<?php
require_once __DIR__ . '/../controller/OrderController.php';

Flight::register('orderController', 'OrderController');

/**
 * @OA\Get(
 *     path="/orders",
 *     summary="Get all orders for the authenticated user",
 *     tags={"Orders"},
 *     @OA\Response(response=200, description="User's order history")
 * )
 */
Flight::route('GET /orders', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]); // ✅ Both roles

    $user = Flight::get('user');
    Flight::orderController()->getOrders($user->id);
});

/**
 * @OA\Post(
 *     path="/orders/checkout",
 *     summary="Checkout the cart and create an order",
 *     tags={"Orders"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"total_price"},
 *             @OA\Property(property="total_price", type="number", format="float")
 *         )
 *     ),
 *     @OA\Response(response=201, description="Order placed")
 * )
 */
Flight::route('POST /orders/checkout', function(){
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]); // ✅ Both roles

    $user = Flight::get('user');
    $data = Flight::request()->data->getData();
    $data['user_id'] = $user->id; // Trust from token

    Flight::orderController()->checkout($data);
});
