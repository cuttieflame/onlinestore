<?php

namespace App\Services;

class PaymentMethodService
{
    public $card_brand;
    public $last4;
    public $exp_month;
    public $exp_year;
    public static function makeCardParametr($card) {
        $crd = new PaymentMethodService();
        $crd->card_brand = $card['card']->brand;
        $crd->last4 = $card['card']->last4;
        $crd->exp_month = $card['card']->exp_month;
        $crd->exp_year = $card['card']->exp_year;
        return $crd;
    }
}