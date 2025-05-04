<?php
require_once __DIR__ . '/../controller/FavouriteController.php';

Flight::register('favouriteController', 'FavouriteController');

/**
 * @OA\Get(
 *     path="/favourites",
 *     summary="Get all favourites for a user",
 *     tags={"Favourites"},
 *     @OA\Parameter(
 *         name="user_id",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Favourites retrieved")
 * )
 */
Flight::route('GET /favourites', function () {
    Flight::favouriteController()->getFavourites();
});

/**
 * @OA\Post(
 *     path="/favourites",
 *     summary="Add a product to favourites",
 *     tags={"Favourites"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "product_id"},
 *             @OA\Property(property="user_id", type="integer"),
 *             @OA\Property(property="product_id", type="integer")
 *         )
 *     ),
 *     @OA\Response(response=201, description="Favourite added")
 * )
 */
Flight::route('POST /favourites', function () {
    Flight::favouriteController()->addFavourite();
});

/**
 * @OA\Delete(
 *     path="/favourites",
 *     summary="Remove a favourite by ID",
 *     tags={"Favourites"},
 *     @OA\Parameter(
 *         name="id",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Favourite removed")
 * )
 */
Flight::route('DELETE /favourites', function () {
    Flight::favouriteController()->removeFavourite();
});

