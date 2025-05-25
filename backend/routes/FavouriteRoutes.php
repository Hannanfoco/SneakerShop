<?php
require_once __DIR__ . '/../controller/FavouriteController.php';

Flight::register('favouriteController', 'FavouriteController');

/**
 * @OA\Get(
 *     path="/favourites",
 *     summary="Get all favourites for a user",
 *     tags={"Favourites"},
 *     @OA\Response(response=200, description="Favourites retrieved")
 * )
 */
Flight::route('GET /favourites', function () {
    Flight::auth_middleware()->authorizeRole(Roles::CUSTOMER); 

    $user = Flight::get('user');
    Flight::favouriteController()->getFavourites($user->id);
});

/**
 * @OA\Post(
 *     path="/favourites",
 *     summary="Add a product to favourites",
 *     tags={"Favourites"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"product_id"},
 *             @OA\Property(property="product_id", type="integer")
 *         )
 *     ),
 *     @OA\Response(response=201, description="Favourite added")
 * )
 */
Flight::route('POST /favourites', function () {
    Flight::auth_middleware()->authorizeRole(Roles::CUSTOMER); 

    $user = Flight::get('user');
    $body = Flight::request()->data->getData();
    $body['user_id'] = $user->id; // Force token user_id
    Flight::favouriteController()->addFavourite($body);
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
    Flight::auth_middleware()->authorizeRole(Roles::CUSTOMER); 

    $user = Flight::get('user');
    $favouriteId = Flight::request()->query['id'] ?? null;
    Flight::favouriteController()->removeFavourite($favouriteId, $user->id);
});
