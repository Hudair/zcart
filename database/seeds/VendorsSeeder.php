<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class VendorsSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Merchant::class, 1)
            ->create([
                'shop_id' => 1,
                'email' => 'merchant@demo.com',
            ])
            ->each(function ($merchant) {
                $merchant->dashboard()->save(factory(App\Dashboard::class)->make());

                $merchant->addresses()->save(
                    factory(App\Address::class)->make(['address_title' => $merchant->name, 'address_type' => 'Primary'])
                );
            });

        factory(App\Merchant::class, 1)
            ->create([
                'shop_id' => 2,
                'email' => 'merchant2@demo.com',
            ])
            ->each(function ($merchant) {
                $merchant->dashboard()->save(factory(App\Dashboard::class)->make());

                $merchant->addresses()->save(
                    factory(App\Address::class)->make(['address_title' => $merchant->name, 'address_type' => 'Primary'])
                );
            });

        $this->call('ShopsSeeder');
    }
}
