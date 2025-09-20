<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{

    protected $fillable = ['name', 'description','thumbnail','slug','sort'];



    protected $table = 'categories';

    protected $hidden = ['created_at', 'updated_at', 'sort', 'id'];

    public function products()
    {
        return $this->hasMany(Product::class,
            'category_id')->where('name', '!=', 'Wallet')->where('stock', '=', 1)
            ->select('id', 'name', 'image', 'slug', 'category_id')->orderBy('sort');
    }
}
