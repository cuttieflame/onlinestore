<?php

namespace App\Contracts;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

interface VerificationInterface
{
    public function sendVerificationEmail(Request $request);
    public function verify(EmailVerificationRequest $request);
}