<?php

namespace App\Contracts;

use App\Http\Requests\OrderRequest;

interface OrderInterface
{
    public function makeOrder(OrderRequest $request,int $id);
}