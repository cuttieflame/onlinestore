<?php

namespace App\Contracts;

interface FavoriteInterface
{
    public function userFavorites($id);
    public function get();
    public function add(int $product_id);
    public function delete(int $id);
    public function clear();
    public function total();
}