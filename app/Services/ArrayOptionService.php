<?php

namespace App\Services;

final class ArrayOptionService
{
    public static function makeOptionArray($request) {
        $options = [
            "name"=>$request->name . '4',
            "content"=>$request->input('content') . '5',
            "main_image"=>$request->main_image . '6',
            "tags"=>$request->input('tags') . '7',
        ];
        return $options;
    }
}