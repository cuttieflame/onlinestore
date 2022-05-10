<?php

namespace App\Services\Arr;

class ArrayService implements ArrayServiceInterface
{
    public static function makeOptionArray($validated) {
        $options = [
            "name"=>$validated->name . '4',
            "content"=>$validated->content . '5',
            "main_image"=>$validated->main_image . '6',
            "tags"=>$validated->tags . '7',
        ];
        return $options;
    }
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