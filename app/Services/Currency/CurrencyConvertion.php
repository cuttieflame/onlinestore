<?php

namespace App\Services\Currency;

use App\Models\Currency;
use Session;
use function session;

class CurrencyConvertion
{
    protected static $container;

    public static function loadContainer() {
        if(is_null(self::$container)) {
            $currencies = Currency::get();
            foreach($currencies as $currency) {
                self::$container[$currency->code] = $currency;
            }
        }
    }
    public static function convert($sum,$originCurrencyCode = 'RUB',$targetCurrencyCode = null) {
        self::loadContainer();
        $originCurrency = self::$container[$originCurrencyCode];

        if(is_null($targetCurrencyCode)) {
            if(session()->has('currency')) {
                $targetCurrencyCode = session()->get('currency','RUB');
            }
            if(session()->has('coupon')) {
                $targetCurrencyCode = session()->get('coupon','RUB');
            }
            if(!session()->has('coupon') and !session()->has('currency')) {
                $targetCurrencyCode = 'RUB';
            }
        }

        $targetCurrency = self::$container[$targetCurrencyCode];
        $gvn = 0;
        if(session()->has('currency')) {
            $gvn = $sum * $originCurrency->rate * $targetCurrency->rate;
        }
        if(session()->has('coupon')) {
            $gvn = $sum * (1 - session()->get('coupon_value') / 100);
        }
        if(!session()->has('coupon') and !session()->has('currency')) {
            $gvn = $sum * $originCurrency->rate * $targetCurrency->rate;
        }
        return $gvn;
    }
    public static function getCurrencies()
    {
        self::loadContainer();

        return self::$container;
    }
    public static function getCurrencySymbol() {
        self::loadContainer();
        $currencyFromSession = Session::get('currency','RUB');
        $currency = self::$container[$currencyFromSession];
        return $currency->symbol;
    }
    public static function getBaseCurrency() {
        self::loadContainer();
        foreach(self::$container as $code => $currency) {
            if($currency->isMain()) {
                return $currency;
            }
        }
    }

}