<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | To get a clientId, client secret and redirectId you need to register as partner.
    | https://selfservice.developer.vismaonline.com/
    |--------------------------------------------------------------------------
    */
    'client_id' => env('VISMA_CLIENT_ID'),
    'client_secret' => env('VISMA_CLIENT_SECRET'),
    'redirect_uri' => env('VISMA_REDIRECT_URI'),

    /*
    |--------------------------------------------------------------------------
    | Default scopes should be sufficient see documentation for possible scopes:
    | https://developer.vismaonline.com/docs/getting-started#section-scopes
    |--------------------------------------------------------------------------
    */
    'scope' => env('VISMA_SCOPE', 'ea:api+offline_access+ea:accounting_readonly'),

    /*
    |--------------------------------------------------------------------------
    | For server side processing should always be 'code'
    |--------------------------------------------------------------------------
    */
    'response_type' => env('VISMA_RESPONSE_TYPE', 'code'),

    /*
    |--------------------------------------------------------------------------
    | Which endpoints to use? Possible values 'production' or 'sandbox' (default)
    |--------------------------------------------------------------------------
    */
    'environment' => env('VISMA_ENVIRONMENT', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | Production endpoints
    |--------------------------------------------------------------------------
    */
    'production' => [
        'authorize_url' => 'https://identity.vismaonline.com/connect/authorize',
        'token_url' => 'https://identity.vismaonline.com/connect/token',
        'api_url' => 'https://eaccountingapi.vismaonline.com/v2',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sandbox endpoints
    |--------------------------------------------------------------------------
    */
    'sandbox' => [
        'authorize_url' => 'https://identity-sandbox.test.vismaonline.com/connect/authorize',
        'token_url' => 'https://identity-sandbox.test.vismaonline.com/connect/token',
        'api_url' => 'https://eaccountingapi-sandbox.test.vismaonline.com/v2',
    ],
];
