<?php

namespace MiniECommers\Backend\Repository;

use Exception;
use MiniECommers\Backend\Models\Category;

class CategoryRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAllCategory(): array
    {
        $stmt = $this->connection->prepare("SELECT id, name FROM categories");
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getCategoryById(int $id): ?Category
    {
        $stmt = $this->connection->prepare("SELECT id, name FROM categories WHERE id = ?");
        $stmt->execute([$id]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        return $this->mapToCategory($result);
    }

    public function saveCategory(Category $category): Category
    {
        $stmt = $this->connection->prepare("INSERT INTO categories (name) VALUES (?)");
        $success = $stmt->execute([$category->getName()]);

        if (!$success) {
            throw new Exception("Gagal menyimpan kategori.");
        }

        $category->setId((int) $this->connection->lastInsertId());

        return $category;
    }

    public function deleteCategoryById(int $id): void
    {
        $stmt = $this->connection->prepare("DELETE FROM categories WHERE id = ?");
        $success = $stmt->execute([$id]);

        if (!$success) {
            throw new Exception("Gagal menghapus kategori dengan ID: {$id}");
        }
    }

    public function deleteAllCategories(): void
    {
        $stmt = $this->connection->prepare("DELETE FROM categories");
        $success = $stmt->execute();

        if (!$success) {
            throw new Exception("Gagal menghapus semua kategori.");
        }
    }

    /**
     * Helper method untuk memetakan data dari database ke objek Category
     */
    private function mapToCategory(array $data): Category
    {
        $category = new Category();
        $category->setId((int)$data['id']);
        $category->setName($data['name']);

        return $category;
    }
}
