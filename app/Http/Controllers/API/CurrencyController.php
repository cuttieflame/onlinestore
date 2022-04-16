<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Services\CurrencyRates;
use Session;

class CurrencyController extends Controller
{

    //Изменение валюты
    public function changeCurrency($currencyCode)
    {
        session(['currency' => $currencyCode]);
        $currency = Currency::byCode($currencyCode)->first();
    }
    public function current() {
        CurrencyRates::getRates();
        return response()->json(['status'=>'changed']);
    }
}
