<?php

namespace App;


use App\Models\Image;
use App\Models\PostView;
use App\Models\ProductCategory;
use App\Models\ProductInfo;
use App\Models\ProductPrice;
use App\Services\CurrencyConvertion;
use App\Traits\Imageable;
//use Kirschbaum\PowerJoins\PowerJoins;

use Eav\Attribute;
use Eav\AttributeOption;
use Eav\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Products extends Model
{
    use HasFactory;
//    use PowerJoins;
    protected $table = "products";
    public $incrementing = false;
    const ENTITY  = 'product';

    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'entity_id');
    }
    public function images()
    {
        return $this->hasOne(Image::class, 'product_id')->select(['images','product_id']);
    }
    public function attributesoptions() {
        return $this->hasMany(AttributeOption::class, 'product_id');
    }
    public function productinfo() {
        return $this->hasMany(ProductInfo::class, 'id');
    }
    public function productprice() {
        return $this->belongsTo(ProductPrice::class,'id');
    }
    public function productcategories() {
        return $this->hasMany(ProductCategory::class,'product_id');
    }
    public function postView()
    {
        return $this->hasMany(PostView::class,'product_id');
    }
    protected $casts = [
      'images'=>'array',
    ];

    public function showPost()
    {
        if(auth()->id()==null){
            return $this->postView()
                ->where('ip', '=',  request()->ip())->exists();
        }

        return $this->postView()
            ->where(function($postViewsQuery) { $postViewsQuery
                ->where('session_id', '=', request()->getSession()->getId())
                ->orWhere('user_id', '=', (auth()->check()));})->exists();
    }
    public function scopeWhereUser($query,$id) {
        $query->where('user_id',$id);
    }
    public function scopeWithAttributeOptions($query,$arr) {
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
    public function scopeOrderPrices($query,$a) {
        $b = '';
        if($a == 0) {$b = 'ASC';}
        if($a == 1) {$b = 'DESC';}
        $query->join('product_prices', 'product_prices.id', '=', 'products.id')
        ->orderBy('product_prices.price',$b);
    }

}
