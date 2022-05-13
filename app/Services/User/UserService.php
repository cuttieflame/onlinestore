<?php

namespace App\Services\User;

use App\Models\Subscription;
use App\Models\User;
use App\Services\Date\DateService;
use App\Services\Date\DateServiceInterface;

class UserService implements UserServiceInterface
{
    private $dateService;
    public function __construct(DateServiceInterface $dateService)
    {
        $this->dateService = $dateService;
    }
    public function getUser(int $user_id) {
        $user = User::where('id',$user_id)
            ->with(['account_details'])
            ->with(['role_user' => function ($q) {
                $q->with('roles');
            }])
            ->with(['permission_user' => function ($q) {
                $q->with('permission');
            }])
            ->firstOrFail();
        return $user;
    }

    public function makeCustomerItems(int $user_id,$service) {
        $customer_items = Subscription::where('user_id', $user_id)->with('subscriptionItems')->get();
        $products = [];
        $invoices = [];
        $i = 0;
        foreach ($customer_items as $elem) {
            $products[] = $service->productRetrieve($elem->subscriptionItems->stripe_id);
            $invoices[] = $service->invoceRetrieve($elem->invoice_id);
            $elem->price = $products[$i]->metadata->price;
            $elem->currency = $invoices[$i]->currency;
            $elem->start = $this->dateService->numberToDate($invoices[$i]['lines']['data'][0]['period']['start']);
            $elem->end = $this->dateService->numberToDate($invoices[$i]['lines']['data'][0]['period']['end']);
            $elem->transaction_id = $elem->subscriptionItems->stripe_id;
            $elem->transaction_date = $invoices[$i]->created;
            $elem->status = $invoices[$i]->paid;
            $elem->amount = $products[$i]->metadata->price;
            $i = $i + 1;
        }
        return $customer_items;
    }
}