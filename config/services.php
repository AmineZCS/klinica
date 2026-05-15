<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'polar' => [
        'access_token' => env('POLAR_ACCESS_TOKEN'),
        'server' => env('POLAR_SERVER', 'sandbox'),
        'webhook_secret' => env('POLAR_WEBHOOK_SECRET'),
        'product_starter' => env('POLAR_PRODUCT_STARTER', 'de349818-3047-4c4a-9f16-2357caf67ea3'),
        'product_pro' => env('POLAR_PRODUCT_PRO', '4f8b00a9-788d-47ec-9f25-a3192f6063ac'),
    ],

];
