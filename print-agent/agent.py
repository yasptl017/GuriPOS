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
import socket
import tkinter as tk
from tkinter import ttk, messagebox, scrolledtext
from datetime import datetime
import urllib.request
import urllib.parse
import urllib.error
from http.server import BaseHTTPRequestHandler, ThreadingHTTPServer

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

# ─── Config ──────────────────────────────────────────────────────────────────

APP_NAME      = "Punjabi Paradise Print Agent"
APP_VERSION   = "1.0.0"
CONFIG_FILE   = os.path.join(os.environ.get("APPDATA", "."), "PunjabiParadisePrintAgent", "config.json")
POLL_INTERVAL = 1
LOG_MAX_LINES = 200

DEFAULT_CONFIG = {
    "server_url":      "",
    "api_key":         "",
    "receipt_printer": "",
    "kitchen_printer": "",
    "autostart":       False,
    "poll_interval":   1,
    "push_mode":       False,   # True = listen for HTTP push from server
    "agent_port":      5757,    # local TCP port for push mode
}

# ─── Helpers ─────────────────────────────────────────────────────────────────

def load_config():
    if os.path.exists(CONFIG_FILE):
        try:
            with open(CONFIG_FILE, "r") as f:
                return {**DEFAULT_CONFIG, **json.load(f)}
        except Exception:
            pass
    return dict(DEFAULT_CONFIG)


def save_config(cfg):
    os.makedirs(os.path.dirname(CONFIG_FILE), exist_ok=True)
    with open(CONFIG_FILE, "w") as f:
        json.dump(cfg, f, indent=2)


def get_windows_printers():
    if WIN32_AVAILABLE:
        try:
            printers = win32print.EnumPrinters(
                win32print.PRINTER_ENUM_LOCAL | win32print.PRINTER_ENUM_CONNECTIONS, None, 2
            )
            return [p["pPrinterName"] for p in printers]
        except Exception:
            pass
    return []


def print_raw_text(printer_name, text):
    if not printer_name:
        raise ValueError("Printer name is empty")
    if WIN32_AVAILABLE:
        try:
            hPrinter = win32print.OpenPrinter(printer_name)
            try:
                hJob = win32print.StartDocPrinter(hPrinter, 1, ("Print Job", None, "RAW"))
                win32print.StartPagePrinter(hPrinter)
                win32print.WritePrinter(hPrinter, text.encode("cp437", errors="replace"))
                win32print.EndPagePrinter(hPrinter)
                win32print.EndDocPrinter(hPrinter)
            finally:
                win32print.ClosePrinter(hPrinter)
            return True
        except Exception as e:
            raise RuntimeError(f"win32print error: {e}")
    else:
        tmp = tempfile.NamedTemporaryFile(mode="w", suffix=".txt", delete=False, encoding="utf-8")
        tmp.write(text)
        tmp.close()
        try:
            subprocess.run(["notepad", "/p", tmp.name], timeout=10)
        finally:
            time.sleep(2)
            os.unlink(tmp.name)
        return True


def set_autostart(enabled):
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
        return True, None
    except Exception as e:
        return False, str(e)


def check_autostart_registry():
    """Check if autostart registry entry exists."""
    key_path = r"Software\Microsoft\Windows\CurrentVersion\Run"
    try:
        key = winreg.OpenKey(winreg.HKEY_CURRENT_USER, key_path, 0, winreg.KEY_READ)
        try:
            winreg.QueryValueEx(key, APP_NAME)
            winreg.CloseKey(key)
            return True
        except FileNotFoundError:
            winreg.CloseKey(key)
            return False
    except Exception:
        return False


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


def run_powershell(command, description=""):
    """Run a PowerShell command elevated (as admin)."""
    try:
        result = subprocess.run(
            ["powershell", "-NoProfile", "-NonInteractive", "-Command", command],
            capture_output=True, text=True, timeout=30
        )
        return result.returncode == 0, result.stdout.strip(), result.stderr.strip()
    except Exception as e:
        return False, "", str(e)


def run_as_admin(command):
    """Run a command with UAC elevation."""
    try:
        import ctypes
        # Use ShellExecute to run powershell elevated
        result = ctypes.windll.shell32.ShellExecuteW(
            None, "runas", "powershell",
            f'-NoProfile -NonInteractive -Command "{command}"',
            None, 1
        )
        return result > 32, None
    except Exception as e:
        return False, str(e)


# ─── Push Server ─────────────────────────────────────────────────────────────

