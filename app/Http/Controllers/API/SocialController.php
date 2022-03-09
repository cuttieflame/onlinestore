<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Redirect;

class SocialController extends Controller
{
    public function index()
    {
        return Socialite::driver('vkontakte')->redirect();
    }
    public function callback()
    {
        $user = Socialite::driver('vkontakte')->user();
        $email = $user->getEmail();
        $name = $user->getName();
        $password = \Hash::make('12345678');
        $u = User::where('email',$email)->first();
        $data =  ['name'=>$name,'email'=>$email,'password'=>$password];
        if ($u) {
            return $u->fill(['name'=>$name]);
        }
        User::create($data);
    }
}
