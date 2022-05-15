<?php
declare(strict_types=1);
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Favorite;
use App\Models\User;
use App\Products;
use App\Services\Order\IOrderManager;
use App\Services\Test\DemoOne;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ReflectionClass;

class TestController extends Controller
{
    public function index()
    {
        $a = new ReflectionClass(DemoOne::class);
        dd($a->getAttributes());
    }

}
