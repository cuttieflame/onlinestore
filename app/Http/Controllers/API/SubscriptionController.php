<?php

namespace App\Http\Controllers\API;

use App\Contracts\SubscriptionInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerBillingCollection;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionItem;
use App\Models\User;
use App\Services\Date\DateServiceInterface;
use App\Services\PaymentMethodService;
use App\Services\Stripe\IStripeManager;
use App\Services\User\UserServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller implements SubscriptionInterface
{
    private $dateService;
    private $userService;
    public function __construct(DateServiceInterface $dateService, UserServiceInterface $userService)
    {
        $this->dateService = $dateService;
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *      path="/stripe",
     *      operationId="getUserStripeCheckoutSessionAndSubscription",
     *      tags={"Stripe"},
     *      summary="Get list of user stripe checkout session and subscription",
     *      description="get list of user stripe checkout session and subscriptions",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="User not found",
     *      )
     *     )
     */


    public function index() {
        try {
            $user = User::findOrFail(auth()->user()->id);
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['error'=>'User not found'],403);
        }
        $abc = app(IStripeManager::class);
        $service = $abc->make('stripe');
        $all_customers = $service->getAllCustomers();
        foreach($all_customers as $elem) {
            if($elem->metadata->user_id == $user->id) {
                $search = 'metadata[\'user_id\']:'."'$user->id'";
                $cus = $service->customerSearch($search);
                $customer = $cus['data'][0];
            }
            else {
                $customer = $service->createCustomer($user->id);
            }
        }
        $customer_id = $customer->id ? $customer->id : $customer->metadata->user_id;
        $checkout = $service->createTestCheckoutSession($customer_id,$user->id);
        $sub = $service->createSubscription($customer->id);
        return response()->json(['check'=>$checkout,'sub'=>$sub],200);
    }
    public function webhook(Request $request)
    {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $arr = $request;
        if($arr['type'] == 'payment_intent.succeeded') {
            $prod = $arr['data']['object']['charges']['data'][0];
            $user_payment_customer = $stripe->customers->retrieve(
                $prod['customer'],
                []
            );
            Payment::create([
                'user_id'=>$user_payment_customer->metadata->user_id,
                'name'=>$prod['billing_details']['name'],
                'currency'=>$prod['currency'],
                'amount'=>$prod['amount'] / 100,
                'last4'=>$prod['payment_method_details']['card']['last4'],
                'card_brand'=>$prod['payment_method_details']['card']['brand'],
                'country'=>$prod['payment_method_details']['card']['country'],
                'customer'=>$user_payment_customer->id,
                'risk_level'=>$prod['outcome']['risk_level'],
                'risk_score'=>$prod['outcome']['risk_score'],
                'pi'=>$prod['payment_intent'],
                'pm'=>$prod['payment_method'],
            ]);
        }
        if ($arr['type'] == 'invoice.created') {
            $user_customer = $stripe->customers->retrieve(
                $arr['data']['object']['customer'],
                []
            );
            $stripe_product = $stripe->products->retrieve(
                'prod_LUHfmTvmaDjbfK',
                []
            );
            $stripe_id = SubscriptionItem::where('stripe_id', $arr['data']['object']['lines']['data'][0]['plan']['product'])
                ->firstOrCreate([
                        'stripe_id' => $arr['data']['object']['lines']['data'][0]['plan']['product'],
                        'stripe_product' => $stripe_product->name,
                        'stripe_price' => $arr['data']['object']['lines']['data'][0]['plan']['id'],
                        'quantity' => 1,
                    ]
                );
            $abc = $arr['data']['object'];
            $trial_ends_at = '';
            $ends_at = '';
            if ($abc['lines']['data'][0]['plan']['trial_period_days'] == '') {
                $trial_ends_at = date('Y-m-d');
                $ends_at = $this->dateService->numberToDate($abc['lines']['data'][0]['period']['end']);
            } else {
                $trial_ends_at = $abc['lines']['data'][0]['plan']['trial_period_days'];
                $ends_at = '';
            }
            Subscription::create([
                'user_id'=>$user_customer->metadata->user_id,
                'name'=>$stripe_product->name,
                'stripe_id'=>$stripe_id->id,
                'stripe_price'=>$arr['data']['object']['lines']['data'][0]['plan']['id'],
                'stripe_status'=>$abc['status'],
                'quantity'=>$abc['lines']['data'][0]['quantity'],
                'trial_ends_at'=>$trial_ends_at,
                'ends_at'=>$ends_at,
                'created_at'=>$this->dateService->numberToDate($abc['lines']['data'][0]['period']['start']),
                'updated_at'=>(string)$abc['created'],
                'invoice_id'=>$abc['id'],
            ]);
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
                Log::info("its all done");
            }
        }
        return response()->json(['webhook'],200);
    }

    /**
     * @OA\Get(
     *      path="/stripe/allproducts",
     *      operationId="getAllStripeProducts",
     *      tags={"Stripe"},
     *      summary="Get list of stripe products",
     *      description="Returns list of stripe products",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       )
     *     )
     */

    public function getAllProducts(Request $request) {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $products = $stripe->products->all();
        $subscription = $stripe->subscriptions->all();
        return response()->json(['products'=>$products,'subscription'=>$subscription],200);
    }

    /**
     * @OA\Post(
     *      path="/stripe/add/product",
     *      operationId="StripeAddProduct",
     *      tags={"Stripe"},
     *      summary="Create new stripe product",
     *      description="Create new stripe product",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *      )
     * )
     */

    public function addProduct() {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        try {
            $user = User::findOrFail(auth()->user()->id);
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['status'=>'Нет такого пользователя'],403);
        }
        $stripe->products->create([
            'name' => 'name',
            'metadata' => [
                'price'=>123,
                'user_id'=>$user->id,
            ],
        ]);
        return response()->json(['status'=>'Товар успешно создан'],201);
    }

    /**
     * @OA\Post(
     *      path="/stripe/add/customer/{id}",
     *      operationId="StripeAddCustomer",
     *      tags={"Stripe"},
     *      summary="Create new stripe customer",
     *      description="Create new stripe customer",
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *      )
     * )
     */

    public function addCustomer(int $id,Request $request) {
        try {
            $user = $this->userService->getUser($id);
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['error'=>'Ошибка'],403);
        }
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $search = 'metadata[\'user_id\']:'."'$user->id'";
        $customer = $stripe->customers->search([
            'query' => $search,
        ]);
        if(!$customer->data) {
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
            return response()->json(['status'=>'Error'],400);
        }
        return response()->json(['status'=>'Пользователь успешно добавлен'],201);
    }

    /**
     * @OA\Get(
     *      path="/stripe/products/{id}",
     *      operationId="getUserStripeProducts",
     *      tags={"Stripe"},
     *      summary="Get list of user stripe products",
     *      description="Returns list of user stripe products",
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User id",
     *         required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="User not found",
     *      )
     *     )
     */

    public function getProducts(int $id) {
        try {
            $user = $this->userService->getUser($id);
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['error'=>'Ошибка'],403);
        }
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $search = 'metadata[\'user_id\']:'."'$user->id'";
        $customer = $stripe->customers->search([
            'query' => $search,
        ]);
        if(!$customer->data) {
            $stripe->customers->create([
                "metadata" => array("user_id" => $user->id,),
                'name'=>$user->name,
                'email'=>$user->email,
                'phone'=>($user->phone == '') ? null : $user->phone,
            ]);
            return response()->json(['status'=>'Пользователь добавлен'],200);
        }
        else {
            $subs = $stripe->subscriptions->retrieve(
                $customer['data'][0]->id
                []
            );
            return response()->json(['subs'=>$subs],200);
        }
    }

    /**
     * @OA\Get(
     *      path="/user/subscriptions",
     *      operationId="getUserStripeSubscription",
     *      tags={"Stripe"},
     *      summary="Get list of user stripe subscriptions",
     *      description="Returns list of user stripe subscripions",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *       ),
     *      @OA\Response(
     *          response=403,
     *          description="User not found",
     *      )
     *     )
     */

    public function getUserSubscriptions() {
        $abc = app(IStripeManager::class);
        $service = $abc->make('stripe');
        try {
            $user = User::findOrFail(auth()->user()->id);
        }
        catch(ModelNotFoundException $exception) {
            return response()->json(['error'=>'Ошибка'],403);
        }
        $search = 'metadata[\'user_id\']:'."'$user->id'";
        $customer = $service->customerSearch($search);
        if(!$customer->data) {
            $service->createCustomer($user->id,$user->name,$user->email,($user->phone == '') ? null : $user->phone);
            return response()->json(['status'=>'Пользователь создан,так как не был создан раньше'],200);
        }
        else {
            $cards = $service->getAllPaymentMethods($customer['data'][0]->id,'card');
            $arr_cards = [];
            foreach ($cards as $card) {
                $arr_cards[] = PaymentMethodService::makeCardParametr($card);
            }
            $customer_items = $this->userService->makeCustomerItems($user->id,$service);

            return response()->json([new CustomerBillingCollection($customer_items), $arr_cards], 200);
        }
    }

}
