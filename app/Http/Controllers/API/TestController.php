<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerBillingCollection;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\ProductCategory;
use App\Models\Subscription;
use App\Models\SubscriptionItem;
use App\Models\User;
use App\Products;
use App\Services\DateService;
use App\Services\ImageToObjectArray;
use App\Services\PaymentMethodService;
use Eav\AttributeOption;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use Monolog\DateTimeImmutable;

class TestController extends Controller
{
    public function index() {

        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $all = $stripe->products->all();
        foreach($all as $elem) {
            $stripe->products->delete(
                $elem->id,
                []
            );
        }

        $user = User::find(3);
        $search = 'metadata[\'user_id\']:'."'$user->id'";
        $cus = $stripe->customers->search([
            'query' => $search,
        ]);

        $customer_id = $cus['data'][0]['id'];
        $all_products = $stripe->prices->all();
        $arr = [];
        foreach($all_products as $elem) {
            $arr[] = $stripe->checkout->sessions->create([
                'customer'=>$customer_id,
                'success_url' => 'http://localhost:3000/dashboard/subscription/success',
                'cancel_url' => 'http://localhost:3000/dashboard/subscription/cancel',
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
            $arr1[] = $stripe->checkout->sessions->create([
                'customer'=>$customer_id,
                'success_url' => 'http://localhost:3000/dashboard/subscription/success',
                'cancel_url' => 'http://localhost:3000/dashboard/subscription/cancel',
                'line_items' => [
                    [
                        'price'=>$elem->id,
                        'quantity'=>1,
                    ],
                ],
                'mode' => 'subscription',
            ]);
        }
        return response()->json(['payments'=>$arr,'subs'=>$arr1]);
    }
//    public function index(Request $request) {
////        activity()
////            ->performedOn($product)
////            ->causedBy($user->id)
////            ->event('update')
////            ->inLog('123')
////            ->withProperties(['key' => 'value'])
////            ->log('edited');
//        $arr = array (
//            'id' => 'evt_1KnlbKLfaQqcaRmQkoFymLO7',
//            'object' => 'event',
//            'api_version' => '2020-08-27',
//            'created' => 1649777150,
//            'data' =>
//                array (
//                    'object' =>
//                        array (
//                            'id' => 'in_1KnlbGLfaQqcaRmQaRmfJbVc',
//                            'object' => 'invoice',
//                            'account_country' => 'FI',
//                            'account_name' => 'cuttieflame',
//                            'account_tax_ids' => NULL,
//                            'amount_due' => 500,
//                            'amount_paid' => 0,
//                            'amount_remaining' => 500,
//                            'application_fee_amount' => NULL,
//                            'attempt_count' => 0,
//                            'attempted' => false,
//                            'auto_advance' => false,
//                            'automatic_tax' =>
//                                array (
//                                    'enabled' => false,
//                                    'status' => NULL,
//                                ),
//                            'billing_reason' => 'subscription_create',
//                            'charge' => NULL,
//                            'collection_method' => 'charge_automatically',
//                            'created' => 1649777150,
//                            'currency' => 'usd',
//                            'custom_fields' => NULL,
//                            'customer' => 'cus_LUl7rzafMQSOzv',
//                            'customer_address' =>
//                                array (
//                                    'city' => NULL,
//                                    'country' => 'CO',
//                                    'line1' => NULL,
//                                    'line2' => NULL,
//                                    'postal_code' => NULL,
//                                    'state' => NULL,
//                                ),
//                            'customer_email' => 'hysagepyha@mailinator.com',
//                            'customer_name' => 'Samantha Klein',
//                            'customer_phone' => NULL,
//                            'customer_shipping' => NULL,
//                            'customer_tax_exempt' => 'none',
//                            'customer_tax_ids' =>
//                                array (
//                                ),
//                            'default_payment_method' => NULL,
//                            'default_source' => NULL,
//                            'default_tax_rates' =>
//                                array (
//                                ),
//                            'description' => NULL,
//                            'discount' => NULL,
//                            'discounts' =>
//                                array (
//                                ),
//                            'due_date' => NULL,
//                            'ending_balance' => 0,
//                            'footer' => NULL,
//                            'hosted_invoice_url' => 'https://invoice.stripe.com/i/acct_1KmxFhLfaQqcaRmQ/test_YWNjdF8xS214RmhMZmFRcWNhUm1RLF9MVWw3UnpjbnIwMFlEWkh1TklBQjA5SlF0M0xOd08xLDQwMzE3OTU00200Wu2DvWcg?s=ap',
//                            'invoice_pdf' => 'https://pay.stripe.com/invoice/acct_1KmxFhLfaQqcaRmQ/test_YWNjdF8xS214RmhMZmFRcWNhUm1RLF9MVWw3UnpjbnIwMFlEWkh1TklBQjA5SlF0M0xOd08xLDQwMzE3OTU00200Wu2DvWcg/pdf?s=ap',
//                            'last_finalization_error' => NULL,
//                            'lines' =>
//                                array (
//                                    'object' => 'list',
//                                    'data' =>
//                                        array (
//                                            0 =>
//                                                array (
//                                                    'id' => 'il_1KnlbGLfaQqcaRmQ4Z1z7QAg',
//                                                    'object' => 'line_item',
//                                                    'amount' => 500,
//                                                    'currency' => 'usd',
//                                                    'description' => '1 × basic sub (at $5.00 / month)',
//                                                    'discount_amounts' =>
//                                                        array (
//                                                        ),
//                                                    'discountable' => true,
//                                                    'discounts' =>
//                                                        array (
//                                                        ),
//                                                    'livemode' => false,
//                                                    'metadata' =>
//                                                        array (
//                                                        ),
//                                                    'period' =>
//                                                        array (
//                                                            'end' => 1652369150,
//                                                            'start' => 1649777150,
//                                                        ),
//                                                    'plan' =>
//                                                        array (
//                                                            'id' => 'price_1KnJ6LLfaQqcaRmQZYNbj8Gj',
//                                                            'object' => 'plan',
//                                                            'active' => true,
//                                                            'aggregate_usage' => NULL,
//                                                            'amount' => 500,
//                                                            'amount_decimal' => '500',
//                                                            'billing_scheme' => 'per_unit',
//                                                            'created' => 1649667601,
//                                                            'currency' => 'usd',
//                                                            'interval' => 'month',
//                                                            'interval_count' => 1,
//                                                            'livemode' => false,
//                                                            'metadata' =>
//                                                                array (
//                                                                ),
//                                                            'nickname' => NULL,
//                                                            'product' => 'prod_LUHfmTvmaDjbfK',
//                                                            'tiers_mode' => NULL,
//                                                            'transform_usage' => NULL,
//                                                            'trial_period_days' => NULL,
//                                                            'usage_type' => 'licensed',
//                                                        ),
//                                                    'price' =>
//                                                        array (
//                                                            'id' => 'price_1KnJ6LLfaQqcaRmQZYNbj8Gj',
//                                                            'object' => 'price',
//                                                            'active' => true,
//                                                            'billing_scheme' => 'per_unit',
//                                                            'created' => 1649667601,
//                                                            'currency' => 'usd',
//                                                            'livemode' => false,
//                                                            'lookup_key' => NULL,
//                                                            'metadata' =>
//                                                                array (
//                                                                ),
//                                                            'nickname' => NULL,
//                                                            'product' => 'prod_LUHfmTvmaDjbfK',
//                                                            'recurring' =>
//                                                                array (
//                                                                    'aggregate_usage' => NULL,
//                                                                    'interval' => 'month',
//                                                                    'interval_count' => 1,
//                                                                    'trial_period_days' => NULL,
//                                                                    'usage_type' => 'licensed',
//                                                                ),
//                                                            'tax_behavior' => 'unspecified',
//                                                            'tiers_mode' => NULL,
//                                                            'transform_quantity' => NULL,
//                                                            'type' => 'recurring',
//                                                            'unit_amount' => 500,
//                                                            'unit_amount_decimal' => '500',
//                                                        ),
//                                                    'proration' => false,
//                                                    'proration_details' =>
//                                                        array (
//                                                            'credited_items' => NULL,
//                                                        ),
//                                                    'quantity' => 1,
//                                                    'subscription' => 'sub_1KnlbGLfaQqcaRmQYGc2mmE0',
//                                                    'subscription_item' => 'si_LUl7hxjuUHOttr',
//                                                    'tax_amounts' =>
//                                                        array (
//                                                        ),
//                                                    'tax_rates' =>
//                                                        array (
//                                                        ),
//                                                    'type' => 'subscription',
//                                                ),
//                                        ),
//                                    'has_more' => false,
//                                    'total_count' => 1,
//                                    'url' => '/v1/invoices/in_1KnlbGLfaQqcaRmQaRmfJbVc/lines',
//                                ),
//                            'livemode' => false,
//                            'metadata' =>
//                                array (
//                                ),
//                            'next_payment_attempt' => NULL,
//                            'number' => 'DB9A6D94-0007',
//                            'on_behalf_of' => NULL,
//                            'paid' => false,
//                            'paid_out_of_band' => false,
//                            'payment_intent' => 'pi_3KnlbGLfaQqcaRmQ1sX6r1Pc',
//                            'payment_settings' =>
//                                array (
//                                    'payment_method_options' => NULL,
//                                    'payment_method_types' => NULL,
//                                ),
//                            'period_end' => 1649777150,
//                            'period_start' => 1649777150,
//                            'post_payment_credit_notes_amount' => 0,
//                            'pre_payment_credit_notes_amount' => 0,
//                            'quote' => NULL,
//                            'receipt_number' => NULL,
//                            'starting_balance' => 0,
//                            'statement_descriptor' => NULL,
//                            'status' => 'open',
//                            'status_transitions' =>
//                                array (
//                                    'finalized_at' => 1649777150,
//                                    'marked_uncollectible_at' => NULL,
//                                    'paid_at' => NULL,
//                                    'voided_at' => NULL,
//                                ),
//                            'subscription' => 'sub_1KnlbGLfaQqcaRmQYGc2mmE0',
//                            'subtotal' => 500,
//                            'tax' => NULL,
//                            'test_clock' => NULL,
//                            'total' => 500,
//                            'total_discount_amounts' =>
//                                array (
//                                ),
//                            'total_tax_amounts' =>
//                                array (
//                                ),
//                            'transfer_data' => NULL,
//                            'webhooks_delivered_at' => NULL,
//                        ),
//                ),
//            'livemode' => false,
//            'pending_webhooks' => 2,
//            'request' =>
//                array (
//                    'id' => 'req_lafs6Mi9hbUKv5',
//                    'idempotency_key' => '572d01f6-276c-41b5-ba5d-5da2cb7e652f',
//                ),
//            'type' => 'invoice.finalized',
//        );
//        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
//
//        $product_prod = $arr['data']['object']['lines']['data'][0]['plan']['product'];
//        $price = $arr['data']['object']['lines']['data'][0]['plan']['id'];
//        dd($arr['data']['object']['id']);
////        $stripe_product = $stripe->products->retrieve(
////            'prod_LUHfmTvmaDjbfK',
////            []
////        );
////        $a = $stripe->prices->retrieve(
////            $price,
////            []
////        );
////        if($arr['data']['object']['lines']['data'][0]['plan']['product']) {
////            $stripe_id = SubscriptionItem::where('stripe_id',$arr['data']['object']['lines']['data'][0]['plan']['product'])
////                ->firstOrCreate([
////                        'stripe_id' => $product_prod,
////                        'stripe_product' => $stripe_product->name,
////                        'stripe_price' => $price,
////                        'quantity' => 1,
////                ]
////            );
////            $sub = new Subscription();
////            $sub->user_id = 3;
////            $sub->name = $stripe_product->name;
////            $sub->stripe_id = $stripe_id->id;
////            $sub->stripe_price = $price;
////            $sub->stripe_status = $arr['data']['object']['status'];
////            $sub->quantity = $arr['data']['object']['lines']['data'][0]['quantity'];
////            if($arr['data']['object']['lines']['data'][0]['plan']['trial_period_days'] == '') {
////                $sub->trial_ends_at = date('Y-m-d');
////                $sub->ends_at = date('Y-m-d');
////            }
////            else {
////                $sub->trial_ends_at = $arr['data']['object']['lines']['data'][0]['plan']['trial_period_days'];
////                $sub->ends_at = '';
////            }
////            $sub->created_at = (string) $arr['data']['object']['created'];
////            $sub->updated_at =  (string) $arr['data']['object']['created'];
//////            $sub->save();
////        }
//
////        $sub->save();
//        return response()->json($arr);
//    }

}
