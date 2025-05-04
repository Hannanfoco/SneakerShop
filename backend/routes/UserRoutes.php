<?php
require_once __DIR__ . '/../controller/UserController.php';

Flight::register('userController', 'UserController');

/**
 * @OA\Get(
 *     path="/users",
 *     summary="Get user by ID or email",
 *     tags={"Users"},
 *     @OA\Parameter(name="id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="email", in="query", @OA\Schema(type="string")),
 *     @OA\Response(response=200, description="User retrieved")
 * )
 */
Flight::route('GET /users', function () {
    Flight::userController()->getUser();
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
    Flight::userController()->registerOrLogin();
});

/**
 * @OA\Put(
 *     path="/users",
 *     summary="Update user details",
 *     tags={"Users"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="username", type="string"),
 *             @OA\Property(property="email", type="string"),
 *             @OA\Property(property="password", type="string")
 *         )
 *     ),
 *     @OA\Response(response=200, description="User updated")
 * )
 */
Flight::route('PUT /users', function () {
    Flight::userController()->updateUser();
});

/**
 * @OA\Delete(
 *     path="/users",
 *     summary="Delete a user by ID",
 *     tags={"Users"},
 *     @OA\Parameter(
 *         name="id",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="User deleted")
 * )
 */
Flight::route('DELETE /users', function () {
    Flight::userController()->deleteUser();
});
