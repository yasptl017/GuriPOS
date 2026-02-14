<?php

namespace App\Services;

use App\Models\PrintJob;
use App\Models\Setting;
use Illuminate\Support\Str;

class PrinterService
{
    protected $kitchenPrinter;
    protected $deskPrinter;
    protected $printMode;   // 'poll' | 'push'
    protected $agentPort;   // local port for push mode (default 5757)

    public function __construct()
    {
        $setting = Setting::select('kitchen_printer', 'desk_printer', 'print_mode', 'agent_port')->first();

        $this->kitchenPrinter = $this->normalizePrinterName(optional($setting)->kitchen_printer);
        $this->deskPrinter    = $this->normalizePrinterName(optional($setting)->desk_printer);
        $this->printMode      = optional($setting)->print_mode ?? 'poll';
        $this->agentPort      = (int)(optional($setting)->agent_port ?? 5757);
    }

    /**
     * Queue a kitchen print job (raw content already formatted).
     */
    public function sendToKitchen($order)
    {
        if (!$this->kitchenPrinter) {
            return "Warning: Kitchen printer is not configured.";
        }

        $job = PrintJob::create([
            'order_id' => $order->id ?? null,
            'printer'  => $this->kitchenPrinter,
            'content'  => is_string($order) ? $order : $this->formatOrderDetails($order),
            'status'   => PrintJob::STATUS_PENDING,
        ]);

        $this->pushToAgent($job);

        return "Order successfully sent to kitchen printer.";
    }

    /**
     * Queue a desk/receipt print job (raw content already formatted).
     */
    public function sendToDesk($order)
    {
        if (!$this->deskPrinter) {
            return "Warning: Desk printer is not configured.";
        }

        $job = PrintJob::create([
            'order_id' => $order->id ?? null,
            'printer'  => $this->deskPrinter,
            'content'  => is_string($order) ? $order : $this->formatOrderDetails($order),
            'status'   => PrintJob::STATUS_PENDING,
        ]);

        $this->pushToAgent($job);

        return "Order successfully sent to desk printer.";
    }

    /**
     * Format order then queue for kitchen printer.
     */
    public function printToKitchen($order)
    {
        if (!$this->kitchenPrinter) {
            return "Warning: Kitchen printer is not configured.";
        }

        $job = PrintJob::create([
            'order_id' => $order->id ?? null,
            'printer'  => $this->kitchenPrinter,
            'content'  => $this->formatOrderDetails($order),
            'status'   => PrintJob::STATUS_PENDING,
        ]);

        $this->pushToAgent($job);

        return "Order successfully sent to kitchen printer.";
    }

    /**
     * Format order then queue for desk printer.
     */
    public function printToDesk($order)
    {
        if (!$this->deskPrinter) {
            return "Warning: Desk printer is not configured.";
        }

        $job = PrintJob::create([
            'order_id' => $order->id ?? null,
            'printer'  => $this->deskPrinter,
            'content'  => $this->formatOrderDetails($order),
            'status'   => PrintJob::STATUS_PENDING,
        ]);

        $this->pushToAgent($job);

        return "Order successfully sent to desk printer.";
    }

    public function getFormattedReceipt($order): string
    {
        return $this->formatOrderDetails($order);
    }

    /**
     * If print_mode is 'push', immediately POST the job to the local print agent
     * running on localhost:{agent_port}. Falls back silently if agent is unreachable.
     */
    protected function pushToAgent(PrintJob $job): void
    {
        if ($this->printMode !== 'push') {
            return;
        }

        $url     = "http://127.0.0.1:{$this->agentPort}/print";
        $payload = json_encode([
            'id'       => $job->id,
            'order_id' => $job->order_id,
            'printer'  => $job->printer,
            'content'  => $job->content,
            'key'      => env('PRINT_AGENT_KEY'),
        ]);

        try {
            $context = stream_context_create([
                'http' => [
                    'method'  => 'POST',
                    'header'  => "Content-Type: application/json\r\nAccept: application/json\r\n",
                    'content' => $payload,
                    'timeout' => 3,
                    'ignore_errors' => true,
                ],
            ]);

            $response = @file_get_contents($url, false, $context);

            if ($response !== false) {
                $data = json_decode($response, true);
                if (!empty($data['success'])) {
                    // Agent printed successfully — mark job as printed
                    $job->status     = PrintJob::STATUS_PRINTED;
                    $job->printed_at = now();
                    $job->save();
                }
            }
        } catch (\Throwable $e) {
            // Agent unreachable — job stays pending, poll mode will pick it up
        }
    }

