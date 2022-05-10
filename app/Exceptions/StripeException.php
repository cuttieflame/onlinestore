<?php

namespace App\Exceptions;

use Exception;

class StripeException extends Exception
{
    public function render($request)
    {
        \Log::debug('Stripe not found');
    }
}
