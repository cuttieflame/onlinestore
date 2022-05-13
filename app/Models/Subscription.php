<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 *
 */
class Subscription extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * @var string
     */
    protected $table ='subscriptions';
    /**
     * @var string[]
     */
    protected $fillable = ['user_id','name','stripe_id','stripe_price','stripe_status','quantity',
    'trial_ends_at','ends_at','created_at','updated_at','invoice_id'];

    /**
     * @return HasOne
     */
    public function subscriptionItems(): HasOne
    {
        return $this->hasOne(SubscriptionItem::class,'id','stripe_id')->select(['id','stripe_id','quantity']);
    }
}
