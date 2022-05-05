<?php
declare(strict_types=1);
namespace App\Http\Controllers\API;

use App\DataTransferObjects\CouponData;
use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use App\Models\Currency;
use App\Services\CurrencyRates;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index() {
        $coupon = Coupon::get();
        return response()->json(['data'=>$coupon],200);
    }
    public function store(CouponRequest $request) {
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
        return response()->json(['status'=>'Coupon made successfully'],200);
    }
    public function changeCurrency($coupon)
    {
        session()->forget('currency');
        $cpn = Coupon::where('code',$coupon)->firstOrFail();
        $currency = Currency::where('id',$cpn->currency_id)->firstOrFail();
        session(['coupon' => $currency->code,'coupon_value'=>$cpn->value]);
    }
}
