<?php

namespace App\Services\Images;

interface ImageServiceInterface
{
    public static function InvertionImage($file);
    public static function InvertionImages($files);
}