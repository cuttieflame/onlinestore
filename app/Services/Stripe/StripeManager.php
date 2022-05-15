<?php

namespace App\Services\Stripe;

use Illuminate\Support\Arr;

class StripeManager implements IStripeManager
{
    private array $shops = [];

    /**
     * @param $name
     * @return IStripeService
     * @throws \Exception
     */
    public function make($name): IStripeService
    {
        $service = Arr::get($this->shops, $name);
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