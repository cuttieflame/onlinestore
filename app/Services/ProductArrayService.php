<?php

namespace App\Services;

final class ProductArrayService
{
    public static function makeBrandArray($subbrands,$a) {
        $bss = [];
        foreach($subbrands as $elems) {
            foreach( explode(",",preg_replace('/\[|\]/','',$elems->categories)) as $elem) {
                if($elem == $a) {
                    $bss[] = $elems->name.$elems->category_id;
                }
            }
        }
        return $bss;
    }
    public static function makeRelatedCategories($builder) {
        $arrctgr = [];$arrmax = [];
        $a = $builder->get();
        foreach($a->pluck('productprice') as $elems) {
            $arrmax[] = $elems->price;
        }
        foreach($a->pluck('productcategories') as $elems) {
            foreach($elems as $elem) {
                if($elem->childrenCategories) {
                    $arrctgr[] = $elem->id;
                }
                    $arrctgr[] = $elem->id;
            }
        }
        return [$arrmax,$arrctgr];
    }
}
