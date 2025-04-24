<?php

namespace MiniECommers\Backend\Repository;

use MiniECommers\Backend\Models\OrderItem;
use Exception;

class OrderItemRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getOrderItemById(int $id): ?OrderItem
    {
        $stmt = $this->connection->prepare("SELECT id, order_id, product_id, quantity, totalPrice FROM order_items WHERE id = ?");
        $stmt->execute([$id]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        $orderItem = new OrderItem();
        $orderItem->setId((int)$result['id']);
        $orderItem->setOrder_id((int)$result['order_id']);
        $orderItem->setProduct_id($result['product_id']);
        $orderItem->setQuantity((int)$result['quantity']);
        $orderItem->setTotalPrice((int)$result['totalPrice']);

        return $orderItem;
    }

    public function getOrderItemsByOrderId(int $orderId): array
    {
        $stmt = $this->connection->prepare("SELECT id, order_id, product_id, quantity, totalPrice FROM order_items WHERE order_id = ?");
        $stmt->execute([$orderId]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function saveOrderItem(OrderItem $orderItem): OrderItem
    {
        $stmt = $this->connection->prepare("INSERT INTO order_items (order_id, product_id, quantity, totalPrice) VALUES (?, ?, ?, ?)");
        $success = $stmt->execute([
            $orderItem->getOrder_id(),
            $orderItem->getProduct_id(),
            $orderItem->getQuantity(),
            $orderItem->getTotalPrice()
        ]);

        if (!$success) {
            throw new Exception("Gagal menyimpan item pesanan.");
        }

        $orderItem->setId((int)$this->connection->lastInsertId());

        return $orderItem;
    }

    public function deleteOrderItemById(int $id): void
    {
        $stmt = $this->connection->prepare("DELETE FROM order_items WHERE id = ?");
        $success = $stmt->execute([$id]);

        if (!$success) {
            throw new Exception("Gagal menghapus item pesanan dengan ID: {$id}");
        }
    }

    public function deleteOrderItemsByOrderId(int $orderId): void
    {
        $stmt = $this->connection->prepare("DELETE FROM order_items WHERE order_id = ?");
        $success = $stmt->execute([$orderId]);

        if (!$success) {
            throw new Exception("Gagal menghapus semua item pesanan dengan ID pesanan: {$orderId}");
        }
    }
}
