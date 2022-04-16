<?php

namespace App\Http\Controllers\API;
use App\Models\Category;
use App\Models\Product;
use App\Products;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    private $productRepository;
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    public function index(Request $request)
    {
        $query = $request->search;
        $products = Products::with(['attributesoptions' => function ($q) {
            $q->select(['attribute_id','value','product_id'])->orderBy('attribute_id');
        }])
            ->with('productprice')
            ->whereHas('attributesoptions',function($q) use ($query) {
                $q->where([
                    ['attribute_id', '=', 4],
                    ['value', 'like', "%{$query}%"],
                ])->orWhere([
                    ['attribute_id', '=', 5],
                    ['value', 'like', "%{$query}%"],
                ]);
            })
            ->paginate(10);
        $arr = [];
        foreach($products as $product) {
            foreach($product->attributesoptions as $elem) {
               if($elem->attribute_id == 10) {
                   $arr[] = $elem->value;
               }
            }
        }
        $tmp = array_count_values($arr);
        $max = max($tmp);
        $result = array_search($max,$tmp);
        $categories = Category::select(['id','title','parent_id'])->with(['subcategory'])->where('id',$result)->first();;
        $subbrands = \Cache::remember('subbrands', '14400', function () {
            return \DB::table('brands')->select(['id','title','category_id','categories'])->get();;
        });
        $bss = [];
        foreach($subbrands as $elems) {
            foreach( explode(",",preg_replace('/\[|\]/','',$elems->categories)) as $elem) {
                if($elem == $result) {
                    $bss[] = $elems->title.$elems->category_id;
                }
            }
        }
        return response()->json(['products'=>$products]);
    }
}
