<?php
require_once __DIR__ . '/../service/OrderService.php';
require_once __DIR__ . '/../service/CartService.php';
require_once __DIR__ . '/../service/PaymentService.php';

class OrderBusinessLogic {
    private $orderService;
    private $cartService;
    private $paymentService;

    public function __construct() {
        $this->orderService = new OrderService();
        $this->cartService = new CartService();
        $this->paymentService = new PaymentService();
    }

    public function getOrdersByUser($userId) {
        return $this->orderService->getOrdersByUser($userId);
    }

    public function proceedToCheckout($userId) {
        $cartItems = $this->cartService->getCartByUser($userId);

        if (empty($cartItems)) {
            throw new Exception('Cart is empty.');
        }

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['quantity'] * $item['price'];
        }

        $this->paymentService->createPayment($userId, $total, 'completed');
        $order = $this->orderService->createOrder($userId, $total, 'pending');

        foreach ($cartItems as $item) {
            $this->cartService->removeItem($item['id']);
        }

        return $order;
    }
}
