<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApodService
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $apiKey,
    ) {
    }

    public function fetch(?string $date = null): array
    {
        $query = [
            'api_key' => $this->apiKey,
        ];

        if ($date !== null) {
            $query['date'] = $date;
        }

        $response = $this->httpClient->request(
            'GET',
            'https://api.nasa.gov/planetary/apod',
            [
                'query' => $query,
                'http_version' => '1.1',
            ]
        );

        return $response->toArray();
    }
}
