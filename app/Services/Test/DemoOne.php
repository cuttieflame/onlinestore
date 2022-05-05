<?php

namespace App\Services\Test;

use App\Contracts\CustomServiceInterface;

class DemoOne implements DemoOneInterface
{
    public  function doSomethingUseful()
    {
        return 'Output from DemoOne';
    }
}