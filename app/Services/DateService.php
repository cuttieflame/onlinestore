<?php

namespace App\Services;


use DateTimeImmutable;

final class DateService
{
    public static function numberToDate($data) {
        $now = new DateTimeImmutable();
        $date = $now->setTimestamp($data);
        return $date->format('Y-m-d H:i:s');
    }
}