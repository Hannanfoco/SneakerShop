<?php
require_once __DIR__ . '/../controller/UserController.php';

Flight::register('userController', 'UserController');

/**
 * @OA\Get(
 *     path="/users",
 *     summary="Get user by ID or email (Admin only)",
 *     tags={"Users"},
 *     security={{"Bearer":{}}},
 *     @OA\Parameter(name="id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="email", in="query", @OA\Schema(type="string")),
 *     @OA\Response(response=200, description="User retrieved"),
 *     @OA\Response(response=403, description="Forbidden")
 * )
 */
Flight::route('GET /users', function () {
    $token = Flight::request()->getHeader("Authentication");
    Flight::auth_middleware()->verifyToken($token);
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN); //  Only ADMIN

    $id = Flight::request()->query->getData()['id'] ?? null;
$email = Flight::request()->query->getData()['email'] ?? null;

if ($id || $email) {
    Flight::userController()->getUser();
} else {
    Flight::userController()->getAllUsers();
}

});

/**
 * @OA\Post(
 *     path="/users",
 *     summary="Register or login a user",
 *     tags={"Users"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"username", "email", "password"},
 *             @OA\Property(property="username", type="string"),
 *             @OA\Property(property="email", type="string"),
 *             @OA\Property(property="password", type="string")
 *         )
 *     ),
 *     @OA\Response(response=200, description="User registered or logged in")
 * )
 */
Flight::route('POST /users', function () {
    //  No auth – open for register/login
    Flight::userController()->registerOrLogin();
});

/**
 * @OA\Put(
 *     path="/users",
 *     summary="Update user details (Admin only)",
 *     tags={"Users"},
 *     security={{"Bearer":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="username", type="string"),
 *             @OA\Property(property="email", type="string"),
 *             @OA\Property(property="password", type="string")
 *         )
 *     ),
 *     @OA\Response(response=200, description="User updated"),
 *     @OA\Response(response=403, description="Forbidden")
 * )
 */
Flight::route('PUT /users', function () {
    $token = Flight::request()->getHeader("Authentication");
    Flight::auth_middleware()->verifyToken($token);
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN); // Only ADMIN

    Flight::userController()->updateUser();
});

/**
 * @OA\Delete(
 *     path="/users",
 *     summary="Delete a user by ID (Admin only)",
 *     tags={"Users"},
 *     security={{"Bearer":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="User deleted"),
 *     @OA\Response(response=403, description="Forbidden")
 * )
 */
Flight::route('DELETE /users', function () {
    $token = Flight::request()->getHeader("Authentication");
    Flight::auth_middleware()->verifyToken($token);
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN); //  Only ADMIN

    Flight::userController()->deleteUser();
});
