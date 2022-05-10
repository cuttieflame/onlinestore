<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface CartInterface
{
    public  function userCarts($id = null);
    public function get();
    public function add(int $product_id);
    public function quantity(Request $request);
    public function remove(int $id);
    public function flush();
    public function total();
}