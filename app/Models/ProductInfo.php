<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInfo extends Model
{
    use HasFactory;
    protected $table = 'products_info';
    public $timestamps = false;
    protected $fillable = ['id','rating','order_count','name_attributes','attribute_info'];
    protected $casts = [
        'name_attributes' => 'array',
        'attribute_info'=>'array',
    ];
}
