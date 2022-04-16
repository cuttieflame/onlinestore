<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerBillingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        return parent::toArray($request);

        return [
            'characteristics'=>
            [
            'id'=>$this->id,
            'user_id'=>$this->user_id,
            'name'=>$this->name,
            'currency'=>$this->currency,
            'stripe_id'=>$this->stripe_id,
            'stripe_status'=>$this->stripe_status,
            'stripe_price'=>$this->stripe_price,
            'quantity'=>$this->quantity,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            'invoice_id'=>$this->invoice_id,
            'amount'=>$this->amount,
            'end_subscription'=>$this->end,
            ],
            'billing_history'=>[
            'currency'=>$this->currency,
            'start_subscription'=>$this->start,
            'end_subscription'=>$this->end,
            'transaction_id'=>$this->transaction_id,
            'transcation_date'=>$this->transcation_date,
            'status'=>$this->status,
            'amount'=>$this->amount,
            ],
        ];
    }
}
