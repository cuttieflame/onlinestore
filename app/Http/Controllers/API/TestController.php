<?php
declare(strict_types=1);
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ProductPrice;
use App\Models\User;
use App\Products;
use App\Services\Stripe\IStripeManager;
use App\Services\Test\DemoOneInterface;
use App\Services\User\UserIndexService;
use Faker\Factory as Faker;
use ReflectionClass;

class TestController extends Controller
{
    public function index()
    {

    }
}
