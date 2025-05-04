<?php
require_once __DIR__ . '/../controller/OrderController.php';

Flight::register('orderController', 'OrderController');

/**
 * @OA\Get(
 *     path="/orders/{user_id}",
 *     summary="Get all orders for a specific user",
 *     tags={"Orders"},
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="User's order history")
 * )
 */
Flight::route('GET /orders/@user_id', function($user_id){
    Flight::orderController()->getOrders($user_id);
});

/**
 * @OA\Post(
 *     path="/orders/checkout",
 *     summary="Checkout the cart and create an order",
 *     tags={"Orders"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "total_price"},
 *             @OA\Property(property="user_id", type="integer"),
 *             @OA\Property(property="total_price", type="number", format="float")
 *         )
 *     ),
 *     @OA\Response(response=201, description="Order placed")
 * )
 */
Flight::route('POST /orders/checkout', function(){
    $data = Flight::request()->data->getData();
    Flight::orderController()->checkout($data);
});
