<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/CartDao.php';

class CartService extends BaseService {
    protected $cartDao;

    public function __construct() {
        $this->cartDao = new CartDao();
        parent::__construct(dao: $this->cartDao);
    }

    /**
     * Add a product to user's cart.
     * If already in cart, update quantity.
     */
    public function addToCart(int $userId, int $productId, int $quantity): mixed {
        $cartItems = $this->cartDao->getUserCart(userId: $userId);

        foreach ($cartItems as $item) {
            if ($item['product_id'] == $productId) {
                $newQty = $item['quantity'] + $quantity;
                return $this->cartDao->update(id: $item['id'], data: ['quantity' => $newQty]);
            }
        }

        return $this->cartDao->insert(data: [
            'user_id' => $userId,
            'product_id' => $productId,
            'quantity' => $quantity
        ]);
    }

    /**
     * Get the cart for a specific user
     */
    public function getCartByUser(int $userId): mixed {
        return $this->cartDao->getUserCart(userId: $userId);
    }

    /**
     * Update quantity of a specific cart item
     */
    public function updateCartItemQuantity(int $cartItemId, int $newQuantity): mixed {
        return $this->cartDao->update(id: $cartItemId, data: ['quantity' => $newQuantity]);
    }

    /**
     * Remove an item from cart
     */
    public function removeItem(int $cartItemId): mixed {
        return $this->cartDao->delete(id: $cartItemId);
    }
}
