<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Favorite;
use App\Models\ProductPrice;
use App\Products;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    public function testFavoriteIsShowSuccessfully()
    {
        $this->json('get', "api/v1/favorite")
            ->assertStatus(403);
    }
    public function tesFavoriteIsAddProductSuccessfully()
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
        $this->json('post', "api/v1/cart/favorite/$product_id")
            ->assertStatus(201);
    }
    public function testFavoriteIsClearSuccessfully()
    {
        $this->json('post', "api/v1/favorite/clear")
            ->assertStatus(403);
    }
    public function testFavoriteIsDeleteProductSuccessfully()
    {

        $favorite = Favorite::inRandomOrder()->first();

        $this->json('delete', "api/v1/cart/delete/$favorite->product_id")
            ->assertStatus(403);
    }
}
