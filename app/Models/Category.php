<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @method select(string[] $array)
 */
class Category extends Model
{
    use HasFactory;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return HasMany
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class,'parent_id');
    }

    /**
     * @return HasMany
     */
    public function childrenCategories(): HasMany
    {
        return $this->hasMany(Category::class,'parent_id')
            ->with('categories');
    }

    /**
     * @return HasMany
     */
    public function productcategories(): HasMany
    {
        return $this->hasMany(ProductCategory::class,'id');
    }

}
