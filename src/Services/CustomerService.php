<?php

namespace MiniECommers\Backend\Services;

use MiniECommers\Backend\Repository\CustomerRepository;
use MiniECommers\Backend\Request\CustomerRequest;
use MiniECommers\Backend\Models\Customer;

class CustomerService
{
    private CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function createCustomer(Customer $customer): Customer
    {
        // Validasi jika diperlukan (contoh sederhana)
        $this->validateCustomer($customer);

        // Simpan pelanggan melalui repository
        return $this->customerRepository->saveCustomer($customer);
    }

    public function getCustomerById(int $id): ?Customer
    {
        // Ambil pelanggan berdasarkan ID melalui repository
        return $this->customerRepository->getCustomerById($id);
    }

    public function getAllCustomers(): array
    {
        // Ambil semua pelanggan melalui repository
        $customers = $this->customerRepository->getAllCustomer();
        return $this->customerRepository->getAllCustomer();
    }

    public function updateCustomer(Customer $customer): Customer
    {
        // Validasi jika diperlukan
        $this->validateCustomer($customer);

        // Perbarui pelanggan melalui repository
        return $this->customerRepository->updateCustomer($customer);
    }

    public function deleteCustomer(int $id): void
    {
        // Hapus pelanggan berdasarkan ID melalui repository
        $customer = $this->getCustomerById($id);
        if ($customer) {
            $this->customerRepository->deleteCustomer($customer);
        } else {
            throw new \Exception("Customer dengan ID {$id} tidak ditemukan.");
        }
    }

    public function deleteAllCustomers(): void
    {
        // Hapus semua pelanggan melalui repository
        $this->customerRepository->deleteAllCustomer();
    }

    private function validateCustomer(Customer $customer): void
    {
        if (empty($customer->getFirstName()) || empty($customer->getEmail())) {
            throw new \InvalidArgumentException("Nama depan dan email wajib diisi.");
        }
    }
}
