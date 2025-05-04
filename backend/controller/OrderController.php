<?php
require_once __DIR__ . '/../business/OrderBusinessLogic.php';

class OrderController {
    private $logic;

    public function __construct() {
        $this->logic = new OrderBusinessLogic();
    }

    public function getOrders($userId) {
        try {
            $orders = $this->logic->getOrdersByUser($userId);
            Flight::json($orders);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }

    public function checkout($data) {
        try {
            if (!isset($data['user_id'])) {
                throw new Exception("Missing user_id");
            }

            $order = $this->logic->proceedToCheckout($data['user_id']);
            Flight::json([
                'success' => true,
                'message' => 'Order placed successfully.',
                'order_id' => $order['id'] ?? null
            ]);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 400);
        }
    }
}
