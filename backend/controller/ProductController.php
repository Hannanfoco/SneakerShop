<?php
require_once __DIR__ . '/../business/ProductBusinessLogic.php';

class ProductController {
    private $logic;

    public function __construct() {
        $this->logic = new ProductBusinessLogic();
    }

    public function getProducts() {
        $query = Flight::request()->query;

        try {
            if (isset($query['id'])) {
                $product = $this->logic->getProductById($query['id']);
                Flight::json($product);
                return;
            }

            if (isset($query['search'])) {
                $products = $this->logic->searchProducts($query['search']);
            } elseif (isset($query['brand'])) {
                $products = $this->logic->getProductsByBrand($query['brand']);
            } elseif (isset($query['price'])) {
                $products = $this->logic->getProductsUnderPrice($query['price']);
            } else {
                $products = $this->logic->getAllProducts();
            }

            Flight::json($products);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }
}
