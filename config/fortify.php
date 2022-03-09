<?php

use App\Providers\RouteServiceProvider;
use Laravel\Fortify\Features;

return [



    'guard' => 'web',



    'passwords' => 'users',



    'username' => 'email',

    'email' => 'email',


    'home' => env('SPA_URL').'/dashboard',



    'prefix' => '',

    'domain' => null,


    'middleware' => ['web'],



    'limiters' => [
        'login' => 'login',
        'two-factor' => 'two-factor',
    ],



    'views' => false,



    'features' => [
        Features::registration(),
        Features::resetPasswords(),
        Features::emailVerification(),
        Features::updateProfileInformation(),
        Features::updatePasswords(),
        Features::twoFactorAuthentication([
            'confirmPassword' => true,
        ]),
    ],

];
