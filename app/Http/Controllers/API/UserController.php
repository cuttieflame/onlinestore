<?php
declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Contracts\UserInterface;
use App\DataTransferObjects\UserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Http\Requests\UserAccountRequest;
use App\Http\Resources\UserResource;
use App\Jobs\UpdateImage;
use App\Models\User;
use App\Services\Images\ImageService;
use App\Services\User\UserIndexService;
use App\Services\User\UserServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller implements UserInterface
{
    private $userService;
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *      path="/user",
     *      operationId="getUser",
     *      tags={"User"},
     *      summary="Get list of user",
     *      description="Returns list of user",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="User not found",
     *      )
     *     )
     */

    public function index(Request $request)
    {
        try {
            $user = $this->userService->getUser(auth()->user()->id);
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['status'=>'Нет такого пользователя'],403);
        }
        return response()->json(['data'=>new UserResource($user)],200)
        ->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
    }

    /**
     * @OA\Put (
     *      path="/user/update/{id}",
     *      operationId="updateUser",
     *      tags={"User"},
     *      summary="Update user",
     *      description="Update user",
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
     *          response=403,
     *          description="User not found",
     *      )
     *     )
     */

    public function update(UserAccountRequest $request,int $id)
    {
            $validated = UserData::fromRequest($request);
            try {
                $user = User::findOrFail($id);
            }
            catch(ModelNotFoundException $exception) {
                return response()->json(['error'=>'User error'],403);
            }
            $user->name = $validated->name;
            $user->email = $validated->email;
            $user->account_details->first_name = $validated->first_name;
            $user->account_details->last_name = $validated->last_name;
            $user->account_details->organization = $validated->organization;
            $user->account_details->location = $validated->location;
            $user->account_details->phone = $validated->phone;
            $user->account_details->birthday = $validated->birthday;
            $user->update();
            $user->account_details->update();
            return response()->json(['status'=>'User updated'],200);
    }

    /**
     * @OA\Post (
     *      path="/user/updateImage/{id}",
     *      operationId="updateUserImage",
     *      tags={"User"},
     *      summary="Update user image",
     *      description="Update user image",
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
     *          response=403,
     *          description="User not found",
     *      )
     *     )
     */


    public function updateImage(UpdateImageRequest $request,int $id) {
        $file = $request->file('file');
        $a = ImageService::InvertionImage($file);
        try {
            $user = User::findOrFail($id);
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['status'=>'Нет такого пользователя'],403);
        }
        UpdateImage::dispatch($user,$a)->delay(now()->addMinutes(5));
        return response()->json(['status'=>'Аватарка обновлена'],200);
    }


    /**
     * @OA\Delete  (
     *      path="/user/destroy/{id}",
     *      operationId="deleteUser",
     *      tags={"User"},
     *      summary="Delete user",
     *      description="Delete user",
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
     *          response=403,
     *          description="User not found",
     *      )
     *     )
     */

    public function destroy(int $id)
    {
        try {
            $user = User::findOrFail($id);
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['error'=>'Нет такого пользователя'],403);
        }
        $user->delete();
        return response()->json(['status'=>'Учетная запись успешно удалена'],200);
    }

    /**
     * @OA\Post   (
     *      path="/password/password-reset",
     *      operationId="UserPasswordReset",
     *      tags={"User"},
     *      summary="User password reset",
     *      description="User password reset",
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
     *          description="Error",
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="User not found",
     *      )
     *     )
     */


    public function resetPassword(ResetPasswordRequest $request) {
        try {
            $user = User::findOrFail(auth()->id());
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['error'=>'Нет такого пользователя'],403);
        }
        if(Hash::make($request->old_password) == Hash::make($user->password) and $request->old_password == $request->confirm_password) {
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json(['status'=>'Пароль успешно обновлен'],200);
        }
        else {
            return response()->json(['status'=>'Ошибка'],400);
        }
    }
}
