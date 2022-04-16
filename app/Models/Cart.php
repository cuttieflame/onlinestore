<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\MassPrunable;
use App\Models\Product;

class Cart extends Model
{
    use HasFactory;
    use MassPrunable;
    protected $fillable = ["session_id", "user_id", "product_id", "price", "quantity"];

    protected $attributes = [
        "quantity" => 1,
    ];
    protected $casts = [
        'product'=>'array',
    ];
    public function prunable()
    {
        return static::where('created_at', '<=', now()->subDays(1));
    }
    public function user() {
        return $this->belongsTo('App\Models\User');
    }
    public function product() {
        return $this->hasMany('App\Products','id','product_id');
    }
//    public function vars() {
//        return $this->belongsToMany(config("cart.product_variant_model"), "cart_vars", "cart_id", "variant_id");
//    }
}