    protected function formatOrderDetails($order)
    {
        $output   = "";
        $subtotal = 0;
        $output .= "         Punjabi Paradise\n";
        $output .= "     419 High Street, Penrith, 2750\n";
        $output .= "Phone: 0247076700     ABN: 86673991529\n";
        $output .= "Order Online at: www.punjabiparadise.com.au\n";
        $output .= str_repeat("-", 42) . "\n";
        $output .= "Customer Details: " . $order->customerDetails . "\n";
        if ($order->type === 'DineIn') {
            $output .= "Table: " . $order->tableNumber . "\n";
        }
        $output .= str_repeat("-", 42) . "\n";
        $output .= "Order No: " . $order->id . "\n";
        $output .= 'Order Type: ' . $order->type . "\n";
        $output .= "Date: " . date('Y-m-d H:i:s') . "\n";
        $output .= str_repeat("-", 42) . "\n";
        $output .= "Items:\n";
        $output .= str_repeat("-", 42) . "\n";
        $output .= sprintf("%-4s %-30s %6s\n", "Qty", "Item", "Amount");
        $output .= str_repeat("-", 42) . "\n";

        foreach ($order->items as $item) {
            $subtotal += $item->price;

            if (empty($item->size) || strtolower($item->size) === 'regular') {
                $name = $item->name;
            } else {
                $name = $item->name . '-' . $item->size;
            }

            if (strlen($name) > 30) {
                $firstLine = substr($name, 0, 30);
                $remaining = substr($name, 30);
                $output .= sprintf("%-4s %-30s %6.2f\n", $item->quantity, $firstLine, $item->price);
                $output .= sprintf("%-4s %-30s\n", "", $remaining);
            } else {
                $output .= sprintf("%-4s %-30s %6.2f\n", $item->quantity, $name, $item->price);
            }

            $output .= "\n";
        }

        $output .= str_repeat("-", 42) . "\n";
        $output .= sprintf("%-30s %12s\n", "Subtotal:", "$" . number_format($subtotal, 2));

        $couponLabel = !empty($order->coupon_name)
            ? "Discount (" . $order->coupon_name . "):"
            : "Discount:";
        $output .= sprintf("%-30s %12s\n", $couponLabel, "$" . number_format($order->discount, 2));
        $output .= sprintf("%-30s %12s\n", "Delivery Charge:", "$" . number_format($order->delivery, 2));
        $output .= str_repeat("-", 42) . "\n";
        $output .= sprintf("%-30s %12s\n", "Total:", "$" . number_format($order->total, 2));
        $output .= str_repeat("-", 42) . "\n";

        if (!empty($order->inst)) {
            $output .= "Special Instructions:\n";
            $output .= wordwrap($order->inst, 42) . "\n";
            $output .= str_repeat("-", 42) . "\n";
        }

        if (isset($order->payment_method)) {
            $output .= sprintf("%-30s %12s\n", "Payment:", $order->payment_method);
            $output .= str_repeat("-", 42) . "\n";
        }

        if (isset($order->status)) {
            $output .= sprintf("%-1s %12s\n", "Status:", $order->status ? "*** " . Str::upper($order->status) . " ***" : "*** PAID ***");
        } else {
            $output .= "         Web Order\n";
            $output .= sprintf("%-1s %12s\n", "Status:", "*** PAID ***");
        }

        $output .= "Thank you!\n";
        return $output;
    }

    protected function normalizePrinterName($name)
    {
        $name = trim((string) $name);
        return $name !== '' ? $name : null;
    }
}
