<?php

namespace App\Services\Stripe;

class StripeService implements IStripeService
{
    private $stripe;

    public function __construct($stripe)
    {
        $this->stripe = $stripe;
    }
    public function productsSearch($query)
    {
        return $this->stripe->products->search([
            'query' => $query,
        ])['data'];
    }
    public function productRetrieve($id) {
        return $this->stripe->products->retrieve(
            $id,
            []
        );
    }

    public function customerSearch($query)
    {
        return $this->stripe->customers->search([
            'query' => $query,
        ]);
    }
    public function getAllCustomers()
    {
        return $this->stripe->customers->all();
    }
    public function createCustomer($user_id,$name = null,$email = null,$phone = null) {
        return $this->stripe->customers->create([
            ['metadata' => ['user_id' => $user_id]]
        ]);
    }

    public function invoceRetrieve($id) {
        return $this->stripe->invoices->retrieve(
            $id,
            []
        );
    }

    public function createCheckoutSession($customer_id,$product_id,$product)
    {
        return $this->stripe->checkout->sessions->create([
            'customer'=>$customer_id['data'][0]->id,
            'success_url' => 'http://localhost:3000/dashboard/subscription/success',
            'cancel_url' => 'http://localhost:3000/dashboard/subscription/cancel',
            'metadata' => [
                'currency' => $product[0]['metadata']['currency'],
                'price'=>$product[0]['metadata']['price'],
                'name'=>$product[0]['name'],
                'product_id'=>$product_id
            ],
            'line_items' => [               [
                'price_data'=> [
                    'currency'=>$product[0]['metadata']['currency'],
                    'unit_amount'=>$product[0]['metadata']['price'] * 100,
                    'product_data'=>[
                        'name'=>$product[0]['name'],
                    ],
                ],
                'quantity'=>1,
            ],
            ],
            'mode' => 'payment',
        ]);
    }
    public function createTestCheckoutSession($customer_id,$user_id)
    {
       return $this->stripe->checkout->sessions->create([
            'customer'=>$customer_id,
            'success_url' => 'http://localhost:3000/dashboard/subscription/success',
            'cancel_url' => 'http://localhost:3000/dashboard/subscription/cancel',
            "metadata" => array(
                "user_id" => $user_id,
            ),
            'line_items' => [               [
                'price_data'=> [
                    'currency'=>'usd',
                    'unit_amount'=>500,
                    'product_data'=>[
                        'name'=>"Cool stripe checkout",
                    ],
                ],
                'quantity'=>1,
            ],
            ],
            'mode' => 'payment',
        ]);
    }
    public function createSubscription($customer_id) {
       return $this->stripe->checkout->sessions->create([
            'customer'=>$customer_id,
            'success_url' => 'http://localhost:3000/dashboard/subscription/success',
            'cancel_url' => 'http://localhost:3000/dashboard/subscription/cancel',
            'line_items' => [
                [
                    'price'=>'price_1KnJ6LLfaQqcaRmQZYNbj8Gj',
                    'quantity'=>1,
                ],
            ],
            'mode' => 'subscription',
        ]);
    }
    public function getAllSubscription()
    {
        $this->stripe->subscriptions->all();
    }
    public function getAllPaymentMethods($customer_id,$type = 'card') {
        return $this->stripe->paymentMethods->all([
            'customer' => $customer_id,
            'type' => $type,
        ]);
    }


}