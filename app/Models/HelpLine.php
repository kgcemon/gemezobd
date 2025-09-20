<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpLine extends Model
{
    protected $table = 'help_line';

    protected $fillable = [
      'name',
      'image',
      'url'
    ];
}
