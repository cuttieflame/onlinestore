<?php

namespace App\Http\Controllers\API;

use App\Contracts\OrderInterface;
use App\DataTransferObjects\OrderData;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Services\GetIpAdress;
use App\Services\Order\IOrderManager;
use App\Services\Stripe\IStripeManager;
use App\Services\User\UserIndexService;
use App\Services\User\UserServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

/**
 *
 */
class OrderController extends Controller implements OrderInterface
{
    /**
     * @var GetIpAdress
     */
    private GetIpAdress $ip;
    /**
     * @var UserServiceInterface
     */
    private UserServiceInterface $userService;

    /**
     * @var IStripeManager
     */
    private IOrderManager $IOrderManager;
    /**
     * @param GetIpAdress $ip
     * @param UserServiceInterface $userService
     */
    public function __construct(GetIpAdress $ip, UserServiceInterface $userService,IOrderManager $IOrderManager)
    {
        $this->ip = $ip;
        $this->userService = $userService;
        $this->IOrderManager = $IOrderManager;
    }

    /**
     * @OA\Post(
     *      path="/order/make/{id}",
     *      operationId="MakeNewOrder",
     *      tags={"Orders"},
     *      summary="create new order",
     *      description="Create new order",
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */


    public function makeOrder(OrderRequest $request,int $id): JsonResponse
    {
        $validated = OrderData::fromRequest($request);
        $address = $this->ip->getIp();
        $service = $this->IOrderManager->make('order');
        try {
            $user = $this->userService->getUser($id);
            $carts = $service->getCarts($user->id);
            $coupon = Coupon::where('code',$validated->give_code)->firstOrFail();
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['error'=>'Error'],403);
        }
        $order = $service->createOrder($user,$address,$validated,$coupon);
        $amount = $service->createOrderItem($carts,$order->id);
        Order::where('order_id',$order->id)->update(['amount'=>$amount]);
        Cart::where('user_id',3)->delete();
        return response()->json(['status'=>'order make'],200);
    }

}
