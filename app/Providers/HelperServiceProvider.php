<?php

namespace App\Providers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        foreach (glob(app_path() . '/Helpers/*.php') as $filename) {
            require_once($filename);
        }

        if (is_incevio_package_loaded('zipcode')) {
            $zipCode = (! empty(session('zipcode_default')) ?
                session('zipcode_default')
                : get_from_option_table('zipcode_default'));

            Session::put('zipcode_default', $zipCode);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
