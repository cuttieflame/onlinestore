<?php

namespace App\Services;
use Image;
final class ImageService
{
    public static function InvertionImage($file) {
        $input['file'] = time().'.'.$file->getClientOriginalExtension();
        $imgFile = Image::make($file->getRealPath());
        $imgFile
            ->text('© 2021 CUTTIEFLAME COMPANY', 120, 100, function($font) {
                $font->size(140);
                $font->color('#ffffff');
                $font->align('center');
                $font->valign('bottom');
                $font->angle(90);
            })
            ->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('storage/images').'/'.$input['file']);
        return $input['file'];
    }
    public static function InvertionImages($files) {
        $arr = [];
        foreach($files as $elem) {
            $input['file'] = time().'.'.$elem->getClientOriginalExtension();
            $imgFile = Image::make($elem->getRealPath());
            $imgFile
                ->text('© 2021 CUTTIEFLAME COMPANY', 120, 100, function($font) {
                    $font->size(140);
                    $font->color('#ffffff');
                    $font->align('center');
                    $font->valign('bottom');
                    $font->angle(90);
                })
                ->resize(500, 500, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path('storage/images').'/'.$input['file']);
            $arr[] = $input['file'];
        }
        return $arr;

    }
}