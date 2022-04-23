<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerBillingCollection;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionItem;
use App\Models\User;
use App\Services\DateService;
use App\Services\GetIpAdress;
use App\Services\PaymentMethodService;
use App\Services\UserIndexService;
use Illuminate\Http\Request;
use Laravel\Cashier\Events\WebhookReceived;

class SubscriptionController extends Controller
{
    public function index() {
        $user = User::find(3);
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $all_customers = $stripe->customers->all();
        foreach($all_customers as $elem) {
            if($elem->metadata->user_id == $user->id) {
                $search = 'metadata[\'user_id\']:'."'$user->id'";
                $cus = $stripe->customers->search([
                    'query' => $search,
                ]);
                $customer = $cus['data'][0];
            }
            else {
                $customer = $stripe->customers->create([
                    'description' => 'My First Test Customer (created for API docs)',
                    ['metadata' => ['user_id' => $user->id]]
                ]);
            }
        }
        $id = 0;
        if($customer->id) {
            $id = $customer->id;
        }
        else {
            $id = $customer->metadata->user_id;
        }
        $checkout = $stripe->checkout->sessions->create([
            'customer'=>$id,
            'success_url' => 'http://localhost:3000/dashboard/subscription/success',
            'cancel_url' => 'http://localhost:3000/dashboard/subscription/cancel',
            "metadata" => array(
                "user_id" => $user->id,
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
        //подписка
        $sub = $stripe->checkout->sessions->create([
            'customer'=>$customer->id,
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
        return response()->json(['check'=>$checkout,'sub'=>$sub]);
    }
    public function webhook(Request $request)
    {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $arr = $request;
        if($arr['type'] == 'payment_intent.succeeded') {

            $prod = $arr['data']['object']['charges']['data'][0];
            $payment_pi = $prod['payment_intent'];
            $payment_pm = $prod['payment_method'];
            $payment_amount = $prod['amount'] / 100;
            $payment_last4 = $prod['payment_method_details']['card']['last4'];
            $payment_card_brand = $prod['payment_method_details']['card']['brand'];
            $payment_country = $prod['payment_method_details']['card']['country'];
            $payment_currency = $prod['currency'];
            $customer_payment_name = $prod['billing_details']['name'];
            $customer_payment_id = $prod['customer'];
            $payment_risk_level = $prod['outcome']['risk_level'];
            $payment_risk_score = $prod['outcome']['risk_score'];
            $user_payment_customer = $stripe->customers->retrieve(
                $customer_payment_id,
                []
            );
            $product_payment = new Payment();
            $product_payment->user_id = $user_payment_customer->metadata->user_id;
            $product_payment->name = $customer_payment_name;
            $product_payment->currency = $payment_currency;
            $product_payment->amount = $payment_amount;
            $product_payment->last4 = $payment_last4;
            $product_payment->card_brand = $payment_card_brand;
            $product_payment->country = $payment_country;
            $product_payment->customer = $user_payment_customer->id;
            $product_payment->risk_level = $payment_risk_level;
            $product_payment->risk_score = $payment_risk_score;
            $product_payment->pi = $payment_pi;
            $product_payment->pm = $payment_pm;
            $product_payment->save();

        }
        if ($arr['type'] == 'invoice.created') {
            \Log::info($request);
            $customer = $arr['data']['object']['customer'];
            $user_customer = $stripe->customers->retrieve(
                $customer,
                []
            );
            $user_id = $user_customer->metadata->user_id;
            $product_prod = $arr['data']['object']['lines']['data'][0]['plan']['product'];
            $price = $arr['data']['object']['lines']['data'][0]['plan']['id'];
            $stripe_product = $stripe->products->retrieve(
                'prod_LUHfmTvmaDjbfK',
                []
            );
            $stripe_id = SubscriptionItem::where('stripe_id', $arr['data']['object']['lines']['data'][0]['plan']['product'])
                ->firstOrCreate([
                        'stripe_id' => $product_prod,
                        'stripe_product' => $stripe_product->name,
                        'stripe_price' => $price,
                        'quantity' => 1,
                    ]
                );
            $sub = new Subscription();
            $sub->user_id = $user_id;
            $sub->name = $stripe_product->name;
            $sub->stripe_id = $stripe_id->id;
            $sub->stripe_price = $price;
            $abc = $arr['data']['object'];
            $sub->stripe_status = $abc['status'];
            $sub->quantity = $abc['lines']['data'][0]['quantity'];
            if ($abc['lines']['data'][0]['plan']['trial_period_days'] == '') {
                $sub->trial_ends_at = date('Y-m-d');
                $sub->ends_at = DateService::numberToDate($abc['lines']['data'][0]['period']['end']);
            } else {
                $sub->trial_ends_at = $abc['lines']['data'][0]['plan']['trial_period_days'];
                $sub->ends_at = '';
            }
            $sub->created_at = DateService::numberToDate($abc['lines']['data'][0]['period']['start']);
            $sub->updated_at = (string)$abc['created'];
            $sub->invoice_id = $abc['id'];
            $sub->save();

            $endpoint_secret = env('WEBHOOK_KEY');
            $payload = @file_get_contents('php://input');
            $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
            try {
                $event = \Stripe\Webhook::constructEvent(
                    $payload, $sig_header, $endpoint_secret
                );
            } catch (\Stripe\Exception\SignatureVerificationException $e) {
                // Invalid signature
                echo '⚠️  Webhook error while validating signature.';
                http_response_code(400);
                exit();
            }
            if ($request->type == 'checkout.session.completed') {
                \Log::info("its all done");
            }
            return response()->json(["status" => "success"]);
        }
    }
    public function getAllProducts(Request $request) {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $products = $stripe->products->all();
        $subscription = $stripe->subscriptions->all();

        return response()->json(['products'=>$products,'subscription'=>$subscription]);
    }
    public function addProduct() {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $product = $stripe->products->create([
            'name' => 'Gold Special',
            'amount'=>500,
            'description'=>'',
        ]);
        dd($product);
    }
    public function addCustomer($id,Request $request) {
//        $a = GetIpAdress::getIp(); //ip-адрес
        $user = UserIndexService::getUser($id);

        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $customers = $stripe->customers->all();
        foreach($customers as $elem) {
            if(preg_replace('/\D+/', '', $elem->description) != $user->id) {
                $phone = ($user->phone == '') ? null : $user->phone;
                $stripe->customers->create([
                'description' => 'Description',
                "metadata" => array(
                        "user_id" => $user->id,
                    ),
                'name'=>$user->name,
                'email'=>$user->email,
                'phone'=>$phone,
                ]);
            }
            else {
                return response()->json(['status'=>'Такой customer уже существует']);
            }
        }
    }
    public function getProducts($id) {
        $user = UserIndexService::getUser($id);
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $subs = $stripe->subscriptions->retrieve(
            $user->customer_id,
            []
        );
        dd($subs);
    }
    public function getUserSubscriptions() {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
//        $user = User::find(auth()->user()->id);
        $user = User::find(3);
        $search = 'metadata[\'user_id\']:'."'$user->id'";
        $customer = $stripe->customers->search([
            'query' => $search,
        ]);
        $customer_id = $customer['data'][0]->id;
        $cards = $stripe->paymentMethods->all([
            'customer' => $customer_id,
            'type' => 'card',
        ]);
        $arr_cards = [];
        foreach($cards as $card) {
            $arr_cards[] = PaymentMethodService::makeCardParametr($card);
        }
        $cstmr = $stripe->customers->retrieve(
            $customer_id,
            []
        );
        $arr = [];
        $arr1 = [];
        $all_subscriptions = $stripe->subscriptions->all();
        $customer_items = Subscription::where('user_id',$user->id)->with('subscriptionItems')->get();
        $i = 0;
        foreach($customer_items as $elem) {
            $arr[] = $stripe->products->retrieve(
                $elem->subscriptionItems->stripe_id,
                []
            );
            $arr1[] = $stripe->invoices->retrieve(
                $elem->invoice_id,
                []
            );

            $elem->price = $arr[$i]->metadata->price;
            $elem->currency = $arr1[$i]->currency;
            $elem->start = DateService::numberToDate($arr1[$i]['lines']['data'][0]['period']['start']);
            $elem->end = DateService::numberToDate($arr1[$i]['lines']['data'][0]['period']['end']);
            $elem->transaction_id = $elem->subscriptionItems->stripe_id;
            $elem->transaction_date = $arr1[$i]->created;
            $elem->status = $arr1[$i]->paid;
            $elem->amount = $arr[$i]->metadata->price;
            $i = $i + 1;

    }
        return response()->json([new CustomerBillingCollection($customer_items),$arr_cards]);
        }

}
