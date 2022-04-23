<?php

namespace App\Http\Controllers\API;

use App\DataTransferObjects\UserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserAccountRequest;
use App\Http\Resources\UserCollection;
use App\Models\User;
use App\Services\ImageService;
use App\Services\UserIndexService;
use Hash;
use Illuminate\Http\Request;
use Image;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $id = auth()->id();
        $user = UserIndexService::getUser(3);

        return response()->json(['data'=>$user]);
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        //
    }
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }
    public function update(UserAccountRequest $request, $id)
    {
            $validated = UserData::fromRequest($request);
            $user = User::findOrFail($id);
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
    }
    public function updateImage(Request $request,$id) {
        $file = $request->file('file');
        $a = ImageService::InvertionImage($file);
        $user = User::find($id);
        $user->account_details->user_image = $a;
        $user->account_details->save();
        return response()->json(['status'=>'Аватарка обновлена']);
    }
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
    }
    public function resetPassword(Request $request) {
        $id = auth()->id();
        $user = User::find($id);
        if(Hash::make($request->old_password) == Hash::make($user->password) and $request->old_password == $request->confirm_password) {
            $user->password = Hash::make($request->password);
            $user->save();
        }
    }
}
