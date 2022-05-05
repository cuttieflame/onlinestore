<?php
declare(strict_types=1);
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Favorite;
use App\Models\User;
use App\Products;
use App\Services\Test\DemoOne;
use App\Services\Test\DemoOneInterface;

class TestController extends Controller
{
    public function index()
    {
        dd(User::inRandomOrder()->limit(1)->first());
    }
}
