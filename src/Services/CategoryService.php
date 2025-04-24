<?php

namespace MiniECommers\Backend\Services;

use MiniECommers\Backend\Models\Category;
use MiniECommers\Backend\Repository\CategoryRepository;
use Exception;

class CategoryService
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function createCategory(Category $category): Category
    {
        // Validasi kategori
        $this->validateCategory($category);

        // Simpan kategori melalui repository
        return $this->categoryRepository->saveCategory($category);
    }

    public function getCategoryById(int $id): ?Category
    {
        // Ambil kategori berdasarkan ID melalui repository
        return $this->categoryRepository->getCategoryById($id);
    }

    public function getAllCategories(): array
    {
        // Ambil semua kategori melalui repository
        return $this->categoryRepository->getAllCategory();
    }

    public function deleteCategory(int $id): void
    {
        // Ambil kategori untuk memastikan kategori ada
        $category = $this->getCategoryById($id);
        if ($category) {
            $this->categoryRepository->deleteCategoryById($id);
        } else {
            throw new Exception("Kategori dengan ID {$id} tidak ditemukan.");
        }
    }

    public function deleteAllCategories(): void
    {
        // Hapus semua kategori melalui repository
        $this->categoryRepository->deleteAllCategories();
    }

    private function validateCategory(Category $category): void
    {
        if (empty($category->getName())) {
            throw new Exception("Nama kategori wajib diisi.");
        }
    }
}
