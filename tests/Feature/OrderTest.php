<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{

    public function testOrderIsMakeSuccessfully()
    {
        $this->faker = Faker::create();
        $user = User::inRandomOrder()->first();
        $payload = [
            'give_code' => substr($this->faker->uuid,0,8),
            'description'=>$this->faker->catchPhrase,
        ];
        $this->json('post', "api/v1/order/make/$user->id",$payload)
            ->assertStatus(403);
    }
}
