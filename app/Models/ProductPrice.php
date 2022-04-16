<?php

namespace App\Models;

use App\Services\CurrencyConvertion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    use HasFactory;
    protected $table = 'product_prices';

    public function getPriceAttribute($value) {

        return CurrencyConvertion::convert($value);
    }
}
