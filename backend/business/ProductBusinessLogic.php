<?php
require_once __DIR__ . '/../service/ProductService.php';

class ProductBusinessLogic {
    private $productService;

    public function __construct() {
        $this->productService = new ProductService();
    }

    public function getAllProducts() {
        return $this->productService->getAll();
    }

    public function getProductById($id) {
        return $this->productService->getById($id);
    }

    public function getProductsByBrand($brand) {
        return $this->productService->getProductsByBrand($brand);
    }

    public function getProductsUnderPrice($price) {
        return $this->productService->getProductsUnderPrice($price);
    }

    public function searchProducts($term) {
        return $this->productService->searchProducts($term);
    }
}
