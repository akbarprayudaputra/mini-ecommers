<?php

namespace MiniECommers\Backend\Repository;

use MiniECommers\Backend\Models\Category;

class CategoriyRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getCategoryById(int $id): ?Category
    {
        $stmt = $this->connection->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($result == null) {
            return null;
        }

        $category = new Category();
        $category->setId($result["id"]);
        $category->setName($result["name"]);

        return $category;
    }

    public function saveCategory(Category $category): Category
    {
        $stmt = $this->connection->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$category->getName()]);
        $category->setId($this->connection->lastInsertId());

        return $category;
    }

    public function deleteCategoryById(int $id): void
    {
        $stmt = $this->connection->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function deleteAllCategories(): void
    {
        $stmt = $this->connection->prepare("DELETE FROM categories");
        $stmt->execute();
    }
}
