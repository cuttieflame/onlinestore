<?php

namespace App\Models;

use App\Services\CurrencyConvertion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function brands() {
        return $this->hasMany('App\Models\Brand','id')->select(['id','title']);
    }
    public function categories() {
        return $this->hasMany('App\Models\Category','id')->select(['id','title']);
    }
    public function productInfo() {
        return $this->hasOne('App\Models\ProductInfo')->select(['product_id','rating','order_count','name_attributes','attribute_info']);
    }

    protected $casts = [
        'images'=>'array',
        'product_info'=>'array',
        'name_attributes'=>'array',
        'attribute_info'=>'array',
    ];
    public function getPriceAttribute($value) {
        return CurrencyConvertion::convert($value);
    }


}
