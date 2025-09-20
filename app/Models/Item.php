<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name','price', 'denom','description','product_id','sort'];
    protected $hidden = ['created_at', 'updated_at','sort','product_id'];

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

}
