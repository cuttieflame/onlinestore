<?php

namespace App\Services;

final class ArrayOptionService
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
}