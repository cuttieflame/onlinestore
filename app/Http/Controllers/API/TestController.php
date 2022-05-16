<?php
declare(strict_types=1);
namespace App\Http\Controllers\API;

use App\Contracts\CustomServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Favorite;
use App\Models\User;
use App\Products;
use App\Services\Order\IOrderManager;
use App\Services\Test\DemoOne;
use App\Services\Test\DemoTwo;
use App\Services\Test\DemoTwoInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ReflectionClass;

class TestController extends Controller
{
//    private $demoTwo;
//    public function __construct(DemoTwoInterface $demoTwo)
//    {
//        $this->demoTwo = $demoTwo;
//    }

    public function index()
    {
        $a = [1,2,3,4,5];
        $b = ['a','b','c','d'];
      DemoTwo::test();
    }

}
