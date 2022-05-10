<?php

namespace App\Models;

use App\Products;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductView extends Model
{
    use HasFactory;
    protected $table = 'product_views';
    protected $fillable = ['product_id','titleslug','url','session_id','user_id','ip','agent'];
    public function productView()
    {
        return $this->belongsTo(Products::class);
    }


}
