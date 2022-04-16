<?php

namespace App\Models;

use App\Products;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostView extends Model
{
    use HasFactory;
    public function productView()
    {
        return $this->belongsTo(Products::class);
    }

    public static function createViewLog($product_id) {
        $postViews= new PostView();
        $postViews->product_id = $product_id;
        $postViews->titleslug = 'slug';
        $postViews->url = request()->url();
        if(auth()->user()) {
            $postViews->session_id = request()->getSession()->getId();
        }
        else {
            $postViews->session_id = 'no session';
        }
        $postViews->user_id = auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null;
        $postViews->ip = request()->ip();
        $postViews->agent = request()->header('User-Agent');
        $postViews->save();
    }
}
