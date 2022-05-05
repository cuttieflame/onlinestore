<?php

namespace Tests\Feature;

use App\Models\User;
use App\Products;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserIsShowSuccessfully()
    {
        $this->faker = Faker::create();
        $user = User::factory()->count(1)->create()->first();
        $this->actingAs($user);
        $this->json('get', "api/v1/user")
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'first_name',
                        'last_name',
                        'organization',
                        'phone',
                        'birthday',
                        'user_image',
                        'role_user' => [],
                        'permission_user'=>[]
                    ]
                ]
            );
    }
    public function testUserIsDeleteSuccessfully()
    {
        $this->faker = Faker::create();
        $user = User::factory()->count(1)->create()->first();
        $this->json('delete', "api/v1/user/destroy/$user->id")
            ->assertStatus(200);
    }
    public function testUserIsUpdateSuccessfully()
    {
        $this->faker = Faker::create();
        $user = User::factory()->count(1)->create()->first();
        $payload = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'first_name'=>$this->faker->firstName,
            'last_name'=>$this->faker->lastName,
            'organization'=>$this->faker->company,
            'location'=>$this->faker->city,
            'phone'=>$this->faker->phoneNumber,
            'birthday'=>$this->faker->date($format = 'Y-m-d', $max = 'now')
        ];
        $this->json('put', "api/v1/user/update/$user->id", $payload)
            ->assertStatus(200);
    }

    public function testUserIsUpdateImageSuccessfully()
    {
//        $this->faker = Faker::create();
//        $payload = [
//            'file' => new FileImage($this->faker->name,$this->faker->file($sourceDir = false, $targetDir = false, false) ),
//        ];
//        $this->json('post', 'api/v1/user/update/3', $payload['file'])
//            ->assertStatus(200);
    }
    public function testUserIsResetPasswordSuccessfully() {
//        $user = User::inRandomOrder()->limit(1)->first();
//        $payload = [
//            'password' =>$user->password,
//            'old_password' => $user->password,
//            'confirm_password' => $user->password,
//        ];
//        $this->json('post', 'api/v1/password/password-reset',$payload)
//            ->assertStatus(200);
    }
}
