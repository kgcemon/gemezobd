<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpdateNotice extends Model
{
    protected $table = 'notice_update';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'notice',
        'status',
    ];
}
