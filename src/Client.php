<?php

declare(strict_types=1);

namespace Webparking\LaravelVisma;

use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessTokenInterface;

class Client
{
    /** @var GenericProvider */
    private $provider;

    /** @var string */
    private $state = null;

    /** @var string */
    private $token = null;

    /** @var ?string */
    private $redirectUrlSuffix = null;

    public function connect(): self
    {
        $this->provider = new GenericProvider([
            'clientId' => config('visma.client_id'),
            'clientSecret' => config('visma.client_secret'),
            'redirectUri' => config('visma.redirect_uri') . $this->getRedirectUrlSuffix(),
            'urlAuthorize' => $this->getUrlAuthorize(),
            'urlAccessToken' => $this->getUrlAccessToken(),
            'urlResourceOwnerDetails' => '',
        ]);

        return $this;
    }

    public function getProvider(): GenericProvider
    {
        if (empty($this->provider)) {
            $this->connect();
        }

        return $this->provider;
    }

    public function getAuthorizationUrl(): string
    {
        return $this->getProvider()->getAuthorizationUrl(['state' => $this->getState()]) . '&scope=' . config('visma.scope');
    }

    public function getAccessToken(string $authorizationCode): AccessTokenInterface
    {
        return $this->getProvider()->getAccessToken('authorization_code', ['code' => $authorizationCode]);
    }

    public function getNewRefreshToken(string $refreshToken): AccessTokenInterface
    {
        return $this->getProvider()->getAccessToken('refresh_token', ['refresh_token' => $refreshToken]);
    }

    public function getUrlAuthorize(): string
    {
        if ('production' === config('visma.environment')) {
            return (string) config('visma.production.authorize_url');
        }

        return (string) config('visma.sandbox.authorize_url');
    }

    public function getUrlAccessToken(): string
    {
        if ('production' === config('visma.environment')) {
            return (string) config('visma.production.token_url');
        }

        return (string) config('visma.sandbox.token_url');
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getRedirectUrlSuffix(): ?string
    {
        return $this->redirectUrlSuffix;
    }

    public function setRedirectUrlSuffix(string $suffix): void
    {
        $this->redirectUrlSuffix = $suffix;
    }

    public function getDefaultPostHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }
}
