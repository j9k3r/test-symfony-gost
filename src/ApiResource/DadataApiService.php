<?php

namespace App\ApiResource;

use GuzzleHttp\Client;

class DadataApiService
{
    public function __construct(
        private readonly Client $client
    ) {
    }

    public function findOrganizationByInn(string $inn): array
    {
        $url = 'http://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party';
        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Token ' . $_ENV['DADATA_TOKEN'],
            ],
            'json' => ['query' => $inn],
        ];

        try {
            $response = $this->client->post($url, $options);
            return json_decode($response->getBody()->getContents(), true)['suggestions'][0];
        } catch (\Exception $e) {
            throw new \RuntimeException('API request failed');
        }
    }
}