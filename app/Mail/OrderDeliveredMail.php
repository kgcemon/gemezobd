<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderDeliveredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $customerName;
    public $orderId;
    public $orderDate;
    public $orderAmount;
    public $orderLink;
    public $orderItem;
    public $orderAddress;

    public function __construct($customerName, $orderId, $orderDate, $orderAmount, $orderLink, $orderItem, $orderAddress)
    {
        $this->customerName = $customerName;
        $this->orderId = $orderId;
        $this->orderDate = $orderDate;
        $this->orderAmount = $orderAmount;
        $this->orderLink = $orderLink;
        $this->orderItem = $orderItem;
        $this->orderAddress = $orderAddress;
    }

    public function build()
    {
        return $this->subject('Your Order #' . $this->orderId . ' has been Delivered 🎉')
            ->view('mail.delivery');
    }
}
