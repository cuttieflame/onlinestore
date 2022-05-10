<?php

namespace App\Services\Arr;

interface ArrayServiceInterface
{
    public static function makeOptionArray($validated);
    public static function makeBrandArray($subbrands,$a);
    public static function makeRelatedCategories($builder);
}