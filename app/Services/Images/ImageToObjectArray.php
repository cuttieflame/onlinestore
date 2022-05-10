<?php

namespace App\Services\Images;

final class ImageToObjectArray
{
    public $image;
    public $id;

    public function __construct($image, $id)
    {
        $this->image = $image;
        $this->id = $id;
    }

    public static function make(array $arr) {
        $users = [];
        $a = 0;
        foreach($arr as $elem) {
            $a = $a + 1;
            $user = new ImageToObjectArray($elem,$a);
            $users[] = $user;
        }
        return $users;
    }


}
