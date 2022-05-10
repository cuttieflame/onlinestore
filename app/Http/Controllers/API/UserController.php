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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller implements UserInterface
{
    public function index(Request $request)
    {
        $user = UserIndexService::getUser(155);
        return response()->json(['data'=>new UserResource($user)])
        ->setStatusCode(Response::HTTP_OK, Response::$statusTexts[Response::HTTP_OK]);
    }
    public function update(UserAccountRequest $request,int $id)
    {
            $validated = UserData::fromRequest($request);
            try {
                $user = User::findOrFail($id);
            }
            catch(ModelNotFoundException $exception) {
                return response()->json(['error'=>'Coupon not valid']);
            }
            $user->name = $validated->name;
            $user->email = $validated->email;
            $user->account_details->first_name = $validated->first_name;
            $user->account_details->last_name = $validated->last_name;
            $user->account_details->organization = $validated->organization;
            $user->account_details->location = $validated->location;
            $user->account_details->phone = $validated->phone;
            $user->account_details->birthday = $validated->birthday;
            $user->save();
            $user->account_details->save();
            return response()->json(['status'=>'User updated'],200);
    }
    public function updateImage(UpdateImageRequest $request,$id) {
        $file = $request->file('file');
        $a = ImageService::InvertionImage($file);
        $user = User::find($id);
        UpdateImage::dispatch($user,$a)->delay(now()->addMinutes(5));
        return response()->json(['status'=>'Аватарка обновлена'],200);
    }
    public function destroy(int $id)
    {
        try {
            $user = User::findOrFail($id);
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['error'=>'Нет такого пользователя'],400);
        }
        $user->delete();
        return response()->json(['status'=>'Учетная запись успешно удалена'],200);
    }
    public function resetPassword(ResetPasswordRequest $request) {
        try {
            $user = User::findOrFail(auth()->id());
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['error'=>'Нет такого пользователя'],400);
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
