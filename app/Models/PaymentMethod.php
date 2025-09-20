<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'icon',
        'method',
        'number',
        'description',
        'status',
    ];
}
