<?php

namespace App\Repositories;
use App\Http\Resources\UserCollection;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
//    public function getByUser($id)
//    {
//        return new UserCollection(User::where('id'. $id)->with('account_details')->get());
//    }
}
