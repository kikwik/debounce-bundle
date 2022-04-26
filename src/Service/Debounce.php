<?php

namespace Kikwik\DebounceBundle\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class Debounce implements DebounceInterface
{
    /**
     * @var string
     */
    private $apiUrl;
    /**
     * @var string
     */
    private $apiKey;
    /**
     * @var array
     */
    private $safeCodes;
    /**
     * @var \Symfony\Contracts\HttpClient\HttpClientInterface
     */
    private $client;


    public function __construct(string $apiKey, string $apiUrl, array $safeCodes, HttpClientInterface $client)
    {
        $this->apiUrl = $apiUrl;
        $this->apiKey = $apiKey;
        $this->safeCodes = $safeCodes;
        $this->client = $client;
    }

    public function check(string $email): array
    {
        if($this->apiKey)
        {
            try {
                $response = $this->client->request(
                    'GET',
                    $this->apiUrl,
                    [
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