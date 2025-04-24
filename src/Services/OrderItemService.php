<?php

namespace MiniECommers\Backend\Services;

use MiniECommers\Backend\Models\OrderItem;
use MiniECommers\Backend\Repository\OrderItemRepository;
use Exception;

class OrderItemService
{
    private OrderItemRepository $orderItemRepository;

    public function __construct(OrderItemRepository $orderItemRepository)
    {
        $this->orderItemRepository = $orderItemRepository;
    }

    public function createOrderItem(OrderItem $orderItem): OrderItem
    {
        $this->validateOrderItem($orderItem);
        return $this->orderItemRepository->saveOrderItem($orderItem);
    }

    public function deleteOrderItemsByOrderId(int $orderId): void
    {
        $this->orderItemRepository->deleteOrderItemsByOrderId($orderId);
    }

    private function validateOrderItem(OrderItem $orderItem): void
    {
        if ($orderItem->getQuantity() <= 0) {
            throw new Exception("Jumlah item pesanan harus lebih besar dari nol.");
        }

        if ($orderItem->getTotalPrice() <= 0) {
            throw new Exception("Harga item pesanan harus valid.");
        }
    }
}
