<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderFailedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $orderId;
    public $date;
    public $amount;
    public $retryUrl;

    public function __construct($name, $orderId, $date, $amount, $retryUrl)
    {
        $this->name = $name;
        $this->orderId = $orderId;
        $this->date = $date;
        $this->amount = $amount;
        $this->retryUrl = $retryUrl;
    }

    public function build()
    {
        return $this->subject('Order Failed - Please Retry')
            ->view('mail.order_failed');
    }
}
