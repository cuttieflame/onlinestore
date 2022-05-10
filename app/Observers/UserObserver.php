<?php

namespace App\Observers;
use Carbon\Carbon;
use App\Models\AccountDetail;
use App\Models\User;

class UserObserver
{
    public function created(User $user)
    {
        AccountDetail::create([
            'id'=>$user->id,
            'first_name'=>'',
            'last_name'=>'',
            'organization'=>'',
            'location'=>'',
            'phone'=>'',
            'birthday'=>Carbon::now()->format('Y-m-d')
        ]);
        activity()
            ->performedOn($user)
            ->causedBy(auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null)
            ->event('create')
            ->inLog('create')
            ->withProperties(['user' => auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null,'this_user'=>$user->id])
            ->log('create user');
    }
    public function updated(User $user)
    {
        activity()
            ->performedOn($user)
            ->causedBy(auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null)
            ->event('update')
            ->inLog('update')
            ->withProperties(['user' => auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null,'this_user'=>$user->id])
            ->log('update user');
    }
    public function deleted(User $user)
    {
       AccountDetail::where('id',$user->id)->delete();
        activity()
            ->performedOn($user)
            ->causedBy(auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null)
            ->event('delete')
            ->inLog('delete')
            ->withProperties(['user' => auth(config("cart.guard"))->check() ? auth(config("cart.guard"))->id() : null,'this_user'=>$user->id])
            ->log('delete user');
    }
    public function restored(User $user)
    {
        //
    }
    public function forceDeleted(User $user)
    {
        //
    }
}
