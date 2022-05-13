<?php
declare(strict_types=1);
namespace App\Http\Controllers\API;

use App\Exceptions\CartSessionNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ProductPrice;
use App\Models\User;
use App\Products;
use App\Services\Stripe\IStripeManager;
use App\Services\Test\DemoOneInterface;
use App\Services\User\UserIndexService;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use ReflectionClass;

class TestController extends Controller
{
    public function index()
    {
        $this->faker = Faker::create();

        $user = User::create([
            'name'=>$this->faker->name(),
            'email'=>$this->faker->email,
            'password'=>'12345'
        ]);
        DB::table('products')->insertGetId([
            'entity_id' => 1,
            'attribute_set_id' => 1,
            'user_id'=>$user->id,
        ]);


    }
}
