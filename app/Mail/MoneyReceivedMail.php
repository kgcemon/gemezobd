<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MoneyReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $trxId;
    public $date;
    public $amount;
    public $walletUrl;

    public function __construct($name, $trxId, $date, $amount, $walletUrl)
    {
        $this->name = $name;
        $this->trxId = $trxId;
        $this->date = $date;
        $this->amount = $amount;
        $this->walletUrl = $walletUrl;
    }

    public function build()
    {
        return $this->subject("You Have Received Money")
            ->view('mail.money_received');
    }
}
