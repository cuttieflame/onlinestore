<?php

namespace App\Models;

use App\Products;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 */
class Favorite extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = ["session_id", "user_id", "product_id"];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class);
    }
}
