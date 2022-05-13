<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 */
class Coupon extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = ['code', 'value', 'type', 'currency_id', 'only_once', 'expired_at', 'description'];

    /**
     * @var string[]
     */
    protected $dates = ['expired_at'];
    private mixed $type;

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * @return bool
     */
    public function isAbsolute(): bool
    {
        return $this->type === 1;
    }

    /**
     * @return bool
     */
    public function isOnlyOnce(): bool
    {
        return $this->only_once === 1;
    }
//    public function getPriceAttribute($value) {
//
//        return CouponConvertion::convert($value);
//    }
//    public function availableForUse()
//    {
//        $this->refresh();
//        if (!$this->isOnlyOnce() || $this->orders->count() === 0) {
//            return is_null($this->expired_at) || $this->expired_at->gte(Carbon::now());
//        }
//        return false;
//    }
//
//    public function applyCost($price, Currency $currency = null)
//    {
//        if ($this->isAbsolute()) {
//            return $price - CurrencyConvertion::convert($this->value, $this->currency->code, $currency->code);
//        } else {
//            return $price - ($price * $this->value / 100);
//        }
//    }
}
