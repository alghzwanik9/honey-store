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

    'moyasar' => [
        'key' => env('MOYASAR_API_KEY'),
        'publishable_key' => env('MOYASAR_PUBLISHABLE_API_KEY'),
    ],

    'tamara' => [
        'merchant_token' => env('TAMARA_MERCHANT_TOKEN'),
        'notification_token' => env('TAMARA_NOTIFICATION_TOKEN'),
        'public_key' => env('TAMARA_PUBLIC_KEY'),
        'api_url' => env('TAMARA_API_URL', 'https://api-sandbox.tamara.co'),
    ],

];
