<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/ProductDao.php';

class ProductService extends BaseService {
    protected $productDao;

    public function __construct() {
        $this->productDao = new ProductDao();
        parent::__construct($this->productDao);
    }

    /**
     * Get products by brand (for filtering)
     */
    public function getProductsByBrand($brand): mixed {
        return $this->productDao->getByBrand($brand);
    }

    /**
     * Get products under a certain price (for filtering)
     */
    public function getProductsUnderPrice($price): mixed {
        return $this->productDao->getUnderPrice($price);
    }

    // getAll() and getById() are inherited from BaseService

    public function searchProducts($term): mixed {
        return $this->productDao->search($term);
    }

}
