<?php

namespace App\Services\Order;

use App\Services\Stripe\IStripeService;

interface IOrderManager
{
    public function make($name): IOrderService;

}