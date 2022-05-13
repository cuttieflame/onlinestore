<?php

namespace App\Http\Controllers\API;

use App\Contracts\SocialInterface;
use App\Http\Controllers\Controller;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Redirect;

/**
 *
 */
class SocialController extends Controller implements SocialInterface
{
    /**
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function index(): \Symfony\Component\HttpFoundation\RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        return Socialite::driver('vkontakte')->redirect();
    }

    /**
     * @return void
     */
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
