<?php
require_once __DIR__ . '/../controller/ProductController.php';

Flight::register('productController', 'ProductController');

/**
 * @OA\Get(
 *     path="/products",
 *     summary="Get all products (with optional filters)",
 *     tags={"Products"},
 *     @OA\Parameter(name="id", in="query", @OA\Schema(type="integer")),
 *     @OA\Parameter(name="brand", in="query", @OA\Schema(type="string")),
 *     @OA\Parameter(name="price", in="query", @OA\Schema(type="number", format="float")),
 *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
 *     @OA\Response(response=200, description="Products list")
 * )
 */
Flight::route('GET /products', function () {
    Flight::productController()->getProducts();
});

