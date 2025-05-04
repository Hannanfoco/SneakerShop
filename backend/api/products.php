<?php

// Show full errors in browser and Postman
ini_set(option: 'display_errors', value: 1);
ini_set(option: 'display_startup_errors', value: 1);
error_reporting(error_level: E_ALL);

require_once '../service/ProductService.php';
header(header: 'Content-Type: application/json');

$productService = new ProductService();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    if (isset($_GET['id'])) {
        $product = $productService->getById($_GET['id']);
        echo json_encode($product);
        exit;
    }

    if (isset($_GET['search'])) {
        $products = $productService->searchProducts($_GET['search']);
    } elseif (isset($_GET['brand'])) {
        $products = $productService->getProductsByBrand($_GET['brand']);
    } elseif (isset($_GET['price'])) {
        $products = $productService->getProductsUnderPrice($_GET['price']);
    } else {
        $products = $productService->getAll();
    }

    echo json_encode($products);



} else {
    http_response_code(response_code: 405); // Method Not Allowed
    echo json_encode(value: ['error' => 'Only GET is allowed']);
}
