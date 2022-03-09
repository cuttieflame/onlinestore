<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Currency;

class CurrencyController extends Controller
{

    //Изменение валюты
    public function changeCurrency($currencyCode)
    {
        $currency = Currency::byCode($currencyCode)->first();
       return redirect()->back()->with('currency',$currency->code);

    }
}
