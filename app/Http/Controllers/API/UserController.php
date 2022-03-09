<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserAccountRequest;
use App\Http\Resources\UserCollection;
use App\Models\User;
use App\Services\UserIndexService;
use Hash;
use Illuminate\Http\Request;
class UserController extends Controller
{
    public function index(Request $request)
    {
        $id = auth()->id();
        $user = UserIndexService::getUser($id);
        return response()->json(['data'=>[$user]]);
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
        $validated = $request->validated();
        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->account_details->first_name = $request->input('first_name');
        $user->account_details->last_name = $request->input('last_name');
        $user->account_details->organization = $request->input('organization');
        $user->account_details->location = $request->input('location');
        $user->account_details->phone = $request->input('phone');
        $user->account_details->birthday = $request->input('birthday');
        $user->save(array_slice($validated,0,2));
        $user->account_details->save(array_slice($validated,2));
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
