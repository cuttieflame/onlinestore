<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionItem extends Model
{
    use HasFactory;
    protected $table ='subscription_items';
    protected $fillable = ['stripe_id','stripe_product','stripe_price','quantity'];

}
