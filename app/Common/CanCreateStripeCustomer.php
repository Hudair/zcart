<?php

namespace App\Common;

use Auth;
use App\Http\Requests\Request;

/**
 * Attach this Trait to a User (or other model) for easier read/writes on Addresses
 *
 * @author Munna Khan
 */
trait CanCreateStripeCustomer
{
   /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    private function createStripeCustomer()
    {
        if (Auth::guard('customer')->check()) {
	        $customer = Auth::guard('customer')->user();
    	}
        else if (Auth::guard('web')->check() && Auth::user()->isMerchant()) {
	        $customer = Auth::user()->owns ;
		}
		else {
			return;
		}

        $address = $customer->billingAddress ?? $customer->address;

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $stripeCustomer = \Stripe\Customer::create([
            'name' => $this->cardholder_name ?? $customer->name,
            'email' => $customer->email,
            'address' => $address ? $address->toStripeAddress() : '',
            'source' => $this->cc_token,
        ]);

        if (count($stripeCustomer->sources->data) > 0) {
            $customer->card_brand = $stripeCustomer->sources->data[0]->brand;
            $customer->card_holder_name = $stripeCustomer->sources->data[0]->name;
            $customer->card_last_four = $stripeCustomer->sources->data[0]->last4;
        }

        //New Code
        // $source = \Stripe\Customer::createSource($stripeCustomer->id);
        // $source = \Stripe\Customer::createSource($stripeCustomer->id, ['source' => $this->cc_token]);
        // if (count($source) > 0) {
        //     $customer->card_brand = $source->brand;
        //     $customer->card_holder_name = $source->name;
        //     $customer->card_last_four = $source->last4;
        // }

        // Save cart info for future use
        $customer->stripe_id = $stripeCustomer->id;
        $customer->save();

        return $customer;
    }
}