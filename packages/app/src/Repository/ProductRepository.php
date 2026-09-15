<?php

declare(strict_types=1);

namespace MaxServ\App\Repository;

use MaxServ\Core\Database\Connection;
use PDO;

final readonly class ProductRepository
{
    private PDO $pdo;

    public function __construct(Connection $connection)
    {
        $this->pdo = $connection->getConnection();
    }

    /**
     * @param array<string, mixed> $product
     */
    public function save(array $product): void
    {
        $statement = $this->pdo->prepare(
            <<<'SQL'
            INSERT INTO product (
                id,
                title,
                description,
                category,
                price,
                discount_percentage,
                brand,
                thumbnail,
                rating,
                stock,
                sku
            ) VALUES (
                :id,
                :title,
                :description,
                :category,
                :price,
                :discount_percentage,
                :brand,
                :thumbnail,
                :rating,
                :stock,
                :sku
            )
            ON DUPLICATE KEY UPDATE
                title = VALUES(title),
                description = VALUES(description),
                category = VALUES(category),
                price = VALUES(price),
                discount_percentage = VALUES(discount_percentage),
                brand = VALUES(brand),
                thumbnail = VALUES(thumbnail),
                rating = VALUES(rating),
                stock = VALUES(stock),
                sku = VALUES(sku)
            SQL
        );

        $statement->execute([
            'id' => $product['id'],
            'title' => $product['title'],
            'description' => $product['description'],
            'category' => $product['category'],
            'price' => $product['price'],
            'discount_percentage' => $product['discountPercentage'],
            'brand' => $product['brand'] ?? null,
            'thumbnail' => $product['thumbnail'] ?? null,
            'rating' => $product['rating'] ?? null,
            'stock' => $product['stock'] ?? 0,
            'sku' => $product['sku'] ?? null,
        ]);
    }
}