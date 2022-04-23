<?php

namespace App\Http\Controllers\API;

use App\Contracts\Factory\StdoutLoggerFactory;
use App\Http\Controllers\Controller;
use App\Services\Test\StdoutLogger;

class TestController extends Controller
{
    public function index() {
        $loggerFactory = new StdoutLoggerFactory();
        $logger = $loggerFactory->createLogger('a');
        dd($logger);

    }

}
