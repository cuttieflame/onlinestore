<?php

namespace App\Services\Stripe;
interface IStripeService
{
    public function productsSearch($query);
    public function customerSearch($query);
    public function createCheckoutSession($customer_id,$product_id,$product);
    public function getAllCustomers();
    public function createCustomer($user_id);
    public function createTestCheckoutSession($customer_id,$user_id);
    public function createSubscription($customer_id);
    public function getAllSubscription();

}