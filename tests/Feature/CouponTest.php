<?php

namespace Tests\Feature;

use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CouponTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCouponIsShowSuccessfully()
    {
        $this->json('get', "api/v1/coupons")
            ->assertStatus(200);
    }
    public function testCouponIsMakeSuccessfully()
    {
        $currencies = [1,2,3];
        $only_once = [0,1];
        $is_absolute = [0,1];
        $this->faker = Faker::create();
        $payload = [
            'code' => substr($this->faker->uuid,0,8),
            'nominal_value'=>$this->faker->numberBetween($min = 1, $max = 99),
            'currency'=>array_rand($currencies, 1),
            'is_only_once'=>array_rand($only_once, 1),
            'is_absolute'=>array_rand($is_absolute, 1),
            'expired_at'=>$this->faker->date($format = 'Y-m-d', $max = 'now'),
            'description'=>$this->faker->catchPhrase,
        ];
        $this->json('post', "api/v1/coupons/make",$payload)
            ->assertStatus(200);
    }
}
