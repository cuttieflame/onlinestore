<?php

namespace App\Models;

use App\Services\CouponConvertion;
use App\Services\CurrencyConvertion;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'value', 'type', 'currency_id', 'only_once', 'expired_at', 'description'];

    protected $dates = ['expired_at'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function isAbsolute()
    {
        return $this->type === 1;
    }

    public function isOnlyOnce()
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
