"""
Punjabi Paradise - Desktop Print Agent
Polls the website server for pending print jobs and sends them to local Windows printers.
Runs as a system tray application with a settings/log GUI.
"""

import os
import sys
import json
import time
import winreg
import threading
import tempfile
import subprocess
import tkinter as tk
from tkinter import ttk, messagebox, scrolledtext
from datetime import datetime
import urllib.request
import urllib.parse
import urllib.error

try:
    import win32print
    import win32api
    WIN32_AVAILABLE = True
except ImportError:
    WIN32_AVAILABLE = False

try:
    import pystray
    from pystray import MenuItem as TrayItem
    from PIL import Image, ImageDraw
    TRAY_AVAILABLE = True
except ImportError:
    TRAY_AVAILABLE = False

# ─── Config ─────────────────────────────────────────────────────────────────

APP_NAME    = "Punjabi Paradise Print Agent"
CONFIG_FILE = os.path.join(os.environ.get("APPDATA", "."), "PunjabiParadisePrintAgent", "config.json")
POLL_INTERVAL = 3   # seconds between each poll
LOG_MAX_LINES = 200

DEFAULT_CONFIG = {
    "server_url":       "",
    "api_key":          "",
    "receipt_printer":  "",
    "kitchen_printer":  "",
    "autostart":        False,
    "poll_interval":    3,
}

# ─── Helpers ─────────────────────────────────────────────────────────────────

def load_config():
    if os.path.exists(CONFIG_FILE):
        try:
            with open(CONFIG_FILE, "r") as f:
                data = json.load(f)
                cfg = {**DEFAULT_CONFIG, **data}
                return cfg
        except Exception:
            pass
    return dict(DEFAULT_CONFIG)


def save_config(cfg):
    os.makedirs(os.path.dirname(CONFIG_FILE), exist_ok=True)
    with open(CONFIG_FILE, "w") as f:
        json.dump(cfg, f, indent=2)


def get_windows_printers():
    """Return list of installed printer names."""
    if WIN32_AVAILABLE:
        try:
            printers = win32print.EnumPrinters(
                win32print.PRINTER_ENUM_LOCAL | win32print.PRINTER_ENUM_CONNECTIONS,
                None, 2
            )
            return [p["pPrinterName"] for p in printers]
        except Exception:
            pass
    return []


def print_raw_text(printer_name, text):
    """Send plain text to a Windows printer using a temp file and ShellExecute."""
    if not printer_name:
        raise ValueError("Printer name is empty")

    if WIN32_AVAILABLE:
        # Use win32print for raw text printing (best for thermal printers)
        try:
            hPrinter = win32print.OpenPrinter(printer_name)
            try:
                hJob = win32print.StartDocPrinter(hPrinter, 1, ("Print Job", None, "RAW"))
                win32print.StartPagePrinter(hPrinter)
                # Encode with cp437 (standard for thermal printers) or latin-1
                encoded = text.encode("cp437", errors="replace")
                win32print.WritePrinter(hPrinter, encoded)
                win32print.EndPagePrinter(hPrinter)
                win32print.EndDocPrinter(hPrinter)
            finally:
                win32print.ClosePrinter(hPrinter)
            return True
        except Exception as e:
            raise RuntimeError(f"win32print error: {e}")
    else:
        # Fallback: write to temp file and print via notepad (no win32print installed)
        tmp = tempfile.NamedTemporaryFile(mode="w", suffix=".txt", delete=False, encoding="utf-8")
        tmp.write(text)
        tmp.close()
        try:
            # /p = print, /d = printer
            subprocess.run(
                ["notepad", "/p", tmp.name],
                timeout=10
            )
        finally:
            time.sleep(2)
            os.unlink(tmp.name)
        return True


def set_autostart(enabled):
    """Add or remove the app from Windows startup registry."""
    exe_path = sys.executable if getattr(sys, "frozen", False) else sys.argv[0]
    key_path = r"Software\Microsoft\Windows\CurrentVersion\Run"
    try:
        key = winreg.OpenKey(winreg.HKEY_CURRENT_USER, key_path, 0, winreg.KEY_SET_VALUE)
        if enabled:
            winreg.SetValueEx(key, APP_NAME, 0, winreg.REG_SZ, f'"{exe_path}"')
        else:
            try:
                winreg.DeleteValue(key, APP_NAME)
            except FileNotFoundError:
                pass
        winreg.CloseKey(key)
    except Exception as e:
        print(f"Autostart error: {e}")