class PushRequestHandler(BaseHTTPRequestHandler):
    """Handles POST /print from the Laravel server (push mode)."""

    app_ref = None   # set to PrintAgentApp instance after creation

    def log_message(self, format, *args):
        pass   # silence default request logging

    def do_GET(self):
        """Health check: GET /status"""
        if self.path == "/status":
            self._json(200, {"status": "ok", "mode": "push", "agent": APP_NAME})
        else:
            self._json(404, {"error": "Not found"})

    def do_POST(self):
        if self.path != "/print":
            self._json(404, {"error": "Unknown endpoint"})
            return

        length = int(self.headers.get("Content-Length", 0))
        body   = self.rfile.read(length)

        try:
            data = json.loads(body.decode())
        except Exception:
            self._json(400, {"error": "Invalid JSON"})
            return

        # Validate API key
        expected_key = self.app_ref.cfg.get("api_key", "") if self.app_ref else ""
        if data.get("key") != expected_key:
            self._json(401, {"error": "Unauthorized"})
            return

        # Dispatch print in a background thread so we respond fast
        job = {
            "id":       data.get("id"),
            "order_id": data.get("order_id", "?"),
            "printer":  data.get("printer", ""),
            "content":  data.get("content", ""),
        }

        if self.app_ref:
            threading.Thread(
                target=self.app_ref._handle_push_job,
                args=(job,),
                daemon=True
            ).start()
            self._json(200, {"success": True, "message": "Print job received"})
        else:
            self._json(503, {"error": "Agent not ready"})

    def _json(self, code, data):
        body = json.dumps(data).encode()
        self.send_response(code)
        self.send_header("Content-Type", "application/json")
        self.send_header("Content-Length", str(len(body)))
        self.end_headers()
        self.wfile.write(body)


class PushServer:
    """Wraps ThreadingHTTPServer so it can be started/stopped cleanly."""

    def __init__(self, port, app_ref):
        self.port    = port
        self.server  = None
        self.thread  = None
        PushRequestHandler.app_ref = app_ref

    def start(self):
        try:
            self.server = ThreadingHTTPServer(("127.0.0.1", self.port), PushRequestHandler)
            self.thread = threading.Thread(target=self.server.serve_forever, daemon=True)
            self.thread.start()
            return True, None
        except OSError as e:
            return False, str(e)

    def stop(self):
        if self.server:
            self.server.shutdown()
            self.server = None

    @property
    def running(self):
        return self.server is not None


# ─── Diagnostics Engine ──────────────────────────────────────────────────────

class DiagnosticsResult:
    def __init__(self, name, status, detail, fix_fn=None, fix_label=None):
        self.name      = name       # Short name e.g. "Internet Connection"
        self.status    = status     # "ok" | "warn" | "fail" | "info"
        self.detail    = detail     # Human-readable message
        self.fix_fn    = fix_fn     # Callable to fix the issue, or None
        self.fix_label = fix_label  # Button label e.g. "Add Exception"


