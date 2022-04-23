<?php

namespace App\DataTransferObjects;
use Illuminate\Http\Request;
use App\DataTransferObjects\ObjectData;

final class UserData extends ObjectData
{
    public string $name;
    public string $email;
    public string $first_name;
    public string $last_name;
    public string $organization;
    public string $location;
    public string $phone;
    public string $birthday;

    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */

    public static function fromRequest(

        Request $request

    ): UserData {

        return new UserData(

            name: $request->get('name'),
            email: $request->get('email'),
            first_name: $request->get('first_name'),
            last_name: $request->get('last_name'),
            organization: $request->get('organization'),
            location: $request->get('location'),
            phone: $request->get('phone'),
            birthday: $request->get('birthday'),

        );
    }
}