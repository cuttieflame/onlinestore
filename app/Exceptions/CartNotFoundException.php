<?php

namespace App\Exceptions;

use Exception;

class CartNotFoundException extends Exception
{
    public function report()
    {
        \Log::debug('Carts not found');
    }
}