def run_diagnostics(cfg):
    results = []

    # 1 ── Internet connectivity
    try:
        socket.setdefaulttimeout(5)
        socket.socket(socket.AF_INET, socket.SOCK_STREAM).connect(("8.8.8.8", 53))
        results.append(DiagnosticsResult(
            "Internet Connection", "ok", "Internet is reachable."
        ))
    except Exception:
        results.append(DiagnosticsResult(
            "Internet Connection", "fail",
            "No internet detected. Check your network cable or Wi-Fi.",
        ))

    # 2 ── DNS / server reachability
    url = cfg.get("server_url", "").strip()
    if url:
        host = urllib.parse.urlparse(url).hostname or ""
        try:
            socket.gethostbyname(host)
            results.append(DiagnosticsResult(
                "Server DNS", "ok", f"'{host}' resolves successfully."
            ))
        except Exception:
            results.append(DiagnosticsResult(
                "Server DNS", "fail",
                f"Cannot resolve '{host}'. Server URL may be wrong or DNS is broken.",
            ))
    else:
        results.append(DiagnosticsResult(
            "Server DNS", "warn", "Server URL not configured. Go to Settings tab."
        ))

    # 3 ── HTTP connection to server
    if url:
        try:
            req = urllib.request.Request(url, headers={"User-Agent": "PrintAgent"})
            urllib.request.urlopen(req, timeout=8)
            results.append(DiagnosticsResult(
                "Server HTTP", "ok", f"Server is reachable at {url}"
            ))
        except urllib.error.HTTPError as e:
            # HTTP error from server = server IS reachable, just auth/404 etc
            results.append(DiagnosticsResult(
                "Server HTTP", "ok",
                f"Server reachable (HTTP {e.code}). That's fine for connectivity."
            ))
        except Exception as e:
            err = str(e)
            detail = f"Cannot connect to server: {err}"
            if "timed out" in err.lower():
                detail = "Connection timed out. Firewall may be blocking outbound HTTPS."
            elif "ssl" in err.lower():
                detail = "SSL error. Server certificate may be invalid."
            results.append(DiagnosticsResult(
                "Server HTTP", "fail", detail
            ))

    # 4 ── API Key / ping
    api_key = cfg.get("api_key", "").strip()
    if url and api_key:
        try:
            data = api_get(f"{url}/api/print-agent/ping?key={urllib.parse.quote(api_key)}")
            if data.get("status") == "ok":
                results.append(DiagnosticsResult(
                    "API Authentication", "ok",
                    f"API key accepted. Server time: {data.get('time', '')}"
                ))
            else:
                results.append(DiagnosticsResult(
                    "API Authentication", "warn",
                    "Server responded but ping format unexpected. Check server code."
                ))
        except urllib.error.HTTPError as e:
            if e.code == 401:
                results.append(DiagnosticsResult(
                    "API Authentication", "fail",
                    "API key rejected (401). Copy the correct key from Admin → Printer Settings."
                ))
            else:
                results.append(DiagnosticsResult(
                    "API Authentication", "warn", f"HTTP {e.code} from ping endpoint."
                ))
        except Exception as e:
            results.append(DiagnosticsResult(
                "API Authentication", "warn", f"Could not reach ping endpoint: {e}"
            ))
    else:
        results.append(DiagnosticsResult(
            "API Authentication", "warn", "API key or server URL not configured."
        ))

    # 5 ── Windows Firewall outbound rule check
    ok, stdout, stderr = run_powershell(
        "Get-NetFirewallRule -Direction Outbound -Action Block -Enabled True | Measure-Object | Select-Object -ExpandProperty Count"
    )
    if ok:
        try:
            block_count = int(stdout.strip())
            if block_count == 0:
                results.append(DiagnosticsResult(
                    "Windows Firewall", "ok",
                    "No outbound block rules found. HTTP traffic should pass freely."
                ))
            else:
                results.append(DiagnosticsResult(
                    "Windows Firewall", "warn",
                    f"{block_count} outbound block rule(s) exist. If connection fails, a rule may be blocking this app.",
                    fix_fn=lambda: _add_firewall_exception(),
                    fix_label="Add Outbound Exception"
                ))
        except Exception:
            results.append(DiagnosticsResult(
                "Windows Firewall", "info", "Could not parse firewall rule count."
            ))
    else:
        results.append(DiagnosticsResult(
            "Windows Firewall", "info",
            "Could not query firewall rules (may need admin). Outbound is usually open by default."
        ))

    # 6 ── Windows Defender / Antivirus exclusion check
    exe_path = sys.executable if getattr(sys, "frozen", False) else os.path.abspath(sys.argv[0])
    ok, stdout, stderr = run_powershell(
        "Get-MpPreference | Select-Object -ExpandProperty ExclusionPath"
    )
    excluded = False
    if ok and exe_path.lower() in stdout.lower():
        excluded = True

    if excluded:
        results.append(DiagnosticsResult(
            "Antivirus Exclusion", "ok",
            "This app is in Windows Defender exclusion list."
        ))
    else:
        results.append(DiagnosticsResult(
            "Antivirus Exclusion", "warn",
            "App is NOT in Windows Defender exclusion list. If printing fails unexpectedly, add it.",
            fix_fn=lambda ep=exe_path: _add_defender_exclusion(ep),
            fix_label="Add Defender Exclusion"
        ))

    # 7 ── win32print availability
    if WIN32_AVAILABLE:
        results.append(DiagnosticsResult(
            "Printer Driver (win32print)", "ok",
            "win32print is available. Direct thermal printing supported."
        ))
    else:
        results.append(DiagnosticsResult(
            "Printer Driver (win32print)", "warn",
            "win32print not found. Printing will fallback to Notepad (slower). Reinstall the app to fix."
        ))

    # 8 ── Receipt printer configured & exists
    receipt_p = cfg.get("receipt_printer", "").strip()
    if receipt_p:
        installed = get_windows_printers()
        if receipt_p in installed:
            results.append(DiagnosticsResult(
                "Receipt Printer", "ok", f"'{receipt_p}' is installed on this PC."
            ))
        else:
            results.append(DiagnosticsResult(
                "Receipt Printer", "fail",
                f"'{receipt_p}' is NOT found in Windows printers. Check name in Settings.",
                fix_fn=lambda: _open_printers_folder(),
                fix_label="Open Printers Folder"
            ))
    else:
        results.append(DiagnosticsResult(
            "Receipt Printer", "warn", "Not configured. Set it in the Settings tab."
        ))

    # 9 ── Kitchen printer configured & exists
    kitchen_p = cfg.get("kitchen_printer", "").strip()
    if kitchen_p:
        installed = get_windows_printers()
        if kitchen_p in installed:
            results.append(DiagnosticsResult(
                "Kitchen Printer", "ok", f"'{kitchen_p}' is installed on this PC."
            ))
        else:
            results.append(DiagnosticsResult(
                "Kitchen Printer", "fail",
                f"'{kitchen_p}' is NOT found in Windows printers. Check name in Settings.",
                fix_fn=lambda: _open_printers_folder(),
                fix_label="Open Printers Folder"
            ))
    else:
        results.append(DiagnosticsResult(
            "Kitchen Printer", "warn", "Not configured. Set it in the Settings tab."
        ))

    # 10 ── Autostart
    if check_autostart_registry():
        results.append(DiagnosticsResult(
            "Auto-start with Windows", "ok",
            "Registry entry exists. App will start automatically on login."
        ))
    else:
        results.append(DiagnosticsResult(
            "Auto-start with Windows", "warn",
            "App will NOT start automatically. Enable in Settings tab.",
            fix_fn=lambda: _enable_autostart_now(),
            fix_label="Enable Auto-start"
        ))

    # 11 ── Push server port (only if push mode enabled)
    if cfg.get("push_mode"):
        port = int(cfg.get("agent_port", 5757))
        try:
            test_sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
            test_sock.settimeout(1)
            result_code = test_sock.connect_ex(("127.0.0.1", port))
            test_sock.close()
            if result_code == 0:
                results.append(DiagnosticsResult(
                    "Push Server Port", "ok",
                    f"Port {port} is open. Push server is accepting connections."
                ))
            else:
                results.append(DiagnosticsResult(
                    "Push Server Port", "fail",
                    f"Port {port} is NOT open. Push server may not have started. Check Settings.",
                ))
        except Exception as e:
            results.append(DiagnosticsResult(
                "Push Server Port", "warn",
                f"Could not test port {port}: {e}"
            ))
    else:
        results.append(DiagnosticsResult(
            "Push Server Port", "info",
            "Push mode is disabled. Enable it in Settings to use instant push printing."
        ))

    return results


