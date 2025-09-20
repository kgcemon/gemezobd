<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SliderImages extends Model
{
    protected $fillable = [
        'link',
        'images_url',
    ];
    protected $table = 'slider_images';
}
