<?php

namespace App\Http\Controllers\API;

use App\Contracts\CurrencyInterface;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Services\Currency\CurrencyRates;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 *
 */
class CurrencyController extends Controller implements CurrencyInterface
{

    /**
     * @OA\Get(
     *      path="/currency/{currencyCode}",
     *      operationId="changeSessionCurrency",
     *      tags={"Currency"},
     *      summary="change session currency",
     *      description="change session currency",
     *      @OA\Parameter(
     *          name="currencyCode",
     *          description="Currency code",
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
     *          description="Currency not found",
     *      )
     *     )
     */


    public function changeCurrency(string $currencyCode): \Illuminate\Http\JsonResponse
    {
        try {
            $currency = Currency::byCode($currencyCode)->firstOrFail();
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['status'=>'Нет такой'],403);
        }
        session(['currency' => $currencyCode]);
        return response()->json(['status'=>'Currency has changed'],200);
    }


    /**
     * @OA\Get(
     *      path="/currentValues",
     *      operationId="changeCurrency",
     *      tags={"Currency"},
     *      summary="change currency",
     *      description="change currency",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *       )
     *     )
     */


    public function current(): \Illuminate\Http\JsonResponse
    {
        CurrencyRates::getRates();
        return response()->json(['status'=>'changed'],200);
    }
}
