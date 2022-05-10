<?php

namespace App\Services\User;

use App\Models\User;

class UserIndexService
{
    public static function getUser($id) {
        $user = User::where('id',$id)
            ->with(['account_details'])
            ->with(['role_user' => function ($q) {
                $q->with('roles');
            }])
            ->with(['permission_user' => function ($q) {
                $q->with('permission');
            }])
            ->firstOrFail();
        return $user;
    }
}
