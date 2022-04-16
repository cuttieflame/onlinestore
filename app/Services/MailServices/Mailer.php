<?php

namespace App\Services\MailServices;
use Mail;
use App\Mail\MailAfterRegistration;

class Mailer
{
    public function mail($recipient, $content)
    {
        Mail::to($recipient)->send(new MailAfterRegistration($content));

    }
}