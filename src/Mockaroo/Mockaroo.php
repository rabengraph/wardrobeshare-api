<?php

namespace App\Mockaroo;

use GuzzleHttp\Client;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class Mockaroo
{
    private $apiKey;
    private $denormalizer;
    private $tempFileDir;

    public function __construct($apiKey, $tempFileDir, DenormalizerInterface $denormalizer)
    {
        $this->apiKey = $apiKey;
        $this->httpClient = new Client([
            'base_uri' => 'https://api.mockaroo.com/api',
        ]);
        $this->denormalizer = $denormalizer;
        $this->tempFileDir = $tempFileDir;
    }

    public function makeGenerator($entityClassName)
    {
        return new Generator($entityClassName, $this->apiKey, $this->httpClient, $this->denormalizer, $this->tempFileDir);
    }
}
