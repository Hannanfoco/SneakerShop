<?php
require_once 'BaseDao.php';

class UserDao extends BaseDao {
    public function __construct() {
        parent::__construct('users');
    }

    public function getByEmail($email) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function getStats() {
        $stmt = $this->connection->query("
            SELECT 
                COUNT(*) AS total_users,
                SUM(CASE WHEN role = 'customer' THEN 1 ELSE 0 END) AS active_users,
                SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) AS new_signups
            FROM users
        ");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}


?>



