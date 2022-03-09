<?php

namespace App\Services;
use Http;

class CurrencyRates
{
    public static function getRates() {
       $baseCurrency = CurrencyConvertion::getBaseCurrency();
        $url = 'http://api.exchangeratesapi.io/v1/latest?access_key=11a9a12deb276fdfd868d4d598152835&symbols=RUB,USD,EUR';
        $response = Http::get($url)->getBody()->getContents();
        $rates = json_decode($response,true)['rates'];
        $newRates = [
            "RUB"=>$rates['EUR'],
            "USD"=>$rates['RUB'] / $rates["USD"],
            "EUR"=>$rates['RUB']
        ];
        foreach(CurrencyConvertion::getCurrencies() as $currency) {
                $currency->update(['rate'=>$newRates[$currency->code]]);
        }
    }
}
