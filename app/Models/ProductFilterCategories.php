<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFilterCategories extends Model
{
    use HasFactory;
    protected $table = 'products_filters';
    public $timestamps = false;
    protected $casts = [
        'related_items'=>'array',
        'brands'=>'array',
    ];
}
