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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'gerencianet' => [
        'mode' => env('GN_MODE'),
        'debug' => env('GN_DEBUG'),
        'defaultkeypix' => env('GN_DEFAULT_KEY_PIX'),
        'sandbox' => [
            'client_id' => env('GN_TEST_CLIENT_ID'),
            'client_secret' => env('GN_TEST_CLIENT_SECRET'),
            'certificate' => env('GN_TEST_CERTIFICATE'),
        ],
        'production' => [
            'client_id' => env('GN_PROD_CLIENT_ID'),
            'client_secret' => env('GN_PROD_CLIENT_SECRET'),
            'certificate' => env('GN_PROD_CERTIFICATE'),
        ],

    ],


];
