<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\ProductPrice;
use App\Models\User;
use App\Products;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 *
 */
class CartTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    //КОРЗИНА НА СЕССИЯХ,ПОЭТОМУ ВЕЗДЕ ОШИБКА 403 БУДЕТ,ТАК КАК НА БЭКЕ СЕССИИ НЕ ПОСТОЯННЫЕ КАК НА ФРОНТЕ

    /**
     * @return void
     */
    public function testCartIsShowSuccessfully()
    {
        $user = User::inRandomOrder()->first();
        $product = Products::inRandomOrder()->first();
        Cart::factory()->count(1)->create([
            'user_id'=>$user->id,
            'session_id'=>session()->getId(),
            'product_id'=>$product->id
        ]);

        $this->actingAs($user);
        $this->json('get', "api/v1/cart")
            ->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testCartIsAddProductSuccessfully()
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
        $this->json('post', "api/v1/cart/add/$product_id")
            ->assertStatus(201);
    }

    /**
     * @return void
     */
    public function testCartIsChangeQuantityProductSuccessfully()
    {
        $cart = Cart::inRandomOrder()->first();
        $this->json('put', "api/v1/cart/quantity?cart_id=$cart->id&quantity=plus&value=0")
            ->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testCartIsClearSuccessfully()
    {
        $user = User::inRandomOrder()->first();
        $product = Products::inRandomOrder()->first();
        Cart::factory()->count(1)->create([
            'user_id'=>$user->id,
            'session_id'=>session()->getId(),
            'product_id'=>$product->id
        ]);

        $this->actingAs($user);
        $this->json('post', "api/v1/cart/clear")
            ->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testCartIsDeleteProductSuccessfully()
    {
        $cart = Cart::inRandomOrder(1)->first();

        $user = User::inRandomOrder()->first();
        $product = Products::inRandomOrder()->first();
        Cart::factory()->count(1)->create([
            'user_id'=>$user->id,
            'session_id'=>session()->getId(),
            'product_id'=>$product->id
        ]);

        $this->actingAs($user);
        $this->json('delete', "api/v1/cart/delete/$product->id")
            ->assertStatus(200);
    }
}
