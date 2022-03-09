<?php

namespace App\Services;

use App\Models\Currency;
use Session;

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
            $targetCurrencyCode = Session::get('currency','RUB');
        }

        $targetCurrency = self::$container[$targetCurrencyCode];

        return $sum * $originCurrency->rate * $targetCurrency->rate;
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
