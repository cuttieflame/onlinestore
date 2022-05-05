<?php

namespace App\Services\Order;

use App\Exceptions\CartNotFoundException;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;

class OrderService implements IOrderService
{
    public function getCarts($user_id)
    {
        $carts = Cart::where('user_id',$user_id)->orWhere('session_id',session()->getId())
                ->with(['product' => function ($q) {
                    $q->withAttributeOptions(['pr-price']);
            }])->getOrFail();
        return $carts;
    }
    public function createOrder($user,$address,$validated,$coupon)
    {
       return Order::create([
            'user_id'=>$user->id,
            'name'=>$user->name,
            'email'=>$user->email,
            'phone'=>$user->phone,
            'address'=>$address,
            'comment'=>$validated->description,
            'amount'=>0,
            'coupon_id'=>$coupon ? $coupon->id : null
        ]);
    }
    public function createOrderItem($carts,$order_id) {
        $amount = 0;
        foreach($carts as $elem) {
            $amount += (int)$elem->price;
            OrderItem::create([
                'order_id'=>$order_id,
                'product_id'=>$elem->product_id,
                'name'=>$elem->attributesoptions[0]->value,
                'price'=>(int)$elem->productprice->price,
                'quantity'=>$elem->quantity
            ]);
        }
        return $amount;
    }


}