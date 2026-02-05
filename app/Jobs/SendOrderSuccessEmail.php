<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\OrderSuccessfully;
use Mail;

class SendOrderSuccessEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userEmail;
    protected $message;
    protected $subject;

    public function __construct($userEmail, $message, $subject)
    {
        $this->userEmail = $userEmail;
        $this->message = $message;
        $this->subject = $subject;
    }

    public function handle()
    {
        Mail::to($this->userEmail)->send(new OrderSuccessfully($this->message, $this->subject));
    }
}