<?php
require_once 'BaseDao.php';

class ProductDao extends BaseDao {
    public function __construct() {
        parent::__construct('products');
    }

    public function getByBrand($brand) {
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE name LIKE :brand");
        $stmt->execute([':brand' => '%' . $brand . '%']);
        return $stmt->fetchAll();
    }

    public function getUnderPrice($price) {
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE price <= :price");
        $stmt->execute([':price' => $price]);
        return $stmt->fetchAll();
    }
}
?>
