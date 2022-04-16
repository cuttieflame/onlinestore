<?php

namespace App\Actions\Fortify;

use App\Jobs\CreateNewUserAndSendMail;
use App\Mail\MailAfterRegistration;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Mail;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;


    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ])->validate();

        try {
            return \DB::transaction(function () use ($input): void {
                User::create([
                    'name' => $input['name'],
                    'email' => $input['email'],
                    'password' => Hash::make($input['password']),
                ]);
                CreateNewUserAndSendMail::dispatch($input['email'],$input['name']);
            }, 3);
        } catch (ExternalServiceException $exception) {
            return response()->json(['error'=>'Попробуйте позже']);
        }



    }
}
