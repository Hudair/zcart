<?php

namespace App\Providers;

use Request;
use App\Shop;
use App\Order;
use App\Refund;
use App\Observers\ShopObserver;
use App\Observers\RefundObserver;
use App\Observers\OrderObserver;
use App\Contracts\PaymentServiceContract;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (
            isset($_SERVER['HTTPS']) &&
            ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&  $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
        ) {
            \URL::forceScheme('https');
        }

        Blade::withoutDoubleEncoding();
        Paginator::useBootstrapThree();

        Shop::observe(ShopObserver::class);
        Order::observe(OrderObserver::class);
        Refund::observe(RefundObserver::class);

        // Add Google recaptcha validation rule
        Validator::extend('recaptcha', 'App\\Helpers\\ReCaptcha@validate');

        // Disable encryption for gdpr cookie
        $this->app->resolving(EncryptCookies::class, function (EncryptCookies $encryptCookies) {
            $encryptCookies->disableFor(config('gdpr.cookie.name'));
        });

        // Add pagination on collections
        if (!Collection::hasMacro('paginate')) {
            Collection::macro('paginate', function ($perPage = 15, $page = null, $options = []) {
                $q = url()->full();
                // Remove unwanted page parameter from the url if exist
                if (Request::has('page')) {
                    $q = remove_url_parameter($q, 'page');
                }

                $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

                $paginator = new LengthAwarePaginator($this->forPage($page, $perPage), $this->count(), $perPage, $page, $options);

                return $paginator->withPath($q);
            });
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Cashier::ignoreMigrations(); // Need for cashier 10

        //Payment method binding for wallet deposit
        if (Request::has('payment_method')) {
            $className = $this->resolvePaymentDependency(Request::get('payment_method'));
            $this->app->bind(PaymentServiceContract::class, $className);
        }

        // Ondemand Img manupulation
        $this->app->singleton(
            \League\Glide\Server::class,
            function ($app) {
                $filesystem = $app->make(\Illuminate\Contracts\Filesystem\Filesystem::class);

                return \League\Glide\ServerFactory::create([
                    'response' => new \League\Glide\Responses\LaravelResponseFactory(app('request')),
                    'driver' => config('image.driver'),
                    'presets' => config('image.sizes'),
                    'source' => $filesystem->getDriver(),
                    'cache' => $filesystem->getDriver(),
                    'cache_path_prefix' => config('image.cache_dir'),
                    'base_url' => 'image', //Don't change this value
                ]);
            }
        );
    }

    private function resolvePaymentDependency($class_name)
    {
        switch ($class_name) {
            case 'stripe':
            case 'saved_card':
                return \App\Services\Payments\StripePaymentService::class;

            case 'instamojo':
                return \App\Services\Payments\InstamojoPaymentService::class;

            case 'authorize-net':
                return \App\Services\Payments\AuthorizeNetPaymentService::class;

            case 'cybersource':
                return \App\Services\Payments\CybersourcePaymentService::class;

            case 'paystack':
                return \App\Services\Payments\PaystackPaymentService::class;

            case 'paypal-express':
                return \App\Services\Payments\PaypalExpressPaymentService::class;

            case 'wire':
                return \App\Services\Payments\WirePaymentService::class;

            case 'cod':
                return \App\Services\Payments\CodPaymentService::class;

            case 'jrfpay':
                return \Incevio\Package\Jrfpay\Services\JrfpayPaymentService::class;
        }

        throw new \ErrorException('Error: Payment Method Not Found.');
    }
}
