<?php

namespace App\Services\Currency;

use Illuminate\Support\Facades\Http;

class CurrencyRates
{
    public static function getRates() {
       $baseCurrency = CurrencyConvertion::getBaseCurrency();
        $url = config('currency_rates.api_url')."&symbols=RUB,USD,EUR";
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
