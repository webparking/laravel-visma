<?php

declare(strict_types=1);

namespace Webparking\LaravelVisma\Entities;

use function GuzzleHttp\Psr7\build_query;
use Illuminate\Support\Collection;
use Webparking\LaravelVisma\Client;

abstract class BaseEntity
{
    /*** @var Client */
    protected $client;

    /** @var string */
    protected $endpoint;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    protected function baseIndex(): collection
    {
        $finished = false;
        $currentPage = 1;
        $data = [];

        while (true !== $finished) {
            $request = $this->client->getProvider()->getAuthenticatedRequest(
                'GET',
                $this->buildUri($currentPage),
                $this->client->getToken()
            );

            $json = json_decode($this->client->getProvider()->getResponse($request)->getBody()->getContents());

            $data = array_merge(array_values($data), array_values($json->Data));

            ++$currentPage;
            if ($json->Meta->CurrentPage === $json->Meta->TotalNumberOfPages) {
                $finished = true;
            }
        }

        return collect($data);
    }

    protected function basePost(object $object, $queryParams = []): object
    {
        if ($object instanceof self) {
            throw new \RuntimeException('Object not an instance of BaseEntity');
        }

        $request = $this->client->getProvider()->getAuthenticatedRequest(
            'POST',
            $this->buildUri(1, $queryParams),
            $this->client->getToken(),
            [
                'body' => json_encode(array_filter((array) $object)),
                'headers' => $this->client->getDefaultPostHeaders(),
            ]
        );

        return json_decode($this->client->getProvider()->getResponse($request)->getBody()->getContents());
    }

    protected function baseGet(): object
    {
        $request = $this->client->getProvider()->getAuthenticatedRequest(
            'GET',
            $this->buildUri(1),
            $this->client->getToken()
        );

        return json_decode($this->client->getProvider()->getResponse($request)->getBody()->getContents());
    }

    private function getUrlAPI(): string
    {
        if ('production' === config('visma.environment')) {
            return (string) config('visma.production.api_url');
        }

        return (string) config('visma.sandbox.api_url');
    }

    private function buildUri(int $currentPage, array $extraParams = []): string
    {
        $url = $this->getUrlAPI() . $this->getEndpoint();
        $url .= '?' . build_query(array_merge(['$page' => $currentPage, '$pagesize' => 100], $extraParams), false);

        return $url;
    }

    private function getEndpoint(): string
    {
        if (null === $this->endpoint) {
            throw new \RuntimeException('Endpoint not set!');
        }

        return $this->endpoint;
    }
}
