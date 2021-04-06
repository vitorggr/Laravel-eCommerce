<?php
    return [
        'name' => 'stripe',
        'description' => 'A mais nova forma de pagamento do mercado',
        'key' => env('STRIPE_KEY', 'xxx'),
        'secret' => env('STRIPE_SECRET', 'xxx'),
        'redirect_url' => env('STRIPE_REDIRECT_URL', 'xxxx'),
        'cancel_url' => env('STRIPE_CANCEL_URL', 'xxxx'),
        'failed_url' => env('STRIPE_FAILED_URL', 'xxxx'),
    ];