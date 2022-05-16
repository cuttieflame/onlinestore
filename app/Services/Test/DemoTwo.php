<?php

namespace App\Services\Test;


class DemoTwo
{
    public static function who() {
        echo '123';
    }
    public static function test() {
        static::who();
    }
}