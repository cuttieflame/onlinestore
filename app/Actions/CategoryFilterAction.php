<?php

namespace App\Actions;
use Illuminate\Http\Request;
class CategoryFilterAction
{
    public function handle($builder,Request $request) {

        if ($request->has('o') or $request->has('s') && in_array($request->o, ['pr-in', 'pr-de'])) {
            $products = $builder->get();
            $count = $products->count();
            if ($count > 1) {
                $max = $builder->get()->max('price'); // цена самого дорогого товара
                $min = $builder->get()->min('price'); // цена самого дешевого товара

                $avg = ($min + $max) * 0.5;
                if ($request->o == 'pr-in') {
                    $builder->where('price', '<=', $avg)->orderBy('price');
                }
                if($request->o == 'pr-de') {
                    $builder->where('price', '>=', $avg)->orderBy('price','desc');
                }
                if($request->s == 'new') {
                    $builder->orderBy('id');
                }
                if($request->s == 'old') {
                    $builder->orderBy('id','desc');
                }

            }
        }
    }
}
