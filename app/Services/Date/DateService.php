<?php

namespace App\Services\Date;


use DateTimeImmutable;

final class DateService implements DateInterface
{
    public static function numberToDate(string $data) {
        $now = new DateTimeImmutable();
        $date = $now->setTimestamp($data);
        return $date->format('Y-m-d H:i:s');
    }
}