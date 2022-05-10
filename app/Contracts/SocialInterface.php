<?php

namespace App\Contracts;

interface SocialInterface
{
    public function index();
    public function callback();
}