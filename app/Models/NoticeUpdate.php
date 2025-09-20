<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeUpdate extends Model
{
    use HasFactory;

    protected $table = 'notice_update';

    protected $fillable = ['notice', 'status'];
}
