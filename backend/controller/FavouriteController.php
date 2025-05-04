<?php
require_once __DIR__ . '/../business/FavouriteBusinessLogic.php';

class FavouriteController {
    private $logic;

    public function __construct() {
        $this->logic = new FavouriteBusinessLogic();
    }

    public function getFavourites() {
        $userId = Flight::request()->query['user_id'] ?? null;
        if (!$userId) {
            Flight::json(['error' => 'Missing user_id'], 400);
            return;
        }

        try {
            $favourites = $this->logic->getFavourites((int)$userId);
            Flight::json(['success' => true, 'favourites' => $favourites]);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }

    public function addFavourite() {
        $data = Flight::request()->data->getData();

        try {
            $this->logic->addToFavourites((int)$data['user_id'], (int)$data['product_id']);
            Flight::json(['success' => true, 'message' => 'Added to favourites']);
        } catch (Exception $e) {
            Flight::json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function removeFavourite() {
        $id = Flight::request()->query['id'] ?? null;
        if (!$id) {
            Flight::json(['error' => 'Missing favourite id'], 400);
            return;
        }

        try {
            $this->logic->removeFromFavourites((int)$id);
            Flight::json(['success' => true, 'message' => 'Removed from favourites', 'id' => $id]);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }
}
