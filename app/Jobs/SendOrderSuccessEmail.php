<?php

namespace App\Jobs;

use App\Mail\WebsiteOrderReceipt;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use Mail;

class SendOrderSuccessEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userEmail;
    protected $orderId;
    protected $subject;
    protected $introMessage;

    public function __construct($userEmail, $orderId, $subject, $introMessage = '')
    {
        $this->userEmail = $userEmail;
        $this->orderId = $orderId;
        $this->subject = $subject;
        $this->introMessage = $introMessage;
    }

    public function handle()
    {
        if (!filter_var($this->userEmail, FILTER_VALIDATE_EMAIL)) {
            Log::warning('Receipt email skipped due to invalid recipient', [
                'order_id' => $this->orderId,
                'email' => $this->userEmail,
            ]);
            return;
        }

        try {
            $order = Order::with(['orderProducts', 'orderAddress'])->find($this->orderId);
            if (!$order) {
                Log::warning('Receipt email skipped because order was not found', ['order_id' => $this->orderId]);
                return;
            }

            Mail::to($this->userEmail)->send(
                new WebsiteOrderReceipt($order, $this->subject, $this->introMessage)
            );
        } catch (\Throwable $e) {
            Log::error('Receipt email failed', [
                'order_id' => $this->orderId,
                'email' => $this->userEmail,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
