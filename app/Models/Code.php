<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $table = 'codes';

    protected $fillable = [
        'code',
        'product_id',
        'item_id',
        'order_id',
        'status',
        'active',
        'denom',
        'uid',
        'note'
    ];


    public function variant()
    {
        return $this->hasOne(Item::class, 'id', 'item_id')->select('id', 'name','denom');
    }

    public function codeByDenom()
    {
        return $this->hasOne(Item::class, 'denom', 'denom');
    }
}
