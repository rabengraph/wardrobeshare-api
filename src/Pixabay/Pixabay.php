<?php

namespace App\Pixabay;

use GuzzleHttp\Client;

class Pixabay
{
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->httpClient = new Client([
            'base_uri' => 'https://pixabay.com',
        ]);
    }

    public function request(array $params)
    {
        $params = \array_merge($params, ['key' => $this->apiKey]);

        $response = $this->httpClient->request('GET', 'api',
            [
                'query' => $params
            ]
        );
        $normalizedItems = json_decode((string) $response->getBody(), true);
        return $normalizedItems;
    }
}
