<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_categories';
    public $incrementing = false;
    public $timestamps = false;
    use HasFactory;

    public function categories() {
        return $this->hasMany(Category::class,'parent_id');
    }
    public function childrenCategories()
    {
        return $this->hasMany(Category::class,'parent_id','category_id');
    }
}
