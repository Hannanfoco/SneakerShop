<?php
require_once __DIR__ . '/../service/FavoritesService.php';

class FavouriteBusinessLogic {
    private $favouriteService;

    public function __construct() {
        $this->favouriteService = new FavouriteService();
    }

    public function getFavourites(int $userId) {
        return $this->favouriteService->getFavourites($userId);
    }

    public function addToFavourites(int $userId, int $productId) {
        return $this->favouriteService->addToFavourites($userId, $productId);
    }

    public function removeFromFavourites(int $favouriteId) {
        return $this->favouriteService->removeFromFavourites($favouriteId);
    }
}
