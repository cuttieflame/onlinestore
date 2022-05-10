<?php

namespace App\Services\Stripe;
interface IStripeService
{
    public function productsSearch($query);
    public function productRetrieve($id);
    public function customerSearch($query);
    public function createCheckoutSession($customer_id,$product_id,$product);
    public function getAllCustomers();
    public function createCustomer($user_id,$name,$email,$phone);
    public function createTestCheckoutSession($customer_id,$user_id);
    public function createSubscription($customer_id);
    public function getAllSubscription();
    public function getAllPaymentMethods($customer_id,$type);
    public function invoceRetrieve($id);

}