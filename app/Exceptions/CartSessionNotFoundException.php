<?php

namespace App\Exceptions;

use Exception;

class CartSessionNotFoundException extends Exception
{
    public function render()
    {
        return response()->json([
            'status'   => 'error',
            'message'  => 'User is already a member',
        ], 403);
    }
}
