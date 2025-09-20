<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderRefundMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $orderId;
    public $date;
    public $amount;
    public $orderUrl;

    public function __construct($name, $orderId, $date, $amount, $orderUrl)
    {
        $this->name = $name;
        $this->orderId = $orderId;
        $this->date = $date;
        $this->amount = $amount;
        $this->orderUrl = $orderUrl;
    }

    public function build()
    {
        return $this->subject('রিফান্ড সম্পন্ন হয়েছে')
            ->view('mail.order_refund');
    }
}
