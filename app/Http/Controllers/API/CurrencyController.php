<?php

namespace App\Http\Controllers\API;

use App\Contracts\CurrencyInterface;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Services\Currency\CurrencyRates;
use Session;

class CurrencyController extends Controller implements CurrencyInterface
{

    //Изменение валюты
    public function changeCurrency(string $currencyCode)
    {
        session(['currency' => $currencyCode]);
        $currency = Currency::byCode($currencyCode)->first();
    }
    public function current() {
        CurrencyRates::getRates();
        return response()->json(['status'=>'changed']);
    }
}
