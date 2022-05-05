<?php

namespace App\DataTransferObjects;
use App\DataTransferObjects\ObjectData;
use Illuminate\Http\Request;

final class CouponData extends ObjectData
{
    public string $code;
    public string $nominal_value;
    public int $currency;
    public bool $is_only_once;
    public bool $is_absolute;
    public string $expired_at;
    public string $description;



    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public static function fromRequest(

        Request $request

    ): CouponData {

        return new CouponData(

            code : $request->get('code'),
            nominal_value  : $request->get('nominal_value'),
            currency : $request->get('currency'),
            is_only_once : $request->get('is_only_once'),
            is_absolute : $request->get('is_absolute'),
            expired_at : $request->get('expired_at'),
            description : $request->get('description'),

        );
    }

//    public static function fromModel(User $user): self
//
//    {
//
//        return new static([
//
//            'id' => $user->id,
//
//            'name' => $user->name,
//
//            'email' => $user->email,
//
//            'approved' => (bool) $user->approved,
//
//            'verified' => (bool) $user->verified,
//
//            'verified_at' => self::generateCarbonObject($user->verified_at),
//
//            'created_at' => $user->created_at,
//
//            'login' => $user->login,
//
//            'operations_number' => $user->operations_number,
//
//            'budget_day_start' => $user->budget_day_start,
//
//            'primary_currency' => $user->currency,
//
//            'timezone' => $user->timezone,
//
//            'phone' => $user->phone,
//
//            'team' => $user->team,
//
//            'language' => $user->language,
//
//            'google_sync' => (bool) $user->google_sync,
//
//        ]);
//
//    }

}