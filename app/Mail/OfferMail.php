<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OfferMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $discount;
    public $coupon;
    public $expiryDate;
    public $offerUrl;

    public function __construct($name, $discount, $coupon, $expiryDate, $offerUrl)
    {
        $this->name = $name;
        $this->discount = $discount;
        $this->coupon = $coupon;
        $this->expiryDate = $expiryDate;
        $this->offerUrl = $offerUrl;
    }

    public function build()
    {
        return $this->subject('🎉 বিশেষ অফার চলছে - ' . $this->discount . '% ছাড়!')
            ->view('mail.offer');
    }
}
