<?php

namespace App\Services\File;

class FileImage
{
    public string $name;
    public string $size;

    public function __construct($name,$size)
    {
        $this->name = $name;
        $this->size = $size;
    }
}