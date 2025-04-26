<?php

namespace MiniECommers\Backend\Repository;

use MiniECommers\Backend\Models\Order;
use Exception;

class OrderRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getOrderById(int $id): ?Order
    {
        $stmt = $this->connection->prepare("SELECT id, orderDate, customer_id, status FROM orders WHERE id = ?");
        $stmt->execute([$id]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        $order = new Order();
        $order->setId($result["id"]);
        $order->setOrderTime($result["orderDate"]);
        $order->setCustomer_id($result["customer_id"]);
        $order->setStatus($result["status"]);

        return $order;
    }

    public function updateOrderPrice($price, $id)
    {
        $stmt = $this->connection->prepare("UPDATE orders SET total = ? WHERE id = ?");
        $stmt->execute([$price, $id]);

        return $stmt->rowCount();
    }

    public function getAllOrders(): array
    {
        $stmt = $this->connection->prepare("SELECT id, orderDate, customer_id, status, total FROM orders");
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function saveOrder(Order $order): Order
    {
        $stmt = $this->connection->prepare("INSERT INTO orders (orderDate, customer_id, status, total) VALUES (?, ?, ?, ?)");
        $success = $stmt->execute([
            $order->getOrderTime(),
            $order->getCustomer_id(),
            $order->getStatus(),
            $order->getTotal(),
        ]);

        if (!$success) {
            throw new Exception("Gagal menyimpan pesanan.");
        }

        $order->setId((int)$this->connection->lastInsertId());

        return $order;
    }

    public function deleteOrderById(int $id): int
    {
        // Prepare the SQL statement
        $stmt = $this->connection->prepare("DELETE FROM orders WHERE id = ?");

        // Execute the statement
        $success = $stmt->execute([$id]);

        // Check if execution was successful
        if (!$success) {
            throw new Exception("Gagal menghapus pesanan dengan ID: {$id}");
        }

        // Retrieve the number of rows affected
        $rowCount = $stmt->rowCount();

        // Return the row count
        return $rowCount;
    }

    public function deleteAllOrders(): void
    {
        $stmt = $this->connection->prepare("DELETE FROM orders");
        $success = $stmt->execute();

        if (!$success) {
            throw new Exception("Gagal menghapus semua pesanan.");
        }
    }
}
