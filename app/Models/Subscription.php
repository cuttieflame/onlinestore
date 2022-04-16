<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $table ='subscriptions';
    public function subscriptionItems() {
        return $this->hasOne(SubscriptionItem::class,'id','stripe_id')->select(['id','stripe_id','quantity']);
    }
}
