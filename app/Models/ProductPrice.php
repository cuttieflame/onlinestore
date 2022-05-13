<?php

namespace App\Models;

use App\Services\Currency\CurrencyConvertion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 */
class ProductPrice extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'product_prices';
    /**
     * @var string[]
     */
    protected $fillable = ['id','price','discount','old_price'];

    /**
     * @param $value
     * @return float|int
     */
    public function getPriceAttribute($value)
    {

        return CurrencyConvertion::convert($value);
    }

}
