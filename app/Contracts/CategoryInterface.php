<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface CategoryInterface
{
    public function index(Request $request,$id);
}