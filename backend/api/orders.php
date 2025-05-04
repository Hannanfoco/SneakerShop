<?php
require_once '../service/OrderService.php';
require_once '../service/CartService.php';
require_once '../service/PaymentService.php';

header('Content-Type: application/json');

$orderService = new OrderService();
$cartService = new CartService();
$paymentService = new PaymentService();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    // =======================
    // GET: Order History
    // =======================
    case 'GET':
        if (!isset($_GET['user_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing user_id']);
            exit;
        }

        $userId = $_GET['user_id'];
        $orders = $orderService->getOrdersByUser($userId);

        echo json_encode($orders);
        break;

    // =======================
    // POST: Proceed to Checkout
    // =======================
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['user_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing user_id']);
            exit;
        }

        $userId = $data['user_id'];
        $cartItems = $cartService->getCartByUser($userId);

        if (empty($cartItems)) {
            http_response_code(400);
            echo json_encode(['error' => 'Cart is empty']);
            exit;
        }

        // Calculate total
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['quantity'] * $item['price'];
        }

        // Create payment
        $payment = $paymentService->createPayment($userId, $total, 'completed');

        // Create order
        $order = $orderService->createOrder($userId, $total, 'pending');

        // Clear cart
        foreach ($cartItems as $item) {
            $cartService->removeItem($item['id']);
        }

        echo json_encode([
            'success' => true,
            'message' => 'Order placed successfully.',
            'order_id' => $order['id'] ?? null
        ]);
        break;

    // =======================
    // Unsupported Methods
    // =======================
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
