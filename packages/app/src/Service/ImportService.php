<?php

declare(strict_types=1);

namespace MaxServ\App\Service;

use GuzzleHttp\Exception\GuzzleException;
use MaxServ\App\Api\DummyJsonClient;
use MaxServ\App\Repository\ProductRepository;

final readonly class ImportService
{
    public function __construct(
        private DummyJsonClient $client,
        private ProductRepository $repository
    ) {
    }

    /**
     * @throws GuzzleException
     */
    public function import(): int
    {
        $products = $this->client->fetchProducts();

        foreach ($products as $product) {
            $this->repository->save($product);
        }

        return count($products);
    }
}