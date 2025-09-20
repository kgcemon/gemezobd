<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    protected $hidden = ['category_id'];

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'slug',
        'short_description',
        'tags',
        'keywords',
        'input_name',
        'input_others',
        'image',
        'cover_image',
        'sort',
        'stock',
        'total_input',
        'support_country',
        'delivery_system',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }


    public function items()
    {
        return $this->hasMany(Item::class)->orderBy('sort');
    }

    public function category()
    {
        return $this->belongsTo(Categorie::class, 'category_id','id')->select(['id', 'name']);
    }

}
