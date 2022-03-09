<?php

namespace App\Models;
use App\Models\Cart as CartModel;
class CartManager
{
    public function get() {
        return CartModel::get();
    }
    public function add($product_id) {
        return CartModel::add($product_id);
    }
    public function quantity($cart_id, $quantity) {
        return CartModel::quantity($cart_id, $quantity);
    }
    public function remove($cart_id) {
        return CartModel::remove($cart_id);
    }
    public function flush() {
        return CartModel::flush();
    }
    public function total() {
        return CartModel::total();
    }
    public function count() {
        return CartModel::count();
    }
    public function userCarts($id = null) {
        $carts = [];

        $rows = CartModel::userCarts($id);

        if($rows !== false && !empty($rows)) {
            foreach ($rows as $row) {
                $carts[$row->session_id][] = $row;
            }
        }

        return $carts;
    }
}
