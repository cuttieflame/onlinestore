<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function getCurrenciesForProducts();
    public function getBrands();
    public function getCategories();
    public function getProducts($id);
    public function myProducts(Product $product);

    public function search(string $query = '');
}
