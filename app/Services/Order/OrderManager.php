<?php

namespace App\Services\Order;

use App\Services\Stripe\StripeService;

class OrderManager implements IOrderManager
{
    private $shops = [];
    private $app;
    public function __construct($app)
    {
        $this->app = $app;
    }
    public function make($name): IOrderService
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
    private function createOrderService(): IOrderService
    {
        $service = new OrderService();
        return $service;
    }
}