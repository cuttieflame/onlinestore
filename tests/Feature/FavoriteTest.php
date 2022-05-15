<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Favorite;
use App\Models\ProductPrice;
use App\Models\User;
use App\Products;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    public function testFavoriteIsShowSuccessfully()
    {
        $user = User::inRandomOrder()->first();
        $product = Products::inRandomOrder()->first();
        Favorite::factory()->count(1)->create([
            'user_id'=>$user->id,
            'session_id'=>session()->getId(),
            'product_id'=>$product->id
        ]);
        $this->actingAs($user);
        $this->json('get', "api/v1/favorite")
            ->assertStatus(200);
    }

    public function testFavoriteIsAddProductSuccessfully()
    {
        $this->faker = Faker::create();

        $product_id = Products::insertGetId([
            'entity_id' => 1,
            'attribute_set_id' => 1
        ]);
        ProductPrice::create([
            'id'=>$product_id,
            'price'=>$this->faker->randomFloat($nbMaxDecimals = 2, $min = 1000, $max = 100000),
            'discount'=>$this->faker->numberBetween($min = 1, $max = 99),
            'old_price'=>$this->faker->randomFloat($nbMaxDecimals = 2, $min = 1000, $max = 100000),
        ]);
        $this->json('post', "api/v1/favorite/add/$product_id")
            ->assertStatus(201);
    }

    public function testFavoriteIsClearSuccessfully()
    {
        $user = User::inRandomOrder()->first();
        $product = Products::inRandomOrder()->first();
        Favorite::factory()->count(1)->create([
            'user_id'=>$user->id,
            'session_id'=>session()->getId(),
            'product_id'=>$product->id
        ]);

        $this->actingAs($user);

        $this->json('post', "api/v1/favorite/clear")
            ->assertStatus(200);
    }
    public function testFavoriteIsDeleteProductSuccessfully()
    {

        $user = User::inRandomOrder()->first();
        $product = Products::inRandomOrder()->first();
        Favorite::factory()->count(1)->create([
            'user_id'=>$user->id,
            'session_id'=>session()->getId(),
            'product_id'=>$product->id
        ]);

        $this->actingAs($user);
        $this->json('delete', "api/v1/favorite/delete/$product->id")
            ->assertStatus(200);
    }
}
