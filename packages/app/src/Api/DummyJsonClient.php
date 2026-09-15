<?php

declare(strict_types=1);

namespace MaxServ\App\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;

final readonly class DummyJsonClient
{
    private const PRODUCTS_ENDPOINT = 'https://dummyjson.com/products?limit=0';

    public function __construct(
        private Client $client
    ) {
    }

    /**
     * @return array<int, array<string, mixed>>
     *
     * @throws GuzzleException
     */
    public function fetchProducts(): array
    {
        $response = $this->client->get(self::PRODUCTS_ENDPOINT);

        $data = json_decode(
            $response->getBody()->getContents(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if (!isset($data['products']) || !is_array($data['products'])) {
            throw new RuntimeException('Invalid response from DummyJSON products API.');
        }

        return $data['products'];
    }
}