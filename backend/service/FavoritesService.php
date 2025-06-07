<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/FavouriteDao.php';

class FavouriteService extends BaseService {
    protected $favouriteDao;

    public function __construct() {
        $this->favouriteDao = new FavouriteDao();
        parent::__construct(dao: $this->favouriteDao);
    }

    /**
     * Add product to favourites (if not already added)
     */
    public function addToFavourites($userId, $productId): mixed {
        // Optional: prevent duplicate favourites
        $favourites = $this->favouriteDao->getUserFavourites(userId: $userId);
        foreach ($favourites as $fav) {
            if ($fav['product_id'] == $productId) {
                throw new Exception(message: " Product already in favourites.");
            }
        }

        return $this->favouriteDao->insert(data: [
            'user_id' => $userId,
            'product_id' => $productId
        ]);
    }

    /**
     * Get all favourites for a user
     */
    public function getFavourites($userId): mixed {
        return $this->favouriteDao->getUserFavourites(userId: $userId);
    }

    /**
     * Remove a product from favourites
     */
    public function removeFromFavourites($favouriteId): mixed {
        return $this->favouriteDao->delete(id: $favouriteId);
    }
}
