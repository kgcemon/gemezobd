<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'uid',
        'user_id',
        'product_id',
        'name',
        'email',
        'phone',
        'item_id',
        'quantity',
        'total',
        'customer_data',
        'status',
        'others_data',
        'order_note',
        'payment_method',
        'transaction_id',
        'number',
    ];
    protected static function booted()
    {
        static::creating(function ($order) {
            if (empty($order->uid)) {
                $order->uid = self::generateUid();
            }
        });
    }

    public function usedCodes()
    {
        return $this->hasMany(Code::class, 'order_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->select(['id', 'name','image','input_name','input_others','is_auto']);
    }
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method','id')->select(['id', 'icon', 'method']);
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function autoTopUpProduct()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }


    public static function generateUid(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 15));
        } while (self::where('uid', $code)->exists());

        return $code;
    }


}
