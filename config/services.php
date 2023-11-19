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

    'hiorg' => [
        'clientId' => 'juhw_fe2_sync',    // The client ID assigned to you by the provider
        'clientSecret' => env('HIORG_OAUTH_CLIENT_SECRET'),    // The client password assigned to you by the provider
        'redirectUri' => 'https://sync.iuk-wuerzburg.de/auth.php',
        'urlAuthorize' => 'https://api.hiorg-server.de/oauth/v1/authorize',
        'urlAccessToken' => 'https://api.hiorg-server.de/oauth/v1/token',
        'urlResourceOwnerDetails' => 'https://api.hiorg-server.de/oauth/v1/userinfo'
    ],
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

];
