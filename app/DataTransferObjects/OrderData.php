<?php

namespace App\DataTransferObjects;
use App\DataTransferObjects\ObjectData;
use Illuminate\Http\Request;

final class OrderData extends ObjectData
{
    public string $give_code;
    public string $description;



    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public static function fromRequest(

        Request $request

    ): OrderData {

        return new OrderData(

            give_code : $request->get('give_code'),
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