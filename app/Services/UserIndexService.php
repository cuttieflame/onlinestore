<?php

namespace App\Services;

class UserIndexService
{
    public static function getUser($id) {
        $user = \DB::table('users')
            ->where('users.id',$id)
            ->join('account_details', 'account_details.id', '=', 'users.id')
            ->leftJoin('users_roles', 'users_roles.user_id', '=', 'users.id')
            ->leftJoin('users_permissions', 'users_permissions.user_id', '=', 'users.id')
            ->first();
        return $user;
    }
}
