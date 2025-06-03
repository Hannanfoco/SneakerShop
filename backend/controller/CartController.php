<?php
require_once __DIR__ . '/../business/CartBusinessLogic.php';

class CartController {
    private $logic;

    public function __construct() {
        $this->logic = new CartBusinessLogic();
    }

    public function getCart() {
    $userId = Flight::request()->query['user_id'] ?? null;
    if (!$userId) {
        Flight::json(['error' => 'Missing user_id'], 400);
        return;
    }

    try {
        $cart = $this->logic->getCartByUser((int)$userId);

        if (!$cart || count($cart) === 0) {
            Flight::json([
                'message' => 'Cart is empty',
                'data' => []
            ], 200);
        } else {
            Flight::json([
                'message' => 'Cart loaded successfully',
                'data' => $cart
            ], 200);
        }

    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], 500);
    }
}


    public function addItem() {
        $data = Flight::request()->data->getData();

        try {
            $added = $this->logic->addToCart((int)$data['user_id'], (int)$data['product_id'], (int)$data['quantity']);
            Flight::json($added);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 400);
        }
    }

    public function updateItem() {
        $data = Flight::request()->data->getData();

        try {
            $updated = $this->logic->updateQuantity((int)$data['cart_item_id'], (int)$data['quantity']);
            Flight::json($updated);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 400);
        }
    }

    public function deleteItem() {
        $itemId = Flight::request()->query['id'] ?? null;
        if (!$itemId) {
            Flight::json(['error' => 'Missing cart item id'], 400);
            return;
        }

        try {
            $deleted = $this->logic->removeFromCart((int)$itemId);
            Flight::json($deleted);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 400);
        }
    }
}
