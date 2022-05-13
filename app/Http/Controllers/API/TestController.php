<?php
declare(strict_types=1);
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Order\IOrderManager;

class TestController extends Controller
{
    private $orderManager;
    public function __construct(IOrderManager $orderManager)
    {
        $this->orderManager = $orderManager;
    }

    public function index()
    {
       $this->orderManager->make();
    }

}
