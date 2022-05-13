<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class SubscriptionItem extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table ='subscription_items';
    /**
     * @var string[]
     */
    protected $fillable = ['stripe_id','stripe_product','stripe_price','quantity'];

}
