<?php
require_once __DIR__ . '/../controller/PaymentController.php';

Flight::register('paymentController', 'PaymentController');

/**
 * @OA\Post(
 *     path="/payment",
 *     summary="Create a new payment record",
 *     tags={"Payments"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "amount"},
 *             @OA\Property(property="user_id", type="integer"),
 *             @OA\Property(property="amount", type="number", format="float"),
 *             @OA\Property(property="payment_status", type="string", example="pending")
 *         )
 *     ),
 *     @OA\Response(response=201, description="Payment created")
 * )
 */
Flight::route('POST /payment', function (): void {
    $token = Flight::request()->getHeader("Authentication");
    Flight::auth_middleware()->verifyToken($token);
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]); // ✅ Allow both

    Flight::paymentController()->createPayment();
});

/**
 * @OA\Get(
 *     path="/payment/{id}",
 *     summary="Get payment by ID",
 *     tags={"Payments"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response=200, description="Payment retrieved"),
 *     @OA\Response(response=404, description="Payment not found")
 * )
 */
Flight::route('GET /payment/@id', function ($id): void {
    $token = Flight::request()->getHeader("Authentication");
    Flight::auth_middleware()->verifyToken($token);
    Flight::auth_middleware()->authorizeRoles([Roles::CUSTOMER, Roles::ADMIN]); // ✅ Allow both

    Flight::paymentController()->getPaymentById($id);
});
