<?php

namespace MiniECommers\Backend\Services;

use MiniECommers\Backend\Models\Product;
use MiniECommers\Backend\Repository\ProductRepository;
use Exception;

class ProductService
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function createProduct(Product $product): Product
    {
        // Validasi data produk
        $this->validateProduct($product);

        // Simpan produk melalui repository
        return $this->productRepository->saveProduct($product);
    }

    public function getProductById(int $id): ?Product
    {
        // Ambil produk berdasarkan ID melalui repository
        return $this->productRepository->getProductById($id);
    }

    public function getAllProducts(): array
    {
        // Ambil semua produk melalui repository
        return $this->productRepository->getAllProducts();
    }

    public function updateProduct(Product $product): void
    {
        // Validasi data produk
        $this->validateProduct($product);

        // Perbarui produk melalui repository
        $this->productRepository->updateProduct($product);
    }

    public function deleteProduct(int $id): void
    {
        // Ambil produk untuk memastikan produk ada
        $product = $this->getProductById($id);
        if ($product) {
            $this->productRepository->deleteProductById($id);
        } else {
            throw new Exception("Produk dengan ID {$id} tidak ditemukan.");
        }
    }

    public function deleteAllProducts(): void
    {
        // Hapus semua produk melalui repository
        $this->productRepository->deleteAllProducts();
    }

    private function validateProduct(Product $product): void
    {
        if (empty($product->getName()) || empty($product->getPrice())) {
            throw new Exception("Nama dan harga produk wajib diisi.");
        }

        if ($product->getPrice() <= 0) {
            throw new Exception("Harga produk harus lebih besar dari 0.");
        }
    }
}
