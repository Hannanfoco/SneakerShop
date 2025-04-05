<?php
require_once 'BaseDao.php';

class FavouriteDao extends BaseDao {
    public function __construct() {
        parent::__construct('favourites');
    }

    public function getUserFavourites($userId) {
        $stmt = $this->connection->prepare("SELECT * FROM favourites WHERE user_id = :uid");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll();
    }
}
?>
