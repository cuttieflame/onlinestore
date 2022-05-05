<?php

namespace App\Services\Test;

use App\Contracts\CustomServiceInterface;

class DemoTwo implements CustomServiceInterface
{
public function doSomethingUseful()
{
    return 'Output from DemoTwo';
}
}