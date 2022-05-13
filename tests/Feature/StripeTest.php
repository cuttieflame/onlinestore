<?php

namespace Tests\Feature;

use App\Models\User;
use App\Products;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StripeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStripeIsShowSuccessfully()
    {
        $user = User::inRandomOrder()->limit(1)->first();
        $this->actingAs($user);
        $this->json('get', "api/v1/stripe")
            ->assertStatus(200);
    }
    public function testStripeIsShowAllProductsSuccessfully()
    {
        $this->json('get', "api/v1/stripe/allproducts")
            ->assertStatus(200);
    }
    public function testStripeIsAddProductSuccessfully()
    {
        $user = User::inRandomOrder()->limit(1)->first();
        $this->actingAs($user);
        $this->json('post', "api/v1/stripe/add/product")
            ->assertStatus(201);
    }
    public function testStripeIsAddCustomerSuccessfully()
    {
        $user = User::inRandomOrder()->limit(1)->first();
        $this->json('post', "api/v1/stripe/add/customer/$user->id")
            ->assertStatus(201);
    }
    public function testStripeIsShowUserProductsSuccessfully()
    {
        $user = User::inRandomOrder()->limit(1)->first();
        $this->json('get', "api/v1/stripe/products/$user->id")
            ->assertStatus(200);
    }
}
