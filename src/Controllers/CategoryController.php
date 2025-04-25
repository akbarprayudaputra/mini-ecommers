<?php

namespace MiniECommers\Backend\Controllers;

use MiniECommers\Backend\Config\Database;
use MiniECommers\Backend\Helpers\Json;
use MiniECommers\Backend\Models\Category;
use MiniECommers\Backend\Repository\CategoryRepository;
use MiniECommers\Backend\Services\CategoryService;

class CategoryController
{

    private CategoryService $categoryService;

    public function __construct()
    {
        $categoryRepository = new CategoryRepository(Database::getConnection("development"));
        $this->categoryService = new CategoryService($categoryRepository);
    }

    public function getAllCategories()
    {
        try {
            $categories = $this->categoryService->getAllCategories();

            http_response_code(200);
            echo Json::encode([
                "message" => "Categories found",
                "Categories" => $categories
            ]);
        } catch (\Throwable $th) {
            http_response_code(500);
            echo Json::encode([
                "error" => $th->getMessage()
            ]);
        }
    }

    public function getCategoryById(int $id)
    {
        try {
            $category = $this->categoryService->getCategoryById($id);

            if ($category === null) {
                http_response_code(404);
                echo Json::encode([
                    "error" => "Category with ID $id not found"
                ]);
                return;
            }

            http_response_code(200);
            echo Json::encode([
                "message" => "Category found",
                "category" => $category
            ]);
        } catch (\Throwable $th) {
            http_response_code(500);
            echo Json::encode([
                "error" => $th->getMessage()
            ]);
        }
    }

    public function deleteCategory(int $id)
    {
        try {
            $this->categoryService->deleteCategory($id);

            http_response_code(200);
            echo Json::encode([
                "message" => "Category deleted"
            ]);
        } catch (\Throwable $th) {
            http_response_code(500);
            echo Json::encode([
                "error" => $th->getMessage()
            ]);
        }
    }

    public function addCategory()
    {
        try {
            $requestData = Json::decode(file_get_contents('php://input'), true);

            $category = new Category();
            $category->setName($requestData['name']);

            $savedCategory = $this->categoryService->createCategory($category);

            echo Json::encode([
                'message' => 'Category created successfully',
                'category' => $savedCategory
            ]);
        } catch (\Throwable $th) {
            http_response_code(500);
            echo Json::encode([
                'error' => $th->getMessage()
            ]);
        }
    }
}
