<?php

namespace MiniECommers\Backend\Controllers;

use MiniECommers\Backend\Config\Database;
use MiniECommers\Backend\Helpers\Json;
use MiniECommers\Backend\Models\Product;
use MiniECommers\Backend\Repository\ProductRepository;
use MiniECommers\Backend\Services\ProductService;

class ProductController
{

    private ProductService $productService;

    public function __construct()
    {
        $productRepository = new ProductRepository(Database::getConnection());
        $this->productService = new ProductService($productRepository);
    }

    public function getAllProduct()
    {
        $products = $this->productService->getAllProducts();

        echo Json::encode([
            "message" => "Products found",
            "Products" => $products
        ]);
    }

    public function getProductById(int $id)
    {
        try {
            $product = $this->productService->getProductById($id);

            if ($product === null) {
                // Kirim respons jika pelanggan tidak ditemukan
                http_response_code(404);
                echo Json::encode(['error' => "Product with ID {$id} not found."]);
                return;
            }

            echo Json::encode([
                "message" => "Product found",
                "Product" => $product
            ]);
        } catch (\Throwable $e) {
            // Kirim respons error
            http_response_code(500);
            echo Json::encode(['error' => $e->getMessage()]);
        }
    }

    public function saveProduct()
    {
        try {
            $requestData = Json::decode(file_get_contents('php://input'), true);

            $product = new Product();
            $product->setName($requestData['name']);
            $product->setDescription($requestData['description']);
            $product->setPrice($requestData['price']);
            $product->setStockQuantity($requestData['stockQuantity']);

            $savedProduct = $this->productService->createProduct($product);

            echo Json::encode([
                "message" => "Product created successfully",
                "Product" => $savedProduct
            ]);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo Json::encode(['error' => $e->getMessage()]);
        }
    }

    public function updateProduct(int $id)
    {
        try {
            $requestData = Json::decode(file_get_contents('php://input'), true);

            if ($this->productService->getProductById($id) === null) {
                http_response_code(404);
                echo Json::encode([
                    'error' => 'Product not found'
                ]);
                return;
            }

            $product = new Product();
            $product->setName(htmlspecialchars($requestData['name']));
            $product->setDescription(htmlspecialchars($requestData['description']));
            $product->setPrice(htmlspecialchars($requestData['price']));
            $product->setCategory_id(htmlspecialchars($requestData['categories_id']));
            $product->setStockQuantity(htmlspecialchars($requestData['stockQuantity']));
            $product->setId($id);

            $this->productService->updateProduct($product);

            http_response_code(200);
            echo Json::encode(['message' => 'Product updated successfully', "Product" => $product]);
        } catch (\Throwable $th) {
            http_response_code(500);
            echo Json::encode(['error' => $th->getMessage()]);
        }
    }

    public function deleteProduct(int $id)
    {
        try {
            $this->productService->deleteProduct($id);

            http_response_code(200);
            echo Json::encode(['message' => 'Product deleted successfully']);
        } catch (\Throwable $th) {
            http_response_code(500);
            echo Json::encode(['error' => $th->getMessage()]);
        }
    }
}
