<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface CartInterface
{
    public  function userCarts($id = null);
    public function get();
    public function add(int $product_id);
    public function quantity(Request $request);
    public function delete(int $id);
    public function clear();
    public function total();
}