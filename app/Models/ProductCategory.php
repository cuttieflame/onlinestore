<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static create(array $array)
 */
class ProductCategory extends Model
{
    /**
     * @var string
     */
    protected $table = 'product_categories';
    /**
     * @var bool
     */
    public $incrementing = false;
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string[]
     */
    protected $fillable = ['product_id','category_id'];
    use HasFactory;

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
        return $this->hasMany(Category::class,'parent_id','category_id');
    }
}
