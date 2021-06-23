<?php

namespace Kikwik\DebounceBundle\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class Debounce implements DebounceInterface
{
    /**
     * @var string
     */
    private $apiKey;
    /**
     * @var \Symfony\Contracts\HttpClient\HttpClientInterface
     */
    private $client;


    public function __construct(string $apiKey, HttpClientInterface $client)
    {
        $this->apiKey = $apiKey;
        $this->client = $client;
    }

    public function check(string $email): array
    {
        if($this->apiKey)
        {
            $response = $this->client->request(
                'GET',
                'https://api.debounce.io/v1/',[
                    'query' => [
                        'api' => $this->apiKey,
                        'email' => $email,
                    ],
                ]
            );
            return json_decode($response->getContent(),true);
        }
        else
        {
            return [
                'success' => '0',
                'debounce' => [
                    'error' => 'API Key not defined'
                ]
            ];
        }
    }
}