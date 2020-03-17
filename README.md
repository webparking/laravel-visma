# Laravel API helper for Visma eAccounting
Does nothing more than helping you setup the oAuth connection for the eAccounting API (https://eaccountingapi.vismaonline.com/swagger/ui/index) and giving you a few simple wrappers to make life a bit easier.

## Installation
```
composer require webparking/laravel-visma
```


## Usage
### Preparations
1. Register your app https://selfservice.developer.vismaonline.com/
2. Set the .env variables (see config/visma.php)

### 1. Making redirect url for initial consent

    /** @var Client $client */
    $client = app()->make(Client::class)->connect();
    return redirect()->away($client->getAuthorizationUrl());
    
### 2. Use the received token to generate an access & refresh token
    $authorizationCode = ''; // Received through request

    /** @var Client $client */
    $client = app()->make(Client::class)->connect();
    
    /** @var AccessTokenInterface $tokens */
    $tokens = $client->getAccessToken($authorizationCode);
    
    // Store those for future requests
    $accessToken = $tokens->getToken();
    $refeshToken = $tokens->getRefreshToken();
        
#### Basic requests preparation
    /** @var VismaClient $client */
    $client = app()->make(VismaClient::class)->connect();
    
    /** @var AccessTokenInterface $tokens */
    $tokens = $client->getNewRefreshToken($refeshToken);
   
    // Store those for future requests 
    $accessToken = $tokens->getToken();
    $refeshToken = $tokens->getRefreshToken();
      
    $client->setToken($accessToken);
    
### Example request
    // Get all accounts
    $accounts = (new Account($client)->index();

    // Get accountBalances at certain date
    $balances = (new AccountBalance($client)->index(Carbon::now());
    
    // Get accountBalances at certain date for specific account
    $balances = (new AccountBalance($client)->index(Carbon::now(), '4001');

### Notes
This is a bare minimal helper for the Visma eAccounting API that just covers what we needed. Feel free to improve it. 

## Licence and Postcardware

This software is open source and licensed under the [MIT license](LICENSE.md).

If you use this software in your daily development we would appreciate to receive a postcard of your hometown.

Please send it to: Webparking BV, Cypresbaan 31a, 2908 LT Capelle aan den IJssel, The Netherlands
