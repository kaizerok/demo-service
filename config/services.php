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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'ledger' => [
        'endpoint_master' => env('LEDGER_ENDPOINT_MASTER'),
        'endpoint_vat_pl' => env('LEDGER_ENDPOINT_VAT_PL'),
    ],

    'transaction_storage' => [
        'enable' => env('TS_ENABLED'),
        'endpoint' => env('TS_ENDPOINT'),
        'service_id' => env('TS_SERVICE_ID'),
        'secret_key' => env('TS_SECRET_KEY'),
    ],
];
