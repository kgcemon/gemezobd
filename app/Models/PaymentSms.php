<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSms extends Model
{
    protected $table = 'payment_sms';
    protected $fillable = ['order_number','sender','number','trxID','status','amount'];
}
