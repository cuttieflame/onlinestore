<?php

namespace App\DataTransferObjects;

use Carbon\Carbon;
use Spatie\DataTransferObject\DataTransferObject;

class ObjectData extends DataTransferObject

{

    public static function generateCarbonObject(?string $date): ?Carbon

    {

        if (!$date) {

            return null;

        }

        return \Illuminate\Support\Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $date);

    }

}