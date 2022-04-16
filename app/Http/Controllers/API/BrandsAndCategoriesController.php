<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class BrandsAndCategoriesController extends Controller
{
    public function index() {
        $categories = \Cache::remember('categories', '14400', function () {
            return \DB::table('categories')->orderBy('id')->select(['id','title'])->get();
        });
        $brands = \Cache::remember('brands', '14400', function () {
            return \DB::table('brands')->orderBy('id')->select(['id','title'])->get();
        });
        $crct = ['categories'=>$categories,'brands'=>$brands];
        return response()->json(['crct'=>$crct]);
    }
}
