<?php

namespace MiniECommers\Backend\Services;

use MiniECommers\Backend\Models\Order;
use MiniECommers\Backend\Repository\OrderRepository;
use MiniECommers\Backend\Config\Database;
use Exception;

class OrderService
{
    private OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Create a new order.
     *
     * @param Order $order
     * @return Order
     * @throws Exception
     */
    public function createOrder(Order $order): Order
    {
        // Gunakan transaksi untuk menjamin konsistensi data
        Database::beginTransaction();

        try {
            $savedOrder = $this->orderRepository->saveOrder($order);

            // Commit transaksi jika berhasil
            Database::commitTransaction();

            return $savedOrder;
        } catch (Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            Database::rollbackTransaction();
            throw new Exception("Gagal membuat pesanan: " . $e->getMessage());
        }
    }

    /**
     * Get an order by ID.
     *
     * @param int $id
     * @return Order|null
     */
    public function getOrderById(int $id): ?Order
    {
        return $this->orderRepository->getOrderById($id);
    }

    /**
     * Get all orders.
     *
     * @return array
     */
    public function getAllOrders(): array
    {
        return $this->orderRepository->getAllOrders();
    }

    /**
     * Delete an order by ID.
     *
     * @param int $id
     * @throws Exception
     */
    public function deleteOrderById(int $id): void
    {
        // Gunakan transaksi untuk penghapusan yang aman
        Database::beginTransaction();

        try {
            $this->orderRepository->deleteOrderById($id);

            // Commit transaksi setelah sukses
            Database::commitTransaction();
        } catch (Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            Database::rollbackTransaction();
            throw new Exception("Gagal menghapus pesanan: " . $e->getMessage());
        }
    }

    /**
     * Delete all orders.
     *
     * @throws Exception
     */
    public function deleteAllOrders(): void
    {
        // Gunakan transaksi untuk penghapusan semua data
        Database::beginTransaction();

        try {
            $this->orderRepository->deleteAllOrders();

            // Commit transaksi setelah sukses
            Database::commitTransaction();
        } catch (Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            Database::rollbackTransaction();
            throw new Exception("Gagal menghapus semua pesanan: " . $e->getMessage());
        }
    }
}
