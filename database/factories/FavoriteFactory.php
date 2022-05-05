<?php

namespace Database\Factories;

use App\Models\Favorite;
use App\Models\User;
use App\Products;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FavoriteFactory extends Factory
{
    protected $model = Favorite::class;
    public function definition()
    {
        return [
            'session_id'=>session()->getId(),
            'product_id'=>Products::inRandomOrder()->select(['id'])->first()->id,
            'user_id'=>User::inRandomOrder()->select(['id'])->first()->id,
        ];
    }
}
