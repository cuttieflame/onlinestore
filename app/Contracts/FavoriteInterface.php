<?php

namespace App\Contracts;

interface FavoriteInterface
{
    public function userFavorites($id);
    public function get();
    public function add(int $product_id);
    public function remove(int $id);
    public function flush();
    public function total();
}