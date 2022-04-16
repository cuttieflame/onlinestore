<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTypeBrand extends Model
{
    use HasFactory;
    protected $table = 'products_types_brands';
    public $timestamps = false;
    protected $casts = [
        'categories'=>'array',
    ];
}
