<?php

namespace App\Services\Stripe;

use Stripe\Stripe;

class StripeManager
{
    private $shops = [];
    private $app;
    public function __construct($app)
    {
        $this->app = $app;
    }
    public function make($name): IStripeService
    {
        $service = \Arr::get($this->shops, $name);
        // No need to create the service every time
        if ($service) {
            return $service;
        }
        $createMethod = 'create' . ucfirst($name) . 'Service';
        if (!method_exists($this, $createMethod)) {
            throw new \Exception("Service $name is not supported");
        }
        $service = $this->{$createMethod}();
        $this->shops[$name] = $service;
        return $service;
    }
    private function createStripeService(): IStripeService
    {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $service = new StripeService($stripe);

        return $service;
    }

}