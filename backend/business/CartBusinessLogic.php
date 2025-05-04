<?php
require_once __DIR__ . '/../service/CartService.php';

class CartBusinessLogic {
    private $cartService;

    public function __construct() {
        $this->cartService = new CartService();
    }

    public function getCartByUser(int $userId): mixed {
        return $this->cartService->getCartByUser(userId: $userId);
    }

    public function addToCart(int $userId, int $productId, int $quantity): mixed {
        if ($quantity <= 0) {
            throw new Exception(message: 'Quantity must be greater than zero.');
        }
        return $this->cartService->addToCart(userId: $userId, productId: $productId, quantity: $quantity);
    }

    public function updateQuantity(int $cartItemId, int $quantity) {
        if ($quantity <= 0) {
            throw new Exception('Quantity must be greater than zero.');
        }
        return $this->cartService->updateCartItemQuantity($cartItemId, $quantity);
    }

    public function removeFromCart(int $cartItemId) {
        return $this->cartService->removeItem($cartItemId);
    }
}
