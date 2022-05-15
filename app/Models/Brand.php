<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 * @method select(string[] $array)
 */
class Brand extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'title'
    ];

    /**
     * @return HasMany
     */
    public function subbrand(): HasMany
    {
        return $this->hasMany(Brand::class, 'parent_id');
    }

}
