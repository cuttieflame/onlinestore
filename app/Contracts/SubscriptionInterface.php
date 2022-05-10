<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface SubscriptionInterface
{
    public function index();
    public function webhook(Request $request);
    public function getAllProducts(Request $request);
    public function addProduct();
    public function addCustomer(int $id,Request $request);
    public function getProducts(int $id);
    public function getUserSubscriptions();

}