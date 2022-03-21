<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    //Mandrill Config
    'mandrill' => [
        'secret' => env('MAIL_PASSWORD'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Shop::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'client_id' => env('STRIPE_CLIENT_ID'),
        'account_id' => env('STRIPE_ACCOUNT_ID'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
            'tolerance' => env('STRIPE_WEBHOOK_TOLERANCE', 300),
        ],
    ],

    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret' => env('PAYPAL_CLIENT_SECRET'),
        'merchant_id' => env('PAYPAL_PARTNER_MERCHANT_ID'),
        'webhook_id' => env('PAYPAL_WEBHOOK_ID'),
        'sandbox' => env('PAYPAL_SANDBOX_MODE'),
    ],

    'instamojo' => [
        'api_key' => env('INSTAMOJO_API_KEY'),
        'auth_token' => env('INSTAMOJO_AUTH_TOKEN'),
        'sandbox' => env('INSTAMOJO_SANDBOX'),
    ],

    'paystack' => [
        'public_key' => env('PAYSTACK_PUBLIC_KEY'),
        'secret' => env('PAYSTACK_SECRET'),
        'sandbox' => env('PAYSTACK_SANDBOX'),
    ],

    'cybersource' => [
        'merchant_id' => env('CYBERSOURCE_MERCHANT_ID'),
        'api_key_id' => env('CYBERSOURCE_API_KEY_ID'),
        'secret' => env('CYBERSOURCE_SECRET'),
        'sandbox' => env('CYBERSOURCE_SANDBOX'),
    ],

    'instamojo' => [
        'api_key' => env('INSTAMOJO_API_KEY'),
        'auth_token' => env('INSTAMOJO_AUTH_TOKEN'),
        'sandbox' => env('INSTAMOJO_SANDBOX'),
    ],

    'authorize-net' => [
        'api_login_id' => env('AUTHORIZENET_API_LOGIN_ID'),
        'transaction_key' => env('AUTHORIZENET_TRANSACTION_KEY'),
        'sandbox' => env('AUTHORIZENET_SANDBOX'),
    ],

    'nexmo' => [
        'key' => env('NEXMO_KEY'),
        'secret' => env('NEXMO_SECRET'),
        'sms_from' => '15556666666',
    ],

    'facebook' => [
        'client_id'     => env('FB_CLIENT_ID'),
        'client_secret' => env('FB_CLIENT_SECRET'),
        'redirect'      => env('FB_REDIRECT_URL'),
    ],

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT_URL'),
    ],

    'recaptcha' => [
        'key' => env('GOOGLE_RECAPTCHA_KEY'),
        'secret' => env('GOOGLE_RECAPTCHA_SECRET'),
    ],

    'pusher' => [
        'id' => env('PUSHER_APP_ID'),
        'key' => env('PUSHER_APP_KEY'),
        'secret' => env('PUSHER_APP_SECRET'),
        'cluster' => env('PUSHER_APP_CLUSTER'),
    ],

];