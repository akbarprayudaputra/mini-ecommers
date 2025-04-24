<?php

namespace MiniECommers\Backend\Repository;

use MiniECommers\Backend\Models\Product;
use PDO;

class ProductRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAllProducts(): array
    {
        $query = $this->connection->prepare("SELECT id, name, description, price, categories_id, stockQuantity FROM products");
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function saveProduct(Product $product): Product
    {
        $stmt = $this->connection->prepare("INSERT INTO products (name, description, price, categories_id, stockQuantity) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $product->getName(),
            $product->getDescription(),
            (float) $product->getPrice(),
            $product->getCategory_id(),
            $product->getStockQuantity()
        ]);

        $product->setId((int) $this->connection->lastInsertId());

        return $product;
    }

    public function getProductById(int $id): ?Product
    {
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return $this->mapToProduct($data);
    }

    public function updateProduct(Product $product): void
    {
        $stmt = $this->connection->prepare(
            'UPDATE products SET name = ?, description = ?, price = ?, categories_id = ?, stockQuantity = ? WHERE id = ?'
        );

        $stmt->execute([
            $product->getName(),
            $product->getDescription(),
            (float) $product->getPrice(),
            $product->getCategory_id(),
            $product->getStockQuantity(),
            $product->getId()
        ]);
    }

    public function deleteProductById(int $id): void
    {
        $stmt = $this->connection->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function deleteAllProducts(): void
    {
        $stmt = $this->connection->prepare("DELETE FROM products");
        $stmt->execute();
    }

    /**
     * Helper method to map database row to Product object
     */
    private function mapToProduct(array $data): Product
    {
        $product = new Product();
        $product->setId((int) $data['id']);
        $product->setName($data['name']);
        $product->setDescription($data['description']);
        $product->setPrice((float) $data['price']);
        $product->setCategory_id((int) $data['categories_id']);
        $product->setStockQuantity((int) $data['stockQuantity']);

        return $product;
    }
}
