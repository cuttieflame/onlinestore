<?php

namespace App\Contracts;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\UploadProductImageRequest;
use Illuminate\Http\Request;

interface ProductInterface
{
    public function index(Request $request);
    public function userProduct(int $id,Request $request);
    public function store(ProductStoreRequest $request);
    public function uploadProductImage(UploadProductImageRequest $request,int $id);
    public function delete(Request $request);
    public function brandsandcategories();
}