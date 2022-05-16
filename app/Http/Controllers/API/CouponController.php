<?php
declare(strict_types=1);
namespace App\Http\Controllers\API;

use App\DataTransferObjects\CouponData;
use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use App\Models\Currency;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 *
 */
class CouponController extends Controller
{

    /**
     * @OA\Get(
     *      path="/coupons",
     *      operationId="getCouponsList",
     *      tags={"Coupons"},
     *      summary="Get list of coupons",
     *      description="Returns list of coupons",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="FORBIDDEN",
     *       )
     *     )
     */


    public function index(): JsonResponse
    {
        try {
            $coupon = Coupon::get();
        }
        catch(ModelNotFoundException) {
            return response()->json(['status'=>'Error'],403);
        }
        return response()->json(['data'=>$coupon],200);
    }

    /**
     * @OA\Post(
     *      path="/coupons/make",
     *      operationId="couponMade",
     *      tags={"Coupons"},
     *      summary="Store new coupon",
     *      description="Create new coupon",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      )
     * )
     */

    /**
     * @param CouponRequest $request
     * @return JsonResponse
     */

    public function store(CouponRequest $request): JsonResponse
    {
        $validated = CouponData::fromRequest($request);
        Coupon::create([
            'code'=>$validated->code,
            'value'=>$validated->nominal_value,
            'type'=>$validated->is_absolute,
            'currency_id'=>$validated->currency,
            'only_once'=>$validated->is_only_once,
            'expired_at'=>$validated->expired_at,
            'description'=>$validated->description,
        ]);
        return response()->json(['status'=>'Coupon made successfully'],201);
    }

    /**
     * @OA\Get(
     *      path="/coupons/{coupon}",
     *      operationId="changeCouponCurrency",
     *      tags={"Coupons"},
     *      summary="Change currency",
     *      description="Change coupon currency",
     *      @OA\Parameter(
     *          name="coupon",
     *          description="coupon slug",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="Coupon not found",
     *      )
     *     )
     */

    /**
     * @param string $coupon
     * @return JsonResponse
     */


    public function changeCurrency(string $coupon): JsonResponse
    {
        session()->forget('currency');
        try {
            $cpn = Coupon::where('code',$coupon)->firstOrFail();
        }
        catch(ModelNotFoundException) {
            return response()->json(['error'=>'Coupon not found'],403);
        }
        $currency = Currency::where('id',$cpn->currency_id)->firstOrFail();
        session(['coupon' => $currency->code,'coupon_value'=>$cpn->value]);
        return response()->json(['satus'=>'Successful changed coupon']);
    }
}
