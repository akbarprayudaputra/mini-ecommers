<?php

namespace MiniECommers\Backend\Controllers;

use MiniECommers\Backend\Config\Database;
use MiniECommers\Backend\Helpers\Json;
use MiniECommers\Backend\Models\Order;
use MiniECommers\Backend\Models\OrderItem;
use MiniECommers\Backend\Repository\OrderItemRepository;
use MiniECommers\Backend\Repository\OrderRepository;
use MiniECommers\Backend\Repository\ProductRepository;
use MiniECommers\Backend\Services\OrderItemService;
use MiniECommers\Backend\Services\OrderService;
use MiniECommers\Backend\Services\ProductService;

class OrderController
{
    private OrderService $orderService;
    private OrderItemService $orderItemService;
    private ProductService $productService;

    public function __construct()
    {
        $productRepository = new ProductRepository(Database::getConnection("development"));
        $orderRepository = new OrderRepository(Database::getConnection());
        $orderItemRepository = new OrderItemRepository(Database::getConnection());
        $this->orderService = new OrderService($orderRepository);
        $this->orderItemService = new OrderItemService($orderItemRepository);
        $this->productService = new ProductService($productRepository);
    }

    public function getAllOrders()
    {
        try {
            $orders = $this->orderService->getAllOrders();
            echo Json::encode([
                "message" => "Order found.",
                "Orders" => $orders
            ]);
        } catch (\Throwable $th) {
            http_response_code(500);
            echo Json::encode([
                "error" => $th->getMessage(),
            ]);
        }
    }

    public function createOrder(int $id): void
    {
        try {
            // Decode JSON input
            $requestData = Json::decode(file_get_contents('php://input'), true);

            // Validasi input
            if (!is_array($requestData)) {
                throw new \RuntimeException("Invalid input format.");
            }

            // Buat order
            $order = new Order();
            $order->setCustomer_id($id);

            // Simpan order
            $savedOrder = $this->orderService->createOrder($order);
            $orderId = $savedOrder->getId();

            $orderItems = [];

            // Jika produk tunggal, bungkus menjadi array
            if (isset($requestData['product_id'])) {
                $requestData = [$requestData];
            }

            // Iterasi data produk
            foreach ($requestData as $data) {
                $product = $this->productService->getProductById($data['product_id']);
                $quantity = $data['quantity'];

                // Validasi produk dan stok
                if ($product === null) {
                    throw new \Exception("Product not found (ID: {$data['product_id']}).");
                }

                if ($quantity > $product->getStockQuantity()) {
                    throw new \Exception("Product out of stock for ID: {$data['product_id']}.");
                }

                // Buat OrderItem
                $orderItem = new OrderItem();
                $orderItem->setOrder_id($orderId);
                $orderItem->setProduct_id($data['product_id']);
                $orderItem->setQuantity($quantity);
                $orderItem->setTotalPrice($product->getPrice());

                $totalPrice = $this->totalPrice($orderItem);
                $orderItem->setTotalPrice($totalPrice);

                // Simpan OrderItem
                $this->orderItemService->createOrderItem($orderItem);

                $orderItems[] = $orderItem;
            }

            // Hitung total harga pesanan
            $totalOrderPrice = $this->calculateTotalOrder($orderItems);

            // Update total pesanan di Order
            $this->orderService->updateOrderPrice($orderId, $totalOrderPrice);
            $order->setTotal($totalOrderPrice);

            // Kirim respons sukses
            http_response_code(201); // Created
            echo Json::encode([
                'message' => 'Order created successfully',
                'orderId' => $orderId,
                'totalPrice' => $totalOrderPrice,
                'items' => $orderItems
            ]);
        } catch (\Exception $e) {
            // Tangani error
            http_response_code(500); // Internal Server Error
            echo Json::encode(['error' => $e->getMessage()]);
        }
    }

    public function getOrderById(int $id)
    {
        try {
            $order = $this->orderService->getOrderById($id);

            if ($order == null) {
                throw new \Exception('Order not found');
            }

            echo Json::encode(
                [
                    'message' => 'Order found',
                    'order' => $order
                ]
            );
        } catch (\Throwable $th) {
            http_response_code(500);
            echo Json::encode(['error' => $th->getMessage()]);
        }
    }

    public function deleteOrder(int $orderId)
    {
        try {
            // Attempt to delete the order
            $order = $this->orderService->deleteOrderById($orderId);

            if ($order == null) {
                throw new \Exception('Order not found');
            }

            // Respond with success message
            http_response_code(200); // OK
            echo Json::encode([
                'message' => 'Order deleted successfully',
                'orderId' => $orderId
            ]);
        } catch (\Throwable $th) {
            // Handle errors
            http_response_code(500); // Internal Server Error
            echo Json::encode([
                'error' => $th->getMessage(),
                'orderId' => $orderId
            ]);
        }
    }

    protected function totalPrice(OrderItem $orderItem): float
    {
        return $orderItem->getTotalPrice() * $orderItem->getQuantity();
    }

    protected function calculateTotalOrder(array $orderItems): float
    {
        $total = 0;

        foreach ($orderItems as $item) {
            $total += $item->getTotalPrice();
        }

        return $total;
    }
}
