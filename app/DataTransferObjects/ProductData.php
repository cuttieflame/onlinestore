<?php

namespace App\DataTransferObjects;
use App\DataTransferObjects\ObjectData;
use Illuminate\Http\Request;
final class ProductData extends ObjectData
{
    public string $name;
    public string $content;
    public int $price;
    public string $brand;
    public string $category;
    public string $tags;
    public string $main_image;
    public array $images;


    /**
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public static function fromRequest(

        Request $request

    ): ProductData {

        return new ProductData(

            name: $request->get('name'),
            content: $request->get('content'),
            price: $request->get('price'),
            brand: $request->get('brand'),
            category: $request->get('category'),
            tags: $request->get('tags'),
            main_image: $request->get('main_image'),
            images: $request->get('images'),

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