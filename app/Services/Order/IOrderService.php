<?php

namespace App\Services\Order;

interface IOrderService
{
    public function getCarts($user_id);
    public function createOrder($user,$address,$validated,$coupon);
    public function createOrderItem($carts,$order_id);
}