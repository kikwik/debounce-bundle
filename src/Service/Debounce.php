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
    /**
     * @var array
     */
    private $safeCodes;


    public function __construct(string $apiKey, array $safeCodes, HttpClientInterface $client)
    {
        $this->apiKey = $apiKey;
        $this->client = $client;
        $this->safeCodes = $safeCodes;
    }

    public function check(string $email): array
    {
        if($this->apiKey)
        {
            try {
                $response = $this->client->request(
                    'GET',
                    'https://api.debounce.io/v1/',[
                        'query' => [
                            'api' => $this->apiKey,
                            'email' => $email,
                        ],
                    ]
                );
                $result = json_decode($response->getContent(),true);
                $result['isSafe'] = in_array($result['debounce']['code'],$this->safeCodes);
                return $result;
            }
            catch (\Throwable $e)
            {
                return [
                    'success' => '0',
                    'debounce' => [
                        'error' => $e->getMessage()
                    ]
                ];
            }
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