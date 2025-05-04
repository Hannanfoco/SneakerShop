<?php


// Show full errors in browser and Postman
ini_set(option: 'display_errors', value: 1);
ini_set(option: 'display_startup_errors', value: 1);
error_reporting(error_level: E_ALL);
require_once '../service/CartService.php';

header(header: 'Content-Type: application/json');
$cartService = new CartService();

// Get HTTP method
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // GET /cart?user_id=1
        $userId = $_GET['user_id'];
        echo json_encode(value: $cartService->getCartByUser(userId: (int)$userId));
        break;

    case 'POST':
        // POST /cart (Add or update item)
        $data = json_decode(json: file_get_contents(filename: "php://input"), associative: true);
        $userId = (int)$data['user_id'];
        $productId = (int)$data['product_id'];
        $quantity = (int)$data['quantity'];
        echo json_encode(value: $cartService->addToCart(userId: $userId, productId: $productId, quantity: $quantity));
        break;

    case 'PUT':
        // PUT /cart (Update quantity)
        $data = json_decode(json: file_get_contents(filename: "php://input"), associative: true);
        $cartItemId = (int)$data['cart_item_id'];
        $newQuantity = (int)$data['quantity'];
        echo json_encode(value: $cartService->updateCartItemQuantity(cartItemId: $cartItemId, newQuantity: $newQuantity));
        break;

    case 'DELETE':
        // DELETE /cart?id=10
        $cartItemId = (int)$_GET['id'];
        echo json_encode(value: $cartService->removeItem(cartItemId: $cartItemId));
        break;

    default:
        http_response_code(response_code: 405);
        echo json_encode(value: ['message' => 'Method Not Allowed']);
}
