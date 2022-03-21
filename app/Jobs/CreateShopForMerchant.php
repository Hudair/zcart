<?php

namespace App\Jobs;

use App\User;
use App\Shop;
use Illuminate\Support\Str;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateShopForMerchant
{
    use Dispatchable;

    protected $merchant;
    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  User  $merchant
     * @param  str  $request
     * @return void
     */
    public function __construct(User $merchant, $request)
    {
        $this->merchant = $merchant;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (isset($this->request['active'])) {
            $status = $this->request['active'];
        }
        else {
            $status = config('system_settings.vendor_needs_approval') ? Null : 1;
        }

        if (is_subscription_enabled() && (bool) config('system_settings.trial_days')) {
            $trial_ends_at = now()->addDays(config('system_settings.trial_days'));
        }

        $shop = Shop::create([
            'name' => $this->request['shop_name'],
            'description' => isset($this->request['description']) ? $this->request['description'] : trans('app.welcome'),
            'legal_name' => isset($this->request['legal_name']) ? $this->request['legal_name'] : Null,
            'owner_id' => $this->merchant->id,
            'email' => $this->merchant->email,
            'slug' => isset($this->request['slug']) ? $this->request['slug'] : Str::slug($this->request['shop_name']),
            'external_url' => isset($this->request['external_url']) ? $this->request['external_url'] : Null,
            'timezone_id' => config('system_settings.timezone_id'),
            'card_holder_name' => isset($this->request['name']) ? $this->request['name'] : Null,
            'current_billing_plan' => isset($this->request['plan']) ? $this->request['plan'] : Null,
            'trial_ends_at' => $trial_ends_at ?? Null,
            'active' => $status,
        ]);

        // configaring The Shop
        $shop->config()->create([
            'support_email' => $this->merchant->email,
            'default_sender_email_address' => $this->merchant->email,
            'maintenance_mode' => 1,
        ]);

        // Updating shop_id field in user table
        $this->merchant->shop_id = $shop->id;
        $this->merchant->save();

        // Creating WordWide shippingZones for the Shop
        $shop->shippingZones()->create([
            'name' => trans('app.worldwide'),
            'tax_id' => 1,
            'country_ids' => [],
            'state_ids' => [],
            'rest_of_the_world' => true,
        ]);
    }
}
