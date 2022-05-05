<?php

namespace App\Services\Stripe;

interface IStripeManager
{
    public function make($name): IStripeService;
}