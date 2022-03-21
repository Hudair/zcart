<?php

namespace App;

use Carbon\Carbon;
use App\SystemConfig;
use Laravel\Cashier\Exceptions\SubscriptionUpdateFailure;
use Laravel\Cashier\Subscription as CashierSubscription;

class Subscription extends CashierSubscription
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    // protected $appends = ['provider_plan'];

    /**
     * Get the "provider_plan" attribute from the model.
     *
     * @return string
     */
    // public function getProviderPlanAttribute()
    // {
    //     return Spark::billsUsingStripe()
    //                     ? $this->stripe_plan : $this->braintree_plan;
    // }


    /**
     * Swap the subscription to a new Stripe plan.
     *
     * @param  string  $plan
     * @param  array  $options
     * @return $this
     *
     * @throws \Laravel\Cashier\Exceptions\SubscriptionUpdateFailure
     */
    public function swap($plan, $options = [])
    {
        // Local subscription
        if (SystemConfig::isBillingThroughWallet()) {
            $this->fill(['stripe_plan' => $plan])->save();

            return $this;
        }

        // Stripe
        return parent::swap($plan, $options);
    }

    /**
     * Determine if the subscription is active, on trial, or within its grace period.
     *
     * @return bool
     */
    public function valid()
    {
        // Local subscription
        if ($this->provider == 'wallet') {
            return $this->active();
        }

        // Stripe
        return parent::valid();
    }

    /**
     * Determine if the subscription is active.
     *
     * @return bool
     */
    public function active()
    {
        if ($this->provider == 'wallet') {
            return $this->onTrial() || $this->onGracePeriod();
        }

        return parent::active();
    }

    public function getProviderAttribute()
    {
        return $this->stripe_id ? 'stripe' : 'wallet';
    }
}
