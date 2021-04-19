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

    protected function baseIndex($queryParams = []): collection
    {
        $finished = false;
        $currentPage = 1;
        $data = [];

        while (true !== $finished) {
            $request = $this->client->getProvider()->getAuthenticatedRequest(
                'GET',
                $this->buildUri($currentPage, $queryParams),
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

    protected function basePost($object, $queryParams = []): object
    {
        $arr = (array) $object;
        foreach($arr as $key => $value) {
            if(!isset($value)) {
                unset($arr[$key]);
            }
        }
        $options = [];
        $options['body'] = json_encode( $arr );
        $options['headers']['Content-Type'] = 'application/json';
        $options['headers']['Accept'] = 'application/json';
        $request = $this->client->getProvider()->getAuthenticatedRequest(
            'POST',
            $this->buildUri(1, $queryParams, true),
            $this->client->getToken(),
            $options
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

    private function buildUri(int $currentPage, $extraParams = [], $postUrl = false): string
    {
        $url = $this->getUrlAPI() . $this->getEndpoint();
        if($postUrl) {
            $url .= '?' . build_query($extraParams, false);
        } else {
            $url .= '?' . build_query(array_merge(['$page' => $currentPage, '$pagesize' => 100], $extraParams), false);
        }

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