# ─── Fix Functions ───────────────────────────────────────────────────────────

def _add_firewall_exception():
    exe_path = sys.executable if getattr(sys, "frozen", False) else os.path.abspath(sys.argv[0])
    cmd = (
        f'New-NetFirewallRule -DisplayName "Punjabi Paradise Print Agent" '
        f'-Direction Outbound -Program "{exe_path}" -Action Allow -Profile Any'
    )
    ok, err = run_as_admin(cmd)
    if ok:
        return True, "Firewall exception added successfully."
    return False, f"Failed: {err}\n\nTry manually: Windows Security → Firewall → Allow an app."


def _add_defender_exclusion(exe_path):
    cmd = f'Add-MpPreference -ExclusionPath "{exe_path}"'
    ok, err = run_as_admin(cmd)
    if ok:
        return True, "Windows Defender exclusion added successfully."
    return False, f"Failed: {err}\n\nTry manually: Windows Security → Virus & threat protection → Exclusions."


def _open_printers_folder():
    subprocess.Popen(["control", "printers"])
    return True, "Opened Devices and Printers."


def _enable_autostart_now():
    ok, err = set_autostart(True)
    if ok:
        return True, "Auto-start enabled. App will start with Windows."
    return False, f"Failed: {err}"


# ─── Main App ─────────────────────────────────────────────────────────────────

