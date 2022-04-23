<?php

namespace App\Contracts;
use App\Http\Requests\ProductStoreRequest;
use Illuminate\Http\Request;

interface ProductInterface
{
    public function index();
    public function userProduct(int $id,Request $request);
    public function store(ProductStoreRequest $request);
}