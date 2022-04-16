<?php

namespace App\Services;

use Monolog\DateTimeImmutable;

final class DateService
{
    public static function numberToDate($date) {
        return date("d.m.Y", $date);
    }
}