<?php

namespace App\Services\MailServices;

class MailManager
{
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function register($email, $content)
    {
        $this->mailer->mail($email, $content);
    }
}