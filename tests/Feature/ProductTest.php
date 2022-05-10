<?php

namespace Tests\Feature;

use App\Http\Middleware\RoleMiddleware;
use App\Models\User;
use App\Products;
use App\Services\File\FileImages;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductTest extends TestCase
{
    //просмотр всех продуктов
    public function testProductIsShowSuccessfully()
    {
        $this->json('get', "api/v1/products")
            ->assertStatus(200);
    }
    //удаление продукта
    public function testProductIsDeleteSuccessfully()
    {
        $products = Products::inRandomOrder()->limit(5)->select(['id'])->get();
        $prod_delete = [];
        foreach($products as $product) {
            $prod_delete[] = $product->id;
        }
        $delete = implode(",",$prod_delete);
        $this->json('post', "api/v1/products/delete?pr=$delete")
            ->assertStatus(200);

    }
    //создание продукта
    public function testProductIsCreateSuccessfully()
    {
        $this->faker = Faker::create();
        $payload = [
            'price' => 775,
            'name'=>$this->faker->name,
            'content'=>$this->faker->text(200),
            'brand'=>"1,4,7,3",
            'category'=>"1,4,7,3",
            'tags'=>$this->faker->text(200),
            'main_image'=>"1.png",
            'images'=>["123"]
        ];
        $user = User::factory()->count(1)->create()->first();
        $user->givePermissionsTo('create-products');
        $this->actingAs($user);
        $this->json('post', "api/v1/products/create",$payload)
            ->assertStatus(200);

    }
    //загрузка фото продукта
//    public function testProductUploadImageSuccessfully()
//    {
//        $this->faker = Faker::create();
//        $product = Products::create([
//            'entity_id'=>1,
//            'attribute_set_id'=>1,
//            'user_id'=>null
//        ]);
//        $payload  = new FileImages( UploadedFile::fake()->image('file1.png', 600, 600),
//            [
//                UploadedFile::fake()->image('file2.png', 600, 600),
//                UploadedFile::fake()->image('file3.png', 600, 600),
//                UploadedFile::fake()->image('file4.png', 600, 600),
//            ]
//        );
//        $this->json('post', "api/v1/products/image/$product->id",$payload)
//        ->assertStatus(200);
//    }
    //просмотр пользовательских продуктов
    public function testProductUserShowSuccessfully()
    {
        $this->faker = Faker::create();

        $user = User::create([
            'name'=>$this->faker->name(),
            'email'=>$this->faker->email,
            'password'=>'12345'
        ]);
        Products::factory()->count(5)->create([
            'user_id'=>$user->id
        ]);

        $this->json('get', "api/v1/product/$user->id?t=dsh")
            ->assertStatus(200);
    }
    public function testBrandAndCategoriesShowSuccessfully()
    {

        $this->json('get', "api/v1/brands_categories")
            ->assertStatus(200);
    }
    //просмотр продукта
}
