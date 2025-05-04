<?php

// Show full errors in browser and Postman
ini_set(option: 'display_errors', value: 1);
ini_set(option: 'display_startup_errors', value: 1);
error_reporting(error_level: E_ALL);
require_once '../service/FavoritesService.php';

header(header: 'Content-Type: application/json');

$favService = new FavouriteService();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Get all favourites for a user: ?user_id=1
        $userId = $_GET['user_id'];
        $favs = $favService->getFavourites(userId: (int)$userId);
        echo json_encode(value: [
            "success" => true,
            "favourites" => $favs
        ]);
        break;

    case 'POST':
        // Add to favourites: { user_id: 1, product_id: 2 }
        $data = json_decode(json: file_get_contents(filename: "php://input"), associative: true);
        try {
            $favService->addToFavourites(userId: (int)$data['user_id'], productId: (int)$data['product_id']);
            echo json_encode(value: [
                "success" => true,
                "message" => "Added to favourites"
            ]);
        } catch (Exception $e) {
            echo json_encode(value: [
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
        break;

    case 'DELETE':
        // Remove a favourite by ID: ?id=5
        $id = $_GET['id'];
        $favService->removeFromFavourites(favouriteId: (int)$id);
        echo json_encode(value: [
            "success" => true,
            "message" => "Removed from favourites",
            "id" => $id
        ]);
        break;

    default:
        http_response_code(response_code: 405);
        echo json_encode(value: [
            "success" => false,
            "message" => "Method Not Allowed"
        ]);
}
