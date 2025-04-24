<?php

namespace MiniECommers\Backend\Controllers;

use MiniECommers\Backend\Config\Database;
use MiniECommers\Backend\Helpers\Json;
use MiniECommers\Backend\Repository\CustomerRepository;
use MiniECommers\Backend\Services\CustomerService;
use MiniECommers\Backend\Models\Customer;

class CustomerController
{
    private CustomerService $customerService;

    public function __construct()
    {
        $customerRepository = new CustomerRepository(Database::getConnection(""));
        $this->customerService = new CustomerService($customerRepository);
    }

    /**
     * Handle the creation of a new customer.
     *
     * @return void
     */
    public function createCustomer(): void
    {
        try {
            // Ambil data dari JSON input
            $requestData = Json::decode(file_get_contents('php://input'), true);
            // Buat objek Customer berdasarkan data request
            $customer = new Customer();
            $customer->setFirstName($requestData["firstName"] ?? null); // Nama depan opsional
            $customer->setLastName($requestData["lastName"] ?? null); // Nama belakang opsional
            $customer->setEmail($requestData["email"] ?? null);
            $customer->setAddress($requestData['address'] ?? null); // Address opsional

            // Gunakan service untuk membuat pelanggan
            $savedCustomer = $this->customerService->createCustomer($customer);

            // Kirim respons JSON untuk pelanggan yang berhasil dibuat
            echo Json::encode($savedCustomer);
        } catch (\Exception $e) {
            // Kirim respons error
            http_response_code(500);
            echo Json::encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Get a customer by ID.
     *
     * @param int $id
     * @return void
     */
    public function getCustomerById(int $id): void
    {
        try {
            // Ambil pelanggan dari service
            $customer = $this->customerService->getCustomerById($id);


            if ($customer == null) {
                // Kirim respons jika pelanggan tidak ditemukan
                http_response_code(404);
                echo Json::encode(['error' => "Customer with ID {$id} not found."]);
            }

            // Kirim respons JSON untuk pelanggan yang ditemukan
            echo Json::encode($customer);
        } catch (\Exception $e) {
            // Kirim respons error
            http_response_code(500);
            echo Json::encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Get all customers.
     *
     * @return void
     */
    public function getAllCustomers(): void
    {
        try {
            // Ambil semua pelanggan dari service
            $customers = $this->customerService->getAllCustomers();

            // Kirim respons JSON untuk daftar pelanggan
            echo Json::encode($customers);
        } catch (\Exception $e) {
            // Kirim respons error
            http_response_code(500);
            echo Json::encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Delete a customer by ID.
     *
     * @param int $id
     * @return void
     */
    public function deleteCustomerById(int $id): void
    {
        try {
            // Gunakan service untuk menghapus pelanggan
            $this->customerService->deleteCustomer($id);

            // Kirim respons sukses
            http_response_code(204);
        } catch (\Exception $e) {
            // Kirim respons error
            http_response_code(500);
            echo Json::encode(['error' => $e->getMessage()]);
        }
    }
}
