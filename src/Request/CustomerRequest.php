<?php

namespace MiniECommers\Backend\Request;

use MiniECommers\Backend\Models\Customer;

class CustomerRequest
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function validate(): void
    {
        if (empty($this->data['firstName']) || empty($this->data['email'])) {
            throw new \InvalidArgumentException("Data pelanggan tidak valid: firstName dan email wajib diisi.");
        }
    }

    public function toCustomer(): Customer
    {
        $this->validate();

        $customer = new Customer();
        $customer->setFirstName($this->data['firstName']);
        $customer->setLastName($this->data['lastName']);
        $customer->setEmail($this->data['email']);
        $customer->setAddress($this->data['address']);

        return $customer;
    }
}
