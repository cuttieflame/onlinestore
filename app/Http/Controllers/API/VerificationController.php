<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CouponRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Access\AuthorizationException;

/**
 *
 */
class VerificationController extends Controller
{

    /**
     * @OA\Post(
     *      path="/email/resend",
     *      operationId="email resend",
     *      tags={"User"},
     *      summary="User email resend",
     *      description="User email resend",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */


    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function sendVerificationEmail(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['error'=>'Email already verified'],403);

        }
        $request->user()->sendEmailVerificationNotification();
        return response()->json(['status'=>'письмо отправлено'],200);
    }

    /**
     * @OA\Post(
     *      path="/email/verify/{id}/{hash}}",
     *      operationId="EmailVerify",
     *      tags={"User"},
     *      summary="Email verify",
     *      description="Email verify",
     *     @OA\Parameter(
     *          name="id",
     *          description="Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="hash",
     *          description="Hash",
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
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    /**
     * @param EmailVerificationRequest $request
     * @return JsonResponse
     */

    public function verify(EmailVerificationRequest $request): \Illuminate\Http\JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['error'=>'Email already verified'],403);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }
        return response()->json(['message'=>'Email has been verified'],200);
    }

}
