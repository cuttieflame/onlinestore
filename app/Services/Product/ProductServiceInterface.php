<?php

namespace App\Services\Product;

interface ProductServiceInterface
{
    public function createViewLog(int $product_id);
    public function productArrayDelete(array $arr);
}