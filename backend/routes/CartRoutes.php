<?php
require_once __DIR__ . '/../controller/CartController.php';

Flight::register('cartController', 'CartController');

/**
 * @OA\Get(
 *     path="/cart",
 *     summary="Get all items in a user's cart",
 *     tags={"Cart"},
 *     @OA\Parameter(
 *         name="user_id",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Cart items retrieved successfully")
 * )
 */
Flight::route('GET /cart', function () {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]);
    Flight::cartController()->getCart();
});

/**
 * @OA\Post(
 *     path="/cart",
 *     summary="Add an item to the cart",
 *     tags={"Cart"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id","product_id","quantity"},
 *             @OA\Property(property="user_id", type="integer"),
 *             @OA\Property(property="product_id", type="integer"),
 *             @OA\Property(property="quantity", type="integer")
 *         )
 *     ),
 *     @OA\Response(response=201, description="Item added to cart")
 * )
 */
Flight::route('POST /cart', function () {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]);
    Flight::cartController()->addItem();
});

/**
 * @OA\Put(
 *     path="/cart",
 *     summary="Update an item in the cart",
 *     tags={"Cart"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"id", "quantity"},
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="quantity", type="integer")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Cart item updated")
 * )
 */
Flight::route('PUT /cart', function () {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]);
    Flight::cartController()->updateItem();
});

/**
 * @OA\Delete(
 *     path="/cart",
 *     summary="Delete an item from the cart",
 *     tags={"Cart"},
 *     @OA\Parameter(
 *         name="id",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Cart item deleted")
 * )
 */
Flight::route('DELETE /cart', function () {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]);
    Flight::cartController()->deleteItem();
});