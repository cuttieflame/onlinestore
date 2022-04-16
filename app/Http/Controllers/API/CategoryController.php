<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Products;
use App\Services\ProductArrayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{

    public function index(Request $request,$id = 0): \Illuminate\Http\JsonResponse
    {
        if($id != 0) {
            $a = preg_replace('/\D+/', '', $id);
            $subcategories = \Cache::remember('subcategories', '14400', function () use ($a) {
                return Category::select(['id'])->where('id',$a)->first();
            });
            $subbrands = \Cache::remember('subbrands', '14400', function () {
                return \DB::table('brands')->select(['id','title','category_id','categories'])->get();
            });
            $bss = ProductArrayService::makeBrandArray($subbrands,$a);
            $builder = Products::withAttributeOptions(['pr-price','pr-ctgrs'])
                ->join('product_categories', 'product_categories.product_id', '=', 'products.id')
                ->where('product_categories.category_id',$a);
            $arrmax = [];

            $ctgr = ProductArrayService::makeRelatedCategories($builder);
            $rld_itms = array_unique($ctgr[1]);
            $related_items = Category::whereIn('id',$rld_itms)->where('parent_id','=',null)
                ->with('childrencategories')
                ->get();

//            foreach($arrctgr as $elem) {
//                $firstIndex = stripos($elem, '+');
//                $id = substr($elem,0,$firstIndex);
//                $parent_id = substr($elem,$firstIndex + 1,strlen($elem));
//            }

            $max = max($ctgr[0]);
            $min = min($ctgr[0]);

            $avg = ($min + $max) * 0.5;

            if ($request->has('br') or $request->has('pr') or $request->o !== "undefined" and $request->o !== null or $request->has('s') or in_array($request->o, ['pr-in', 'pr-de'])) {

                if($request->br) {
                    $builder->whereHas('productcategories',function($query) use ($request) {
                        $query->whereIn('category_id',explode(",", $request->br));
                    });
                }
                if($request->pr and $request->max) {
                    $builder->join('product_prices', 'product_prices.id', '=', 'products.id')
                        ->where([
                            ['product_prices.price', '>', $request->pr],
                            ['product_prices.price', '<', $request->max],
                        ]);
                }
                if ($request->o == 'pr-in') {
                    $builder->orderPrices(0);
                }
                if($request->o == 'pr-de') {
                    $builder->orderPrices(1);
                }
                if($request->s == 'new') {
                    $builder->orderBy('id');
                }
                if($request->s == 'old') {
                    $builder->orderBy('id','desc');
                }
            }
            $products = $builder->limit(200)->paginate(10);
        }
        if($id == 0) {
            $query = $request->search;
            $builder = Products::withAttributeOptions(['pr-price','pr-ctgrs'])
                ->whereHas('attributesoptions',function($q) use ($query) {
                    $q->where([
                        ['attribute_id', '=', 4],
                        ['value', 'like', "%{$query}%"],
                    ])->orWhere([
                        ['attribute_id', '=', 5],
                        ['value', 'like', "%{$query}%"],
                    ]);
                });
            $ctgr = ProductArrayService::makeRelatedCategories($builder);
            $max = max($ctgr[0]);
            $min = min($ctgr[0]);

            $avg = ($min + $max) * 0.5;
            if ($request->has('br') or $request->has('pr') or $request->o !== "undefined" and $request->o !== null or $request->has('s') or in_array($request->o, ['pr-in', 'pr-de'])) {

                if($request->br) {
                    $builder->whereHas('productcategories',function($query) use ($request) {
                        $query->whereIn('category_id',explode(",", $request->br));
                    });
                }
                if($request->pr and $request->max) {
                    $builder->join('product_prices', 'product_prices.id', '=', 'products.id')
                        ->where([
                            ['product_prices.price', '>', $request->pr],
                            ['product_prices.price', '<', $request->max],
                        ]);
                }
                if ($request->o == 'pr-in') {
                    $builder->join('product_prices', 'product_prices.id', '=', 'products.id')
                        ->orderBy('product_prices.price','ASC');
                }
                if($request->o == 'pr-de') {
                    $builder->join('product_prices', 'product_prices.id', '=', 'products.id')
                        ->orderBy('product_prices.price','DESC');
                }
                if($request->s == 'new') {
                    $builder->orderBy('id');
                }
                if($request->s == 'old') {
                    $builder->orderBy('id','desc');
                }
            }

            $subbrands = \DB::table('brands')->select(['id','title','category_id','categories'])->get();
            $bss = ProductArrayService::makeBrandArray($subbrands,1);
            $related_items = \DB::table('categories')->whereIn('id',array_unique($ctgr[1]))->get();
            $products = $builder->limit(200)->paginate(10);

        }
        return response()->json(['products'=>$products,'related_items'=>$related_items,'brands'=>$bss]);
    }

}
