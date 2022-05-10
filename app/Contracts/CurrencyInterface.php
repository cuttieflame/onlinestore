<?php

namespace App\Contracts;

interface CurrencyInterface
{
    public function changeCurrency(string $currencyCode);
    public function current();
}