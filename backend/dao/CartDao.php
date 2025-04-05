<?php
require_once 'BaseDao.php';

class CartDao extends BaseDao {
    public function __construct() {
        parent::__construct('cart');
    }

    public function getUserCart($userId) {
        $stmt = $this->connection->prepare("SELECT * FROM cart WHERE user_id = :uid");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll();
    }
}
?>