class PrintAgentApp:
    def __init__(self):
        self.cfg = load_config()
        self.running = False
        self.poll_thread = None
        self.push_server = None
        self.root = None
        self.tray = None
        self.status_var = None
        self.log_lines = []
        self._diag_widgets = []   # (row_frame, result) tuples for refresh

        self._build_window()
        self._start_polling()
        self._start_push_server_if_needed()
        if TRAY_AVAILABLE:
            self._build_tray()

    # ── Window ────────────────────────────────────────────────────────────

    def _build_window(self):
        self.root = tk.Tk()
        self.root.title(f"{APP_NAME}  v{APP_VERSION}")
        self.root.geometry("780x600")
        self.root.resizable(True, True)
        self.root.protocol("WM_DELETE_WINDOW", self._on_close)
        try:
            self.root.iconbitmap(self._get_icon_path())
        except Exception:
            pass

        nb = ttk.Notebook(self.root)
        nb.pack(fill=tk.BOTH, expand=True, padx=6, pady=6)

        # ── Status Tab ─────────────────────────────────────────────────
        status_frame = ttk.Frame(nb)
        nb.add(status_frame, text="  Status  ")

        self.status_var = tk.StringVar(value="Starting…")
        ttk.Label(status_frame, textvariable=self.status_var,
                  font=("Segoe UI", 11, "bold"), foreground="gray").pack(pady=(14, 4))

        rp_frame = ttk.LabelFrame(status_frame, text="Receipt Printer")
        rp_frame.pack(fill=tk.X, padx=14, pady=4)
        self.receipt_status = tk.StringVar(value="Not configured")
        ttk.Label(rp_frame, textvariable=self.receipt_status).pack(anchor=tk.W, padx=8, pady=4)

        kp_frame = ttk.LabelFrame(status_frame, text="Kitchen Printer")
        kp_frame.pack(fill=tk.X, padx=14, pady=4)
        self.kitchen_status = tk.StringVar(value="Not configured")
        ttk.Label(kp_frame, textvariable=self.kitchen_status).pack(anchor=tk.W, padx=8, pady=4)

        cnt_frame = ttk.LabelFrame(status_frame, text="Print Stats")
        cnt_frame.pack(fill=tk.X, padx=14, pady=4)
        self.jobs_printed = tk.IntVar(value=0)
        self.jobs_failed  = tk.IntVar(value=0)
        ttk.Label(cnt_frame, text="Printed:").grid(row=0, column=0, padx=8, pady=4, sticky=tk.W)
        ttk.Label(cnt_frame, textvariable=self.jobs_printed, foreground="green").grid(row=0, column=1, padx=4)
        ttk.Label(cnt_frame, text="Failed:").grid(row=0, column=2, padx=8, sticky=tk.W)
        ttk.Label(cnt_frame, textvariable=self.jobs_failed, foreground="red").grid(row=0, column=3, padx=4)

        mode_frame = ttk.LabelFrame(status_frame, text="Print Mode")
        mode_frame.pack(fill=tk.X, padx=14, pady=4)
        self.mode_status_var = tk.StringVar(value=self._mode_label())
        self.mode_status_lbl = ttk.Label(mode_frame, textvariable=self.mode_status_var, font=("Segoe UI", 9))
        self.mode_status_lbl.pack(anchor=tk.W, padx=8, pady=4)

        btn_row = ttk.Frame(status_frame)
        btn_row.pack(pady=8)
        ttk.Button(btn_row, text="Test Receipt Print",  command=self._test_receipt).pack(side=tk.LEFT, padx=4)
        ttk.Button(btn_row, text="Test Kitchen Print",  command=self._test_kitchen).pack(side=tk.LEFT, padx=4)
        ttk.Button(btn_row, text="Check Connection",    command=self._check_connection).pack(side=tk.LEFT, padx=4)

        # ── Diagnostics Tab ────────────────────────────────────────────
        diag_outer = ttk.Frame(nb)
        nb.add(diag_outer, text="  Diagnostics  ")
        self._build_diag_tab(diag_outer)

        # ── Log Tab ────────────────────────────────────────────────────
        log_frame = ttk.Frame(nb)
        nb.add(log_frame, text="  Log  ")
        self.log_text = scrolledtext.ScrolledText(
            log_frame, state=tk.DISABLED, height=20, font=("Consolas", 9), wrap=tk.WORD
        )
        self.log_text.pack(fill=tk.BOTH, expand=True, padx=6, pady=6)
        ttk.Button(log_frame, text="Clear Log", command=self._clear_log).pack(pady=4)

        # ── Settings Tab ───────────────────────────────────────────────
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
        self.sv_poll = tk.IntVar(value=self.cfg.get("poll_interval", 1))
        ttk.Spinbox(sf, from_=1, to=10, textvariable=self.sv_poll, width=6).grid(row=row, column=1, padx=4, pady=4, sticky=tk.W)
        row += 1

        ttk.Separator(sf, orient="horizontal").grid(row=row, column=0, columnspan=2, sticky=tk.EW, padx=8, pady=6)
        row += 1

        self.sv_push_mode = tk.BooleanVar(value=self.cfg.get("push_mode", False))
        push_chk = ttk.Checkbutton(
            sf, text="Enable Push Mode  (agent listens for instant print jobs from server)",
            variable=self.sv_push_mode,
            command=self._on_push_mode_toggle
        )
        push_chk.grid(row=row, column=0, columnspan=2, sticky=tk.W, padx=8, pady=2)
        row += 1

        self._push_port_lbl  = ttk.Label(sf, text="Agent Listen Port:")
        self._push_port_lbl.grid(row=row, column=0, sticky=tk.W, padx=8, pady=4)
        self.sv_agent_port = tk.IntVar(value=self.cfg.get("agent_port", 5757))
        self._push_port_spin = ttk.Spinbox(sf, from_=1024, to=65535, textvariable=self.sv_agent_port, width=8)
        self._push_port_spin.grid(row=row, column=1, padx=4, pady=4, sticky=tk.W)
        ttk.Label(sf, text="(must match Admin → Printer Settings → agent port)",
                  foreground="gray", font=("Segoe UI", 8)
                  ).grid(row=row, column=2, padx=4, sticky=tk.W)

        # Show/hide port row based on initial value
        self._toggle_push_port_visibility()

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
        ttk.Button(settings_frame, text="Save & Connect", command=self._save_settings).pack(pady=10)

        self._update_printer_labels()

    # ── Diagnostics Tab ───────────────────────────────────────────────

    def _build_diag_tab(self, parent):
        # Header
        hdr = ttk.Frame(parent)
        hdr.pack(fill=tk.X, padx=10, pady=(10, 4))
        ttk.Label(hdr, text="System Diagnostics",
                  font=("Segoe UI", 11, "bold")).pack(side=tk.LEFT)
        self._diag_run_btn = ttk.Button(hdr, text="Run All Checks",
                                        command=self._run_diagnostics_async)
        self._diag_run_btn.pack(side=tk.RIGHT, padx=4)
        ttk.Button(hdr, text="Fix All Issues",
                   command=self._fix_all).pack(side=tk.RIGHT, padx=4)

        ttk.Label(parent,
                  text="Checks internet, firewall, antivirus, API key, printers and auto-start.",
                  foreground="gray").pack(padx=10, anchor=tk.W)

        # Progress bar (hidden until running)
        self._diag_progress = ttk.Progressbar(parent, mode="indeterminate")

        # Scrollable results area
        container = ttk.Frame(parent)
        container.pack(fill=tk.BOTH, expand=True, padx=10, pady=6)

        canvas = tk.Canvas(container, highlightthickness=0)
        scrollbar = ttk.Scrollbar(container, orient="vertical", command=canvas.yview)
        self._diag_scroll_frame = ttk.Frame(canvas)

        self._diag_scroll_frame.bind(
            "<Configure>",
            lambda e: canvas.configure(scrollregion=canvas.bbox("all"))
        )
        canvas.create_window((0, 0), window=self._diag_scroll_frame, anchor="nw")
        canvas.configure(yscrollcommand=scrollbar.set)

        canvas.pack(side=tk.LEFT, fill=tk.BOTH, expand=True)
        scrollbar.pack(side=tk.RIGHT, fill=tk.Y)

        # Summary label at bottom
        self._diag_summary = tk.StringVar(value="Click 'Run All Checks' to start.")
        ttk.Label(parent, textvariable=self._diag_summary,
                  foreground="gray").pack(pady=(0, 6))

        # Store canvas ref for later
        self._diag_canvas = canvas

    def _run_diagnostics_async(self):
        self._diag_run_btn.config(state=tk.DISABLED)
        self._diag_summary.set("Running checks…")
        self._diag_progress.pack(fill=tk.X, padx=10, pady=2)
        self._diag_progress.start(10)

        def _run():
            results = run_diagnostics(self.cfg)
            self.root.after(0, lambda: self._show_diag_results(results))

        threading.Thread(target=_run, daemon=True).start()

    def _show_diag_results(self, results):
        self._diag_progress.stop()
        self._diag_progress.pack_forget()
        self._diag_run_btn.config(state=tk.NORMAL)

        # Clear previous results
        for w in self._diag_scroll_frame.winfo_children():
            w.destroy()
        self._diag_widgets.clear()

        COLORS  = {"ok": "#16a34a", "warn": "#d97706", "fail": "#dc2626", "info": "#2563eb"}
        ICONS   = {"ok": "✔",       "warn": "⚠",       "fail": "✘",      "info": "ℹ"}
        BG      = {"ok": "#f0fdf4", "warn": "#fffbeb",  "fail": "#fef2f2", "info": "#eff6ff"}

        ok_count   = sum(1 for r in results if r.status == "ok")
        fail_count = sum(1 for r in results if r.status == "fail")
        warn_count = sum(1 for r in results if r.status == "warn")

        for i, result in enumerate(results):
            color = COLORS.get(result.status, "gray")
            icon  = ICONS.get(result.status, "•")
            bg    = BG.get(result.status, "#f9f9f9")

            row_frame = tk.Frame(
                self._diag_scroll_frame,
                background=bg,
                relief="flat",
                bd=0
            )
            row_frame.pack(fill=tk.X, pady=2, padx=2)

            # Icon + name
            left = tk.Frame(row_frame, background=bg)
            left.pack(side=tk.LEFT, fill=tk.BOTH, expand=True, padx=8, pady=6)

            tk.Label(left, text=f"{icon}  {result.name}",
                     font=("Segoe UI", 10, "bold"),
                     foreground=color, background=bg,
                     anchor=tk.W).pack(anchor=tk.W)
            tk.Label(left, text=result.detail,
                     font=("Segoe UI", 9),
                     foreground="#374151", background=bg,
                     wraplength=480, justify=tk.LEFT,
                     anchor=tk.W).pack(anchor=tk.W)

            # Fix button if available
            if result.fix_fn:
                fix_fn   = result.fix_fn
                fix_label = result.fix_label or "Fix"
                btn = tk.Button(
                    row_frame, text=fix_label,
                    font=("Segoe UI", 9),
                    bg="#f59e0b", fg="white",
                    relief="flat", cursor="hand2",
                    padx=10,
                    command=lambda fn=fix_fn, b=None, rf=row_frame, r=result: self._apply_fix(fn, rf, r)
                )
                btn.pack(side=tk.RIGHT, padx=8, pady=8)
                # Store button ref on result for fix-all
                result._fix_btn = btn
            else:
                result._fix_btn = None

            self._diag_widgets.append((row_frame, result))

        # Summary
        if fail_count == 0 and warn_count == 0:
            self._diag_summary.set(f"All {ok_count} checks passed. Everything looks good!")
        else:
            parts = []
            if fail_count:
                parts.append(f"{fail_count} failed")
            if warn_count:
                parts.append(f"{warn_count} warnings")
            parts.append(f"{ok_count} passed")
            self._diag_summary.set("  |  ".join(parts) + "  — Click fix buttons to resolve issues.")

        self.log(f"Diagnostics: {ok_count} OK, {warn_count} warn, {fail_count} fail")

    def _apply_fix(self, fix_fn, row_frame, result):
        """Run a fix function and update the row UI."""
        def _do():
            try:
                ret = fix_fn()
                if isinstance(ret, tuple):
                    success, msg = ret
                else:
                    success, msg = ret, ""
            except Exception as e:
                success, msg = False, str(e)

            def _update():
                if success:
                    messagebox.showinfo("Fix Applied", msg or "Done!")
                    self.log(f"Fix applied: {result.name} — {msg}")
                    # Re-run diagnostics to refresh
                    self._run_diagnostics_async()
                else:
                    messagebox.showerror("Fix Failed", msg or "Could not apply fix automatically.\nPlease do it manually.")
                    self.log(f"Fix failed: {result.name} — {msg}", "ERROR")

            self.root.after(0, _update)

        threading.Thread(target=_do, daemon=True).start()

    def _fix_all(self):
        """Run all available fix functions one by one."""
        fixable = [(rf, r) for (rf, r) in self._diag_widgets if r.fix_fn and r.status != "ok"]
        if not fixable:
            messagebox.showinfo("Fix All", "No issues to fix, or run checks first.")
            return

        def _do():
            results_log = []
            for (rf, result) in fixable:
                try:
                    ret = result.fix_fn()
                    success, msg = (ret if isinstance(ret, tuple) else (ret, ""))
                    results_log.append(f"{'✔' if success else '✘'} {result.name}: {msg or 'done'}")
                except Exception as e:
                    results_log.append(f"✘ {result.name}: {e}")

            def _done():
                summary = "\n".join(results_log)
                messagebox.showinfo("Fix All Complete", summary)
                self.log(f"Fix All: {summary}")
                self._run_diagnostics_async()

            self.root.after(0, _done)

        threading.Thread(target=_do, daemon=True).start()

    # ── Tray ─────────────────────────────────────────────────────────────

    def _build_tray(self):
        icon_img = self._make_tray_icon()
        menu = pystray.Menu(
            TrayItem("Show", self._show_window, default=True),
            TrayItem("Run Diagnostics", lambda: self.root.after(0, self._run_diagnostics_async)),
            TrayItem("Quit", lambda: self.root.after(0, self._quit)),
        )
        self.tray = pystray.Icon(APP_NAME, icon_img, APP_NAME, menu)
        threading.Thread(target=self.tray.run, daemon=True).start()

    def _make_tray_icon(self):
        img  = Image.new("RGB", (64, 64), color=(33, 37, 41))
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
        ts   = datetime.now().strftime("%H:%M:%S")
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
        self.cfg["server_url"]      = self.sv_url.get().rstrip("/")
        self.cfg["api_key"]         = self.sv_key.get()
        self.cfg["receipt_printer"] = self.sv_receipt_printer.get()
        self.cfg["kitchen_printer"] = self.sv_kitchen_printer.get()
        self.cfg["autostart"]       = self.sv_autostart.get()
        self.cfg["poll_interval"]   = int(self.sv_poll.get())
        self.cfg["push_mode"]       = self.sv_push_mode.get()
        self.cfg["agent_port"]      = int(self.sv_agent_port.get())
        save_config(self.cfg)
        set_autostart(self.cfg["autostart"])
        self._update_printer_labels()
        self._restart_push_server()
        self.mode_status_var.set(self._mode_label())
        self.log("Settings saved.")
        self._check_connection()

    def _refresh_printers(self, cb_receipt, cb_kitchen):
        printers = get_windows_printers()
        opts = printers if printers else ["(no printers found)"]
        cb_receipt["values"] = opts
        cb_kitchen["values"] = opts
        self.log(f"Found {len(printers)} printer(s)")

    def _update_printer_labels(self):
        self.receipt_status.set(self.cfg.get("receipt_printer") or "Not configured")
        self.kitchen_status.set(self.cfg.get("kitchen_printer") or "Not configured")

    # ── Connection Check ─────────────────────────────────────────────────

    def _check_connection(self):
        def _do():
            url = self.cfg.get("server_url", "")
            key = self.cfg.get("api_key",    "")
            if not url or not key:
                self._set_status("Not configured — go to Settings tab", "gray")
                return
            try:
                data = api_get(f"{url}/api/print-agent/ping?key={urllib.parse.quote(key)}")
                if data.get("status") == "ok":
                    self._set_status(f"Connected  ✔  {data.get('server', '')}  {data.get('time', '')}", "green")
                    self.log(f"Connection OK: {data}")
                else:
                    self._set_status("Connected but unexpected response", "orange")
            except urllib.error.HTTPError as e:
                if e.code == 401:
                    self._set_status("Wrong API Key (401) — check Settings", "red")
                else:
                    self._set_status(f"HTTP Error {e.code}", "red")
                self.log(f"Connection error: {e}", "ERROR")
            except Exception as e:
                self._set_status(f"Cannot reach server — {e}", "red")
                self.log(f"Connection failed: {e}", "ERROR")

        threading.Thread(target=_do, daemon=True).start()

    def _set_status(self, text, color="gray"):
        self.root.after(0, lambda: self.status_var.set(text))

    # ── Test Prints ──────────────────────────────────────────────────────

    def _test_receipt(self):
        p = self.cfg.get("receipt_printer", "")
        if not p:
            messagebox.showwarning("No Printer", "Receipt printer is not configured.")
            return
        self._do_test_print(p, "Receipt")

    def _test_kitchen(self):
        p = self.cfg.get("kitchen_printer", "")
        if not p:
            messagebox.showwarning("No Printer", "Kitchen printer is not configured.")
            return
        self._do_test_print(p, "Kitchen")

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

    # ── Push Mode ────────────────────────────────────────────────────────

    def _mode_label(self):
        if self.cfg.get("push_mode"):
            port = self.cfg.get("agent_port", 5757)
            return f"Push Mode  — listening on localhost:{port}  (poll fallback every 30s)"
        else:
            interval = self.cfg.get("poll_interval", 1)
            return f"Poll Mode  — checking server every {interval}s"

    def _start_push_server_if_needed(self):
        if self.cfg.get("push_mode"):
            port = int(self.cfg.get("agent_port", 5757))
            self.push_server = PushServer(port, self)
            ok, err = self.push_server.start()
            if ok:
                self.log(f"Push server started on localhost:{port}")
            else:
                self.log(f"Push server failed to start on port {port}: {err}", "ERROR")
                self.push_server = None

    def _restart_push_server(self):
        # Stop existing server if running
        if self.push_server and self.push_server.running:
            self.push_server.stop()
            self.push_server = None
            self.log("Push server stopped.")
        self._start_push_server_if_needed()

    def _on_push_mode_toggle(self):
        self._toggle_push_port_visibility()

    def _toggle_push_port_visibility(self):
        if self.sv_push_mode.get():
            self._push_port_lbl.grid()
            self._push_port_spin.grid()
        else:
            self._push_port_lbl.grid_remove()
            self._push_port_spin.grid_remove()

    def _handle_push_job(self, job):
        """Called by PushRequestHandler when a push print job arrives."""
        receipt_p = self.cfg.get("receipt_printer", "")
        kitchen_p = self.cfg.get("kitchen_printer", "")

        printer_type = job.get("printer", "")
        content      = job.get("content", "")
        order_id     = job.get("order_id", "?")
        job_id       = job.get("id")

        if printer_type == kitchen_p:
            local_printer, label = kitchen_p, "Kitchen"
        elif printer_type == receipt_p:
            local_printer, label = receipt_p, "Receipt"
        else:
            local_printer, label = printer_type, printer_type

        success   = True
        error_msg = None
        try:
            print_raw_text(local_printer, content + "\n\n\n")
            self.log(f"[PUSH] Printed Order #{order_id} → {label} ({local_printer})")
            self.root.after(0, lambda: self.jobs_printed.set(self.jobs_printed.get() + 1))
        except Exception as e:
            success   = False
            error_msg = str(e)
            self.log(f"[PUSH] FAILED Order #{order_id} → {label}: {e}", "ERROR")
            self.root.after(0, lambda: self.jobs_failed.set(self.jobs_failed.get() + 1))

        # Ack back to server so it marks the job printed/failed in DB
        if job_id:
            url = self.cfg.get("server_url", "")
            key = self.cfg.get("api_key", "")
            if url and key:
                try:
                    ack_url = f"{url}/api/print-agent/jobs/{job_id}/ack?key={urllib.parse.quote(key)}"
                    api_post(ack_url, {"success": success, "error": error_msg})
                except Exception as e:
                    self.log(f"[PUSH] Ack failed for job {job_id}: {e}", "WARN")

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
            # In push mode, poll is just a slow fallback (every 30s)
            if self.cfg.get("push_mode"):
                time.sleep(30)
            else:
                time.sleep(self.cfg.get("poll_interval", POLL_INTERVAL))

    def _poll_once(self):
        url = self.cfg.get("server_url", "")
        key = self.cfg.get("api_key",    "")
        if not url or not key:
            return

        jobs = api_get(f"{url}/api/print-agent/jobs?key={urllib.parse.quote(key)}")
        if not jobs:
            return

        self.log(f"Got {len(jobs)} job(s)")

        receipt_p = self.cfg.get("receipt_printer", "")
        kitchen_p = self.cfg.get("kitchen_printer", "")

        for job in jobs:
            job_id       = job["id"]
            printer_type = job["printer"]
            content      = job["content"]
            order_id     = job.get("order_id", "?")

            if printer_type == kitchen_p:
                local_printer, label = kitchen_p, "Kitchen"
            elif printer_type == receipt_p:
                local_printer, label = receipt_p, "Receipt"
            else:
                local_printer, label = printer_type, printer_type

            success   = True
            error_msg = None
            try:
                print_raw_text(local_printer, content + "\n\n\n")
                self.log(f"Printed Order #{order_id} → {label} ({local_printer})")
                self.root.after(0, lambda: self.jobs_printed.set(self.jobs_printed.get() + 1))
            except Exception as e:
                success   = False
                error_msg = str(e)
                self.log(f"FAILED Order #{order_id} → {label}: {e}", "ERROR")
                self.root.after(0, lambda: self.jobs_failed.set(self.jobs_failed.get() + 1))

            try:
                ack_url = f"{url}/api/print-agent/jobs/{job_id}/ack?key={urllib.parse.quote(key)}"
                api_post(ack_url, {"success": success, "error": error_msg})
            except Exception as e:
                self.log(f"Ack failed for job {job_id}: {e}", "WARN")

    # ── Misc ─────────────────────────────────────────────────────────────

    def _on_close(self):
        if TRAY_AVAILABLE and self.tray:
            self.root.withdraw()
        else:
            self._quit()

    def _quit(self):
        self.running = False
        if self.push_server and self.push_server.running:
            self.push_server.stop()
        if self.tray:
            self.tray.stop()
        self.root.destroy()

    def _get_icon_path(self):
        base = os.path.dirname(sys.executable if getattr(sys, "frozen", False) else __file__)
        return os.path.join(base, "icon.ico")

    def run(self):
        self.log(f"{APP_NAME} v{APP_VERSION} started")
        self._check_connection()
        # Auto-run diagnostics on first launch if not configured
        if not self.cfg.get("server_url"):
            self.root.after(1500, self._run_diagnostics_async)
        self.root.mainloop()


# ─── Entry Point ─────────────────────────────────────────────────────────────

if __name__ == "__main__":
    app = PrintAgentApp()
    app.run()
