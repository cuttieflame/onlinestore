<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerBillingCollection;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Payment;
use App\Models\ProductCategory;
use App\Models\Subscription;
use App\Models\SubscriptionItem;
use App\Models\User;
use App\Products;
use App\Services\DateService;
use App\Services\ImageToObjectArray;
use App\Services\PaymentMethodService;
use DateTimeImmutable;
use Eav\AttributeOption;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class TestController extends Controller
{
    public function index() {
        $arr =  array (
            'id' => 'evt_1Kr0T1LfaQqcaRmQbqY6DcO9',
            'object' => 'event',
            'api_version' => '2020-08-27',
            'created' => 1650549280,
            'data' =>
                array (
                    'object' =>
                        array (
                            'id' => 'in_1Kr0SyLfaQqcaRmQuy8CPiuT',
                            'object' => 'invoice',
                            'account_country' => 'FI',
                            'account_name' => 'cuttieflame',
                            'account_tax_ids' => NULL,
                            'amount_due' => 500,
                            'amount_paid' => 0,
                            'amount_remaining' => 500,
                            'application_fee_amount' => NULL,
                            'attempt_count' => 0,
                            'attempted' => false,
                            'auto_advance' => false,
                            'automatic_tax' =>
                                array (
                                    'enabled' => false,
                                    'status' => NULL,
                                ),
                            'billing_reason' => 'subscription_create',
                            'charge' => NULL,
                            'collection_method' => 'charge_automatically',
                            'created' => 1650549280,
                            'currency' => 'usd',
                            'custom_fields' => NULL,
                            'customer' => 'cus_LV6xRkhwDkgpbL',
                            'customer_address' => NULL,
                            'customer_email' => 'kokodah222@gmail.com',
                            'customer_name' => 'viktor sosunkov',
                            'customer_phone' => NULL,
                            'customer_shipping' => NULL,
                            'customer_tax_exempt' => 'none',
                            'customer_tax_ids' =>
                                array (
                                ),
                            'default_payment_method' => NULL,
                            'default_source' => NULL,
                            'default_tax_rates' =>
                                array (
                                ),
                            'description' => NULL,
                            'discount' => NULL,
                            'discounts' =>
                                array (
                                ),
                            'due_date' => NULL,
                            'ending_balance' => 0,
                            'footer' => NULL,
                            'hosted_invoice_url' => 'https://invoice.stripe.com/i/acct_1KmxFhLfaQqcaRmQ/test_YWNjdF8xS214RmhMZmFRcWNhUm1RLF9MWTZnTUVzYndoQVA3OWpscnlaeWhXdVRQSE5iMTgwLDQxMDkwMDgz0200Ilc6WdqZ?s=ap',
                            'invoice_pdf' => 'https://pay.stripe.com/invoice/acct_1KmxFhLfaQqcaRmQ/test_YWNjdF8xS214RmhMZmFRcWNhUm1RLF9MWTZnTUVzYndoQVA3OWpscnlaeWhXdVRQSE5iMTgwLDQxMDkwMDgz0200Ilc6WdqZ/pdf?s=ap',
                            'last_finalization_error' => NULL,
                            'lines' =>
                                array (
                                    'object' => 'list',
                                    'data' =>
                                        array (
                                            0 =>
                                                array (
                                                    'id' => 'il_1Kr0SyLfaQqcaRmQvh5Ceysl',
                                                    'object' => 'line_item',
                                                    'amount' => 500,
                                                    'currency' => 'usd',
                                                    'description' => '1 × basic sub (at $5.00 / month)',
                                                    'discount_amounts' =>
                                                        array (
                                                        ),
                                                    'discountable' => true,
                                                    'discounts' =>
                                                        array (
                                                        ),
                                                    'livemode' => false,
                                                    'metadata' =>
                                                        array (
                                                        ),
                                                    'period' =>
                                                        array (
                                                            'end' => 1653141280,
                                                            'start' => 1650549280,
                                                        ),
                                                    'plan' =>
                                                        array (
                                                            'id' => 'price_1KnJ6LLfaQqcaRmQZYNbj8Gj',
                                                            'object' => 'plan',
                                                            'active' => true,
                                                            'aggregate_usage' => NULL,
                                                            'amount' => 500,
                                                            'amount_decimal' => '500',
                                                            'billing_scheme' => 'per_unit',
                                                            'created' => 1649667601,
                                                            'currency' => 'usd',
                                                            'interval' => 'month',
                                                            'interval_count' => 1,
                                                            'livemode' => false,
                                                            'metadata' =>
                                                                array (
                                                                ),
                                                            'nickname' => NULL,
                                                            'product' => 'prod_LUHfmTvmaDjbfK',
                                                            'tiers_mode' => NULL,
                                                            'transform_usage' => NULL,
                                                            'trial_period_days' => NULL,
                                                            'usage_type' => 'licensed',
                                                        ),
                                                    'price' =>
                                                        array (
                                                            'id' => 'price_1KnJ6LLfaQqcaRmQZYNbj8Gj',
                                                            'object' => 'price',
                                                            'active' => true,
                                                            'billing_scheme' => 'per_unit',
                                                            'created' => 1649667601,
                                                            'currency' => 'usd',
                                                            'livemode' => false,
                                                            'lookup_key' => NULL,
                                                            'metadata' =>
                                                                array (
                                                                ),
                                                            'nickname' => NULL,
                                                            'product' => 'prod_LUHfmTvmaDjbfK',
                                                            'recurring' =>
                                                                array (
                                                                    'aggregate_usage' => NULL,
                                                                    'interval' => 'month',
                                                                    'interval_count' => 1,
                                                                    'trial_period_days' => NULL,
                                                                    'usage_type' => 'licensed',
                                                                ),
                                                            'tax_behavior' => 'unspecified',
                                                            'tiers_mode' => NULL,
                                                            'transform_quantity' => NULL,
                                                            'type' => 'recurring',
                                                            'unit_amount' => 500,
                                                            'unit_amount_decimal' => '500',
                                                        ),
                                                    'proration' => false,
                                                    'proration_details' =>
                                                        array (
                                                            'credited_items' => NULL,
                                                        ),
                                                    'quantity' => 1,
                                                    'subscription' => 'sub_1Kr0SyLfaQqcaRmQpo8AURjj',
                                                    'subscription_item' => 'si_LY6glOHBntFS5I',
                                                    'tax_amounts' =>
                                                        array (
                                                        ),
                                                    'tax_rates' =>
                                                        array (
                                                        ),
                                                    'type' => 'subscription',
                                                ),
                                        ),
                                    'has_more' => false,
                                    'total_count' => 1,
                                    'url' => '/v1/invoices/in_1Kr0SyLfaQqcaRmQuy8CPiuT/lines',
                                ),
                            'livemode' => false,
                            'metadata' =>
                                array (
                                ),
                            'next_payment_attempt' => NULL,
                            'number' => 'DB9A6D94-0034',
                            'on_behalf_of' => NULL,
                            'paid' => false,
                            'paid_out_of_band' => false,
                            'payment_intent' => 'pi_3Kr0SyLfaQqcaRmQ1pmHydzV',
                            'payment_settings' =>
                                array (
                                    'payment_method_options' => NULL,
                                    'payment_method_types' => NULL,
                                ),
                            'period_end' => 1650549280,
                            'period_start' => 1650549280,
                            'post_payment_credit_notes_amount' => 0,
                            'pre_payment_credit_notes_amount' => 0,
                            'quote' => NULL,
                            'receipt_number' => NULL,
                            'starting_balance' => 0,
                            'statement_descriptor' => NULL,
                            'status' => 'open',
                            'status_transitions' =>
                                array (
                                    'finalized_at' => 1650549280,
                                    'marked_uncollectible_at' => NULL,
                                    'paid_at' => NULL,
                                    'voided_at' => NULL,
                                ),
                            'subscription' => 'sub_1Kr0SyLfaQqcaRmQpo8AURjj',
                            'subtotal' => 500,
                            'tax' => NULL,
                            'test_clock' => NULL,
                            'total' => 500,
                            'total_discount_amounts' =>
                                array (
                                ),
                            'total_tax_amounts' =>
                                array (
                                ),
                            'transfer_data' => NULL,
                            'webhooks_delivered_at' => NULL,
                        ),
                ),
            'livemode' => false,
            'pending_webhooks' => 2,
            'request' =>
                array (
                    'id' => 'req_vzQXUHbsPGzH8z',
                    'idempotency_key' => '37a22bcd-c277-4c1e-8f48-c4efc523e746',
                ),
            'type' => 'invoice.created',
        );
//        dd($arr['data']['object']);
        $a = $arr['data']['object'];
        $st = $a['lines']['data'][0]['period']['start'];
        dd($a);

    }

}
