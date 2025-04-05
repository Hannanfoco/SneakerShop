<?php
require_once 'BaseDao.php';

class OrderDao extends BaseDao {
    public function __construct() {
        parent::__construct('orders');
    }

    public function getOrdersByUser($userId) {
        $stmt = $this->connection->prepare("SELECT * FROM orders WHERE user_id = :uid ORDER BY created_at DESC");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll();
    }
}
?>
