<?php

namespace App\Models;

use App\Products;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 */
class ProductView extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'product_views';
    /**
     * @var string[]
     */
    protected $fillable = ['product_id','titleslug','url','session_id','user_id','ip','agent'];

    /**
     * @return BelongsTo
     */
    public function productView(): BelongsTo
    {
        return $this->belongsTo(Products::class);
    }


}
