<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\TrustProxies::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,

        //The Application Middlewares
        \App\Http\Middleware\CheckForInstallation::class,
        \Spatie\Cors\Cors::class,

        // SEO
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,

            //The Application Middlewares
            \App\Http\Middleware\InitSettings::class, //This need to be exactly here, dont move
            \App\Http\Middleware\Language::class,
            \App\Http\Middleware\CookieConsentMiddleware::class,
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
            \App\Http\Middleware\ApiInit::class, //This need to be exactly here, dont move
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'subscribed' => \App\Http\Middleware\VerifyUserIsSubscribed::class,
        'subscriptionEnabled' => \App\Http\Middleware\IsSubscriptionEnabled::class,
        'admin' => \App\Http\Middleware\VerifyUserIsAdmin::class,
        'merchant' => \App\Http\Middleware\VerifyUserIsMerchant::class,
        'storefront' => \App\Http\Middleware\Storefront::class,
        'selling' => \App\Http\Middleware\SellingTheme::class,
        'dashboard' => \App\Http\Middleware\DashboardSetup::class,
        'demoCheck' => \App\Http\Middleware\DemoRestrictionCheck::class,
        'ajax' => \App\Http\Middleware\AllowOnlyAjaxRequests::class,
        'install' => \App\Http\Middleware\CanInstall::class,
        'checkout' => \App\Http\Middleware\CheckForGuestCheckoutMode::class,
        'checkBillingInfo' => \App\Http\Middleware\CheckIfBillingInfoRequired::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Routing\Middleware\ThrottleRequests::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
