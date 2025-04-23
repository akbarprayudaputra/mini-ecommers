<?php

namespace MiniECommers\Backend\Repository;

use MiniECommers\Backend\Models\Product;

class ProductRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAllProducts(): array
    {
        $query = $this->connection->prepare("SELECT name, description, price, stockQuantity FROM products");
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function saveProduct(Product $product)
    {
        $stmt = $this->connection->prepare("INSERT INTO products (name, description, price, stockQuantity) VALUES (?, ?, ?, ?)");
        $stmt->execute([$product->getName(), $product->getDescription(), $product->getPrice(), $product->getStockQuantity()]);

        $product->setId($this->connection->lastInsertId());

        return $product;
    }
}
