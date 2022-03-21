<?php

namespace App\Services;

use Auth;
use App\Customer;
use App\Notifications\Auth\SendVerificationEmail as EmailVerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewCustomer
{
	public function save(Request $request)
	{
        $customer = Customer::create([
            'name' => $request->address_title,
            'email' => $request->email,
            'password' => $request->password,
            'accepts_marketing' => $request->accepts_marketing,
            'verification_token' => Str::random(40),
            'active' => 1,
        ]);

        // Sent email address verification notich to customer
        $customer->notify(new EmailVerificationNotification($customer));

        $customer->addresses()->create($request->all()); //Save address

        if (Auth::guard('web')->check() || Auth::guard('api')->check()) {
            Auth::logout();
        }

		//Login the customer
        if ($request->wantsJson()) {
	        Auth::guard('api')->login($customer);
        }
        else {
	        Auth::guard('customer')->login($customer);
        }

        return $customer;
	}

}