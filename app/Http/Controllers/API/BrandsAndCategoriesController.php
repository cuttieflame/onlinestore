<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class BrandsAndCategoriesController extends Controller
{
    public function index() {
        $categories = \DB::table('categories')->select(['id','title'])->get();
        $brands = \DB::table('brands')->select(['id','title'])->get();
        $categories->merge($brands);
        return response()->json(['categories'=>$categories,'brands'=>$brands]);
    }
}
