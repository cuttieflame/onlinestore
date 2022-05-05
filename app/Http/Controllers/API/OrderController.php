<?php

namespace App\Http\Controllers\API;

use App\DataTransferObjects\OrderData;
use App\Exceptions\CartNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\GetIpAdress;
use App\Services\Order\IOrderManager;
use App\Services\UserIndexService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $ip;
    private $user;
    public function __construct(GetIpAdress $ip,UserIndexService $user)
    {
        $this->ip = $ip;
        $this->user = $user;
    }
    public function makeOrder(OrderRequest $request,$id) {
        $validated = OrderData::fromRequest($request);
        $address = $this->ip->getIp();
        $abc = app(IOrderManager::class);
        $service = $abc->make('order');
        try {
            $user = $this->user->getUser($id);
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['error'=>'Model not found']);
        }
        try {
            $carts = $service->getCarts($user->id);
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['error'=>'Cart not found']);
        }
        try {
            $coupon = Coupon::where('code',$validated->give_code)->firstOrFail();
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['error'=>'Coupon not valid']);
        }
        $order = $service->createOrder($user,$address,$validated,$coupon);
        $amount = $service->createOrderItem($carts,$order->id);
        Order::where('order_id',$order->id)->update(['amount'=>$amount]);
        Cart::where('user_id',3)->delete();
        return response()->json(['status'=>'uspeshno']);
    }

}