def api_get(url):
    req = urllib.request.Request(url, headers={"Accept": "application/json"})
    with urllib.request.urlopen(req, timeout=10) as resp:
        return json.loads(resp.read().decode())


def api_post(url, data):
    body = json.dumps(data).encode()
    req = urllib.request.Request(
        url, data=body,
        headers={"Content-Type": "application/json", "Accept": "application/json"},
        method="POST"
    )
    with urllib.request.urlopen(req, timeout=10) as resp:
        return json.loads(resp.read().decode())


# ─── Main App Window ─────────────────────────────────────────────────────────

class PrintAgentApp:
    def __init__(self):
        self.cfg = load_config()
        self.running = False
        self.poll_thread = None
        self.root = None
        self.tray = None
        self.status_var = None
        self.log_lines = []

        self._build_window()
        self._start_polling()

        if TRAY_AVAILABLE:
            self._build_tray()

    # ── Window ────────────────────────────────────────────────────────────

    def _build_window(self):
        self.root = tk.Tk()
        self.root.title(APP_NAME)
        self.root.geometry("720x560")
        self.root.resizable(True, True)
        self.root.protocol("WM_DELETE_WINDOW", self._on_close)

        # Try to set icon
        try:
            self.root.iconbitmap(self._get_icon_path())
        except Exception:
            pass

        nb = ttk.Notebook(self.root)
        nb.pack(fill=tk.BOTH, expand=True, padx=6, pady=6)

        # ── Status Tab ────────────────────────────────────────────────
        status_frame = ttk.Frame(nb)
        nb.add(status_frame, text="  Status  ")

        self.status_var = tk.StringVar(value="Starting…")
        status_lbl = ttk.Label(status_frame, textvariable=self.status_var,
                               font=("Segoe UI", 11, "bold"), foreground="gray")
        status_lbl.pack(pady=(14, 4))

        # Receipt printer status
        rp_frame = ttk.LabelFrame(status_frame, text="Receipt Printer")
        rp_frame.pack(fill=tk.X, padx=14, pady=4)
        self.receipt_status = tk.StringVar(value="Not configured")
        ttk.Label(rp_frame, textvariable=self.receipt_status).pack(anchor=tk.W, padx=8, pady=4)

        # Kitchen printer status
        kp_frame = ttk.LabelFrame(status_frame, text="Kitchen Printer")
        kp_frame.pack(fill=tk.X, padx=14, pady=4)
        self.kitchen_status = tk.StringVar(value="Not configured")
        ttk.Label(kp_frame, textvariable=self.kitchen_status).pack(anchor=tk.W, padx=8, pady=4)

        # Job counters
        cnt_frame = ttk.LabelFrame(status_frame, text="Print Stats")
        cnt_frame.pack(fill=tk.X, padx=14, pady=4)
        self.jobs_printed = tk.IntVar(value=0)
        self.jobs_failed  = tk.IntVar(value=0)
        ttk.Label(cnt_frame, text="Printed:").grid(row=0, column=0, padx=8, pady=4, sticky=tk.W)
        ttk.Label(cnt_frame, textvariable=self.jobs_printed, foreground="green").grid(row=0, column=1, padx=4)
        ttk.Label(cnt_frame, text="Failed:").grid(row=0, column=2, padx=8, sticky=tk.W)
        ttk.Label(cnt_frame, textvariable=self.jobs_failed, foreground="red").grid(row=0, column=3, padx=4)

        btn_row = ttk.Frame(status_frame)
        btn_row.pack(pady=8)
        ttk.Button(btn_row, text="Test Receipt Print", command=self._test_receipt).pack(side=tk.LEFT, padx=4)
        ttk.Button(btn_row, text="Test Kitchen Print", command=self._test_kitchen).pack(side=tk.LEFT, padx=4)
        ttk.Button(btn_row, text="Check Connection", command=self._check_connection).pack(side=tk.LEFT, padx=4)

        # ── Log Tab ───────────────────────────────────────────────────
        log_frame = ttk.Frame(nb)
        nb.add(log_frame, text="  Log  ")

        self.log_text = scrolledtext.ScrolledText(
            log_frame, state=tk.DISABLED, height=20,
            font=("Consolas", 9), wrap=tk.WORD
        )
        self.log_text.pack(fill=tk.BOTH, expand=True, padx=6, pady=6)
        ttk.Button(log_frame, text="Clear Log", command=self._clear_log).pack(pady=4)

        # ── Settings Tab ──────────────────────────────────────────────
        settings_frame = ttk.Frame(nb)
        nb.add(settings_frame, text="  Settings  ")

        sf = ttk.LabelFrame(settings_frame, text="Server Connection")
        sf.pack(fill=tk.X, padx=14, pady=8)

        row = 0
        ttk.Label(sf, text="Server URL:").grid(row=row, column=0, sticky=tk.W, padx=8, pady=4)
        self.sv_url = tk.StringVar(value=self.cfg.get("server_url", ""))
        ttk.Entry(sf, textvariable=self.sv_url, width=45).grid(row=row, column=1, padx=4, pady=4, sticky=tk.EW)

        row += 1
        ttk.Label(sf, text="API Key:").grid(row=row, column=0, sticky=tk.W, padx=8, pady=4)
        self.sv_key = tk.StringVar(value=self.cfg.get("api_key", ""))
        ttk.Entry(sf, textvariable=self.sv_key, width=45, show="*").grid(row=row, column=1, padx=4, pady=4, sticky=tk.EW)

        row += 1
        ttk.Label(sf, text="Poll every (sec):").grid(row=row, column=0, sticky=tk.W, padx=8, pady=4)
        self.sv_poll = tk.IntVar(value=self.cfg.get("poll_interval", 3))
        ttk.Spinbox(sf, from_=1, to=30, textvariable=self.sv_poll, width=6).grid(row=row, column=1, padx=4, pady=4, sticky=tk.W)

        pf = ttk.LabelFrame(settings_frame, text="Printers")
        pf.pack(fill=tk.X, padx=14, pady=4)

        printers = get_windows_printers()
        printer_opts = printers if printers else ["(no printers found)"]

        row = 0
        ttk.Label(pf, text="Receipt Printer:").grid(row=row, column=0, sticky=tk.W, padx=8, pady=4)
        self.sv_receipt_printer = tk.StringVar(value=self.cfg.get("receipt_printer", ""))
        cb_receipt = ttk.Combobox(pf, textvariable=self.sv_receipt_printer, values=printer_opts, width=42)
        cb_receipt.grid(row=row, column=1, padx=4, pady=4, sticky=tk.EW)

        row += 1
        ttk.Label(pf, text="Kitchen Printer:").grid(row=row, column=0, sticky=tk.W, padx=8, pady=4)
        self.sv_kitchen_printer = tk.StringVar(value=self.cfg.get("kitchen_printer", ""))
        cb_kitchen = ttk.Combobox(pf, textvariable=self.sv_kitchen_printer, values=printer_opts, width=42)
        cb_kitchen.grid(row=row, column=1, padx=4, pady=4, sticky=tk.EW)

        row += 1
        ttk.Label(pf, text="").grid(row=row, column=0)
        ttk.Button(pf, text="Refresh Printer List",
                   command=lambda: self._refresh_printers(cb_receipt, cb_kitchen)
                   ).grid(row=row, column=1, padx=4, pady=4, sticky=tk.W)

        wf = ttk.LabelFrame(settings_frame, text="Windows")
        wf.pack(fill=tk.X, padx=14, pady=4)
        self.sv_autostart = tk.BooleanVar(value=self.cfg.get("autostart", False))
        ttk.Checkbutton(wf, text="Start automatically with Windows",
                        variable=self.sv_autostart).pack(anchor=tk.W, padx=8, pady=4)

        btn_save = ttk.Button(settings_frame, text="Save & Connect", command=self._save_settings)
        btn_save.pack(pady=10)

        self._update_printer_labels()

    def _refresh_printers(self, cb_receipt, cb_kitchen):
        printers = get_windows_printers()
        opts = printers if printers else ["(no printers found)"]
        cb_receipt["values"] = opts
        cb_kitchen["values"] = opts
        self.log(f"Found {len(printers)} printer(s)")

    def _on_close(self):
        """Hide to tray instead of closing."""
        if TRAY_AVAILABLE and self.tray:
            self.root.withdraw()
        else:
            self._quit()

    def _quit(self):
        self.running = False
        if self.tray:
            self.tray.stop()
        self.root.destroy()

    def _get_icon_path(self):
        base = os.path.dirname(sys.executable if getattr(sys, "frozen", False) else __file__)
        return os.path.join(base, "icon.ico")

    # ── Tray ─────────────────────────────────────────────────────────────

    def _build_tray(self):
        icon_img = self._make_tray_icon()
        menu = pystray.Menu(
            TrayItem("Show", self._show_window, default=True),
            TrayItem("Quit", lambda: self.root.after(0, self._quit)),
        )
        self.tray = pystray.Icon(APP_NAME, icon_img, APP_NAME, menu)
        t = threading.Thread(target=self.tray.run, daemon=True)
        t.start()

    def _make_tray_icon(self):
        img = Image.new("RGB", (64, 64), color=(33, 37, 41))
        draw = ImageDraw.Draw(img)
        draw.rectangle([8, 12, 56, 48], outline="white", width=3)
        draw.rectangle([18, 36, 46, 52], fill="white")
        draw.ellipse([26, 20, 38, 32], fill="orange")
        return img

    def _show_window(self):
        self.root.after(0, self.root.deiconify)
        self.root.after(0, self.root.lift)

    # ── Logging ──────────────────────────────────────────────────────────

    def log(self, msg, level="INFO"):
        ts  = datetime.now().strftime("%H:%M:%S")
        line = f"[{ts}] [{level}] {msg}\n"
        self.log_lines.append(line)
        if len(self.log_lines) > LOG_MAX_LINES:
            self.log_lines.pop(0)

        def _append():
            self.log_text.config(state=tk.NORMAL)
            self.log_text.insert(tk.END, line)
            self.log_text.see(tk.END)
            self.log_text.config(state=tk.DISABLED)

        self.root.after(0, _append)

    def _clear_log(self):
        self.log_lines.clear()
        self.log_text.config(state=tk.NORMAL)
        self.log_text.delete("1.0", tk.END)
        self.log_text.config(state=tk.DISABLED)

    # ── Settings ─────────────────────────────────────────────────────────

    def _save_settings(self):
        self.cfg["server_url"]       = self.sv_url.get().rstrip("/")
        self.cfg["api_key"]          = self.sv_key.get()
        self.cfg["receipt_printer"]  = self.sv_receipt_printer.get()
        self.cfg["kitchen_printer"]  = self.sv_kitchen_printer.get()
        self.cfg["autostart"]        = self.sv_autostart.get()
        self.cfg["poll_interval"]    = int(self.sv_poll.get())
        save_config(self.cfg)
        set_autostart(self.cfg["autostart"])
        self._update_printer_labels()
        self.log("Settings saved.")
        self._check_connection()

    def _update_printer_labels(self):
        rp = self.cfg.get("receipt_printer") or "Not configured"
        kp = self.cfg.get("kitchen_printer") or "Not configured"
        if self.receipt_status:
            self.receipt_status.set(rp)
        if self.kitchen_status:
            self.kitchen_status.set(kp)

    # ── Connection Check ─────────────────────────────────────────────────

    def _check_connection(self):
        def _do():
            url = self.cfg.get("server_url", "")
            key = self.cfg.get("api_key", "")
            if not url or not key:
                self._set_status("Not configured", "red")
                return
            try:
                data = api_get(f"{url}/api/print-agent/ping?key={urllib.parse.quote(key)}")
                if data.get("status") == "ok":
                    self._set_status(f"Connected — {data.get('server', '')}  ({data.get('time', '')})", "green")
                    self.log(f"Connection OK: {data}")
                else:
                    self._set_status("Server responded but unexpected format", "orange")
            except urllib.error.HTTPError as e:
                if e.code == 401:
                    self._set_status("Wrong API Key (401 Unauthorized)", "red")
                    self.log(f"Auth error: {e}", "ERROR")
                else:
                    self._set_status(f"HTTP Error {e.code}", "red")
                    self.log(f"HTTP error: {e}", "ERROR")
            except Exception as e:
                self._set_status(f"Cannot reach server: {e}", "red")
                self.log(f"Connection failed: {e}", "ERROR")

        threading.Thread(target=_do, daemon=True).start()

    def _set_status(self, text, color="gray"):
        def _do():
            if self.status_var:
                self.status_var.set(text)
            # Try to update label color
            for w in self.root.winfo_children():
                pass  # Status label color handled via foreground in _build_window
        self.root.after(0, _do)

    # ── Test Prints ──────────────────────────────────────────────────────

    def _test_receipt(self):
        printer = self.cfg.get("receipt_printer", "")
        if not printer:
            messagebox.showwarning("No Printer", "Receipt printer is not configured.")
            return
        self._do_test_print(printer, "Receipt")

    def _test_kitchen(self):
        printer = self.cfg.get("kitchen_printer", "")
        if not printer:
            messagebox.showwarning("No Printer", "Kitchen printer is not configured.")
            return
        self._do_test_print(printer, "Kitchen")

    def _do_test_print(self, printer, label):
        def _do():
            text = (
                "\n"
                "         Punjabi Paradise\n"
                "     419 High Street, Penrith\n"
                "------------------------------------------\n"
                f"  ** TEST PRINT - {label.upper()} PRINTER **\n"
                f"  {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}\n"
                "------------------------------------------\n"
                "  If you see this, printing works!\n"
                "\n\n\n"
            )
            try:
                print_raw_text(printer, text)
                self.log(f"Test print sent to: {printer}")
                messagebox.showinfo("Test Print", f"Test page sent to {label} printer!")
            except Exception as e:
                self.log(f"Test print failed: {e}", "ERROR")
                messagebox.showerror("Print Error", f"Failed to print:\n{e}")

        threading.Thread(target=_do, daemon=True).start()

    # ── Polling Loop ─────────────────────────────────────────────────────

    def _start_polling(self):
        self.running = True
        self.poll_thread = threading.Thread(target=self._poll_loop, daemon=True)
        self.poll_thread.start()

    def _poll_loop(self):
        while self.running:
            try:
                self._poll_once()
            except Exception as e:
                self.log(f"Poll error: {e}", "ERROR")
            interval = self.cfg.get("poll_interval", POLL_INTERVAL)
            time.sleep(interval)

    def _poll_once(self):
        url = self.cfg.get("server_url", "")
        key = self.cfg.get("api_key", "")
        if not url or not key:
            return

        jobs = api_get(f"{url}/api/print-agent/jobs?key={urllib.parse.quote(key)}")

        if not jobs:
            return

        self.log(f"Got {len(jobs)} job(s)")

        for job in jobs:
            job_id  = job["id"]
            printer_type = job["printer"]   # the printer NAME stored in DB
            content = job["content"]
            order_id = job.get("order_id", "?")

            # Map printer name to local printer
            # The DB stores the printer name (e.g. "EPSON TM-T82 Receipt")
            # We also support logical names: if it matches the kitchen_printer setting → use kitchen printer
            # If it matches desk_printer setting → use receipt printer
            receipt_p = self.cfg.get("receipt_printer", "")
            kitchen_p = self.cfg.get("kitchen_printer", "")

            # Determine which local printer to use
            if printer_type == kitchen_p:
                local_printer = kitchen_p
                label = "Kitchen"
            elif printer_type == receipt_p:
                local_printer = receipt_p
                label = "Receipt"
            else:
                # Fallback: try to use the printer name directly
                local_printer = printer_type
                label = printer_type

            success = True
            error_msg = None
            try:
                print_raw_text(local_printer, content + "\n\n\n")
                self.log(f"Printed Order #{order_id} → {label} ({local_printer})")
                self.root.after(0, lambda: self.jobs_printed.set(self.jobs_printed.get() + 1))
            except Exception as e:
                success = False
                error_msg = str(e)
                self.log(f"FAILED Order #{order_id} → {label}: {e}", "ERROR")
                self.root.after(0, lambda: self.jobs_failed.set(self.jobs_failed.get() + 1))

            # Acknowledge the job
            try:
                ack_url = f"{url}/api/print-agent/jobs/{job_id}/ack?key={urllib.parse.quote(key)}"
                api_post(ack_url, {"success": success, "error": error_msg})
            except Exception as e:
                self.log(f"Ack failed for job {job_id}: {e}", "WARN")

    # ── Run ───────────────────────────────────────────────────────────────

    def run(self):
        self.log(f"{APP_NAME} started")
        self._check_connection()
        self.root.mainloop()


# ─── Entry Point ─────────────────────────────────────────────────────────────

if __name__ == "__main__":
    app = PrintAgentApp()
    app.run()
