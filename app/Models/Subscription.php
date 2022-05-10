<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table ='subscriptions';
    protected $fillable = ['user_id','name','stripe_id','stripe_price','stripe_status','quantity',
    'trial_ends_at','ends_at','created_at','updated_at','invoice_id'];
    public function subscriptionItems() {
        return $this->hasOne(SubscriptionItem::class,'id','stripe_id')->select(['id','stripe_id','quantity']);
    }
}
