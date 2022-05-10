<?php
declare(strict_types=1);
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ProductPrice;
use App\Models\User;
use App\Products;
use App\Services\User\UserIndexService;
use Faker\Factory as Faker;

class TestController extends Controller
{
    public function index()
    {
        $product = Products::where('id', 550)
            ->with(['productprice:id,price'])
            ->firstOrFail();
        dd($product);
    }
}
