<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class ProductInfo extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'products_info';
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string[]
     */
    protected $fillable = ['id','rating','order_count','name_attributes','attribute_info'];
    /**
     * @var string[]
     */
    protected $casts = [
        'name_attributes' => 'array',
        'attribute_info'=>'array',
    ];
}
