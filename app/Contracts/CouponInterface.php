<?php

namespace App\Contracts;

use App\Http\Requests\CouponRequest;

interface CouponInterface
{
    public function index();
    public function store(CouponRequest $request);
    public function changeCurrency(string $coupon);
}