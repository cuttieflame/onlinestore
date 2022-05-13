<?php

namespace App\Services\User;

interface UserServiceInterface
{
    public function getUser(int $user_id);
    public function makeCustomerItems(int $user_id,$service);
}