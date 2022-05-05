<?php

namespace App\Services\File;

class FileImages
{
    public $file;
    public $files;

    public function __construct($file,$files)
    {
        $this->file = $file;
        $this->files = $files;
    }
}