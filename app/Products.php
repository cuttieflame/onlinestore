<?php

namespace App;


use App\Models\Image;
use App\Models\ProductCategory;
use App\Models\ProductInfo;
use App\Models\ProductPrice;
use App\Models\ProductView;
use App\Services\Filters\FilterBuilder;
use Eav\Attribute;
use Eav\AttributeOption;
use Eav\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @SWG\Definition(
 *  definition="Products",
 *  @SWG\Property(
 *      property="id",
 *      type="serial4"
 *  ),
 *  @SWG\Property(
 *      property="entity_id",
 *      type="integer(4)"
 *  ),
 *  @SWG\Property(
 *      property="attribute_set_id",
 *      type="integer(4)"
 *  )
 *  @SWG\Property(
 *      property="user_id",
 *      type="integer(8)"
 *  )
 * )
 * @method withAttributeOptions(string[] $array)
 * @method where(string $string, int $id)
 * @method whereUser(int $id)
 */

class Products extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = "products";
    /**
     *
     */
    const ENTITY  = 'product';
    /**
     * @var string[]
     */
    public $fillable = ['id','entity_id','attribute_set_id','user_id'];

    /**
     * @return HasMany
     */
    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class, 'entity_id');
    }

    /**
     * @return HasOne
     */
    public function images(): HasOne
    {
        return $this->hasOne(Image::class, 'product_id')->select(['images','product_id']);
    }

    /**
     * @return HasMany
     */
    public function attributesoptions(): HasMany
    {
        return $this->hasMany(AttributeOption::class, 'product_id');
    }

    /**
     * @return HasMany
     */
    public function productinfo(): HasMany
    {
        return $this->hasMany(ProductInfo::class, 'id');
    }

    /**
     * @return BelongsTo
     */
    public function productprice(): BelongsTo
    {
        return $this->belongsTo(ProductPrice::class,'id');
    }

    /**
     * @return HasMany
     */
    public function productcategories(): HasMany
    {
        return $this->hasMany(ProductCategory::class,'product_id');
    }

    /**
     * @return HasMany
     */
    public function productview(): HasMany
    {
        return $this->hasMany(ProductView::class,'product_id');
    }

    /**
     * @var string[]
     */
    protected $casts = [
      'images'=>'array',
    ];

    /**
     * @param $query
     * @param $id
     * @return void
     */
    public function scopeWhereUser($query, $id) {
        $query->where('user_id',$id);
    }

    /**
     * @param $query
     * @param $arr
     * @return void
     */
    public function scopeWithAttributeOptions($query, $arr) {
        $query->with(['attributesoptions' => function ($q) {
            $q->select(['attribute_id','value','product_id'])->orderBy('attribute_id');
        }]);
        if (in_array("pr-price", $arr)) {
           $query->with('productprice');
        }
        if (in_array("pr-ctgrs", $arr)) {
            $query->with(['productcategories' => function ($q) {
                $q->with(['childrenCategories' => function ($q) {
                    $q->with('productcategories');
                }]);
            }]);
        }
        if (in_array("img", $arr)) {
            $query->with('images');
        }
        if (in_array("pr-info", $arr)) {
            $query->with('productinfo');
        }
    }

    /**
     * @param $query
     * @param $a
     * @return void
     */
    public function scopeOrderPrices($query, $a) {
        $b = '';
        if($a == 0) {$b = 'ASC';}
        if($a == 1) {$b = 'DESC';}
        $query->join('product_prices', 'product_prices.id', '=', 'products.id')
        ->orderBy('product_prices.price',$b);
    }

    /**
     * @param $query
     * @param $filters
     * @return mixed
     */
    public function scopeFilterBy($query, $filters): mixed
    {
        $namespace = 'App\Services\FilterBuilder';
        $filter = new FilterBuilder($query, $filters, $namespace);

        return $filter->apply();
    }

}
