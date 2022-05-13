<?php

namespace App\Models;

use Eav\AttributeOption;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;


/**
 * @method static create(array $array)
 * @method static select(string[] $array)
 */
class Cart extends Model
{
    use HasFactory;
    use MassPrunable;

    /**
     * @var string[]
     */
    protected $fillable = ["session_id", "user_id", "product_id", "price", "quantity"];

    /**
     * @var int[]
     */
    protected $attributes = [
        "quantity" => 1,
    ];
    /**
     * @var string[]
     */
    protected $casts = [
        'product'=>'array',
    ];

    /**
     * @return mixed
     */
    public function prunable(): mixed
    {
        return static::where('created_at', '<=', now()->subDays(1));
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return HasOne
     */
    public function product(): HasOne
    {
        return $this->hasOne('App\Products','id','product_id');
    }

    /**
     * @return HasMany
     */
    public function attributesoptions(): HasMany
    {
        return $this->hasMany(AttributeOption::class,'product_id','product_id');
    }

    /**
     * @return BelongsTo
     */
    public function productprice(): BelongsTo
    {
        return $this->belongsTo(ProductPrice::class,'product_id','id');
    }

//    public function vars() {
//        return $this->belongsToMany(config("cart.product_variant_model"), "cart_vars", "cart_id", "variant_id");
//    }
}
