<?php
require_once __DIR__ . '/../controller/ProductController.php';
require_once __DIR__ . '/../controller/UserController.php';

Flight::register('productController', 'ProductController');
Flight::register('userController', 'UserController');

/**
 * @OA\Get(
 *     path="/products",
 *     summary="Get all products (with optional filters, Admin and Customer)",
 *     tags={"Products"},
 *     security={{"Bearer":{}}},
 *     @OA\Parameter(name="id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="brand", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="price", in="query", @OA\Schema(type="number", format="float")),
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
 *     @OA\Response(response=200, description="Products list"),
 *     @OA\Response(response=403, description="Forbidden")
 * )
 */
Flight::route('GET /products', function () {
    $token = Flight::request()->getHeader("Authentication");
    Flight::auth_middleware()->verifyToken($token);

    $user = Flight::auth_middleware()->getCurrentUser($token);
    if (!in_array($user['role'], [Roles::ADMIN, Roles::CUSTOMER])) {
        Flight::halt(403, 'Forbidden');
    }

    Flight::productController()->getProducts();
});


/**
 * @OA\Delete(
 *     path="/users/{id}",
 *     summary="Delete a user by ID (Admin only)",
 *     tags={"Users"},
 *     security={{"Bearer":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="User deleted"),
 *     @OA\Response(response=403, description="Forbidden"),
 *     @OA\Response(response=404, description="User not found")
 * )
 */
Flight::route('DELETE /users/@id', function ($id) {
    $token = Flight::request()->getHeader("Authentication");
    Flight::auth_middleware()->verifyToken($token);
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN); //  Only ADMIN

    Flight::userController()->deleteUser($id);
});
