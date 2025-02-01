<?php

return [
    'gateway' => env('PAYMENT_GATEWAY', 'klarna'),
    'thank_you_url' => env('THANK_YOU_URL', 'thank-you'),
    'gateways' => [
        'klarna' => [
            'api_url' => env('KLARNA_API_URL', 'https://api.playground.klarna.com/'),
            'username' => env('KLARNA_API_USERNAME'),
            'password' => env('KLARNA_API_PASSWORD'),
            'secret_key' => env('KLARNA_SECRET_KEY'),
            'redirecting' => [
                'terms' => env('TERMS_URL'),
                'checkout' => env('CHECKOUT_URL'),
                'confirmation' => env('CONFIRMATION_URL'),
                'push' => env('PUSH_URL'),
            ]
        ],
    ]
];