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

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findAll(): array
    {
        $statement = $this->pdo->query(
            'SELECT id, title, price, discount_percentage, brand, category, thumbnail
         FROM product
         ORDER BY title ASC'
        );

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findById(int $id): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT *
         FROM product
         WHERE id = :id'
        );

        $statement->execute([
            'id' => $id,
        ]);

        $product = $statement->fetch(PDO::FETCH_ASSOC);

        return $product ?: null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    /**
     * @return array<int, array<string, mixed>>
     */
    public function findByFilters(
        ?string $category = null,
        ?string $brand = null,
        string $sort = 'title',
        string $direction = 'asc'
    ): array {
        $conditions = [];
        $parameters = [];

        if ($category !== null && $category !== '') {
            $conditions[] = 'category = :category';
            $parameters['category'] = $category;
        }

        if ($brand !== null && $brand !== '') {
            $conditions[] = 'brand = :brand';
            $parameters['brand'] = $brand;
        }

        $allowedSorts = [
            'title' => 'title',
            'price' => '(price * (1 - discount_percentage / 100))',
            'brand' => 'brand',
            'category' => 'category',
            'discount_percentage' => 'discount_percentage',
        ];

        $sortColumn = $allowedSorts[$sort] ?? $allowedSorts['title'];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'title';
        }

        $direction = strtolower($direction) === 'desc' ? 'DESC' : 'ASC';

        $sql = 'SELECT id, title, price, discount_percentage, brand, category, thumbnail
            FROM product';

        if ($conditions !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= " ORDER BY {$sortColumn} {$direction}";

        $statement = $this->pdo->prepare($sql);
        $statement->execute($parameters);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array<int, string>
     */
    public function findCategories(): array
    {
        $statement = $this->pdo->query(
            'SELECT DISTINCT category
         FROM product
         ORDER BY category ASC'
        );

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * @return array<int, string>
     */
    /**
     * @return array<int, string>
     */
    public function findBrands(?string $category = null): array
    {
        $sql = 'SELECT DISTINCT brand
            FROM product
            WHERE brand IS NOT NULL
              AND brand != ""';

        $parameters = [];

        if ($category !== null && $category !== '') {
            $sql .= ' AND category = :category';
            $parameters['category'] = $category;
        }

        $sql .= ' ORDER BY brand ASC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($parameters);

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }
}