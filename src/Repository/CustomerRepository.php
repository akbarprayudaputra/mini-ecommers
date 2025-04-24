<?php

namespace MiniECommers\Backend\Repository;

use MiniECommers\Backend\Models\Customer;

class CustomerRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAllCustomer(): array
    {
        $stmt = $this->connection->prepare("SELECT id, firstName, lastName, email, address FROM customers");
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function saveCustomer(Customer $customer): Customer
    {
        $stmt = $this->connection->prepare("INSERT INTO customers (firstName, lastName, email, address) VALUES (?, ?, ?, ?)");
        $stmt->execute([$customer->getFirstName(), $customer->getLastName(), $customer->getEmail(), $customer->getAddress()]);

        $customer->setId((int)$this->connection->lastInsertId());

        return $customer;
    }

    public function getCustomerById(int $id): ?Customer
    {
        $stmt = $this->connection->prepare("SELECT id, firstName, lastName, email, address FROM customers WHERE id = ?");
        $stmt->execute([$id]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result === false) {
            return null;
        }

        $customer = new Customer();
        $customer->setId($result["id"]);
        $customer->setFirstName($result["firstName"]);
        $customer->setLastName($result["lastName"]);
        $customer->setEmail($result["email"]);
        $customer->setAddress($result["address"]);
        return $customer;
    }

    public function updateCustomer(Customer $customer): Customer
    {
        $stmt = $this->connection->prepare("UPDATE customers SET firstName = ?, lastName = ?, email = ?, address = ? WHERE id = ?");
        $stmt->execute([
            $customer->getFirstName(),
            $customer->getLastName(),
            $customer->getEmail(),
            $customer->getAddress(),
            $customer->getId()
        ]);

        return $customer;
    }

    public function deleteCustomer(Customer $customer): bool
    {
        $stmt = $this->connection->prepare("DELETE FROM customers WHERE id = ?");
        $stmt->execute([$customer->getId()]);

        return $stmt->rowCount() > 0;
    }

    public function deleteAllCustomer(): void
    {
        $stmt = $this->connection->prepare("DELETE FROM customers");
        $stmt->execute();
    }
}
