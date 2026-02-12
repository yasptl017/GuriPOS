<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WebsiteOrderReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $subjectLine;
    public $introMessage;
    public $currencyIcon;

    public function __construct(Order $order, $subjectLine, $introMessage = '')
    {
        $this->order = $order;
        $this->subjectLine = $subjectLine;
        $this->introMessage = $introMessage;
        $this->currencyIcon = optional(Setting::first())->currency_icon ?? '$';
    }

    public function build()
    {
        $mail = $this->subject($this->subjectLine)
            ->view('user.order_receipt_email');

        if (!empty($this->order->print_receipt)) {
            $mail->attachData(
                $this->order->print_receipt,
                'receipt-' . $this->order->order_id . '.txt',
                ['mime' => 'text/plain']
            );
        }

        return $mail;
    }
}
