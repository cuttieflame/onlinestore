<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Stripe\IStripeManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class createCheckoutSessionTest extends TestCase
{

    /** @test  */
    public function test_example()
    {
        $this->expectNotToPerformAssertions();

        $product_id = 10;
        $abc = app(IStripeManager::class);
        $service = $abc->make('stripe');
        $search = 'metadata[\'product_id\']:'."'$product_id'";
        $pr = $service->productsSearch($search);
        $search1 = 'metadata[\'user_id\']:'."'3'";
        $customer_id = $service->customerSearch($search1);
        $checkout = $service->createCheckoutSession($customer_id,$product_id,$pr);
    }
}
