<?php
require_once 'BaseDao.php';

class FavouriteDao extends BaseDao {
    public function __construct() {
        parent::__construct('favourites');
    }

    public function getUserFavourites($userId) {
        $stmt = $this->connection->prepare("
            SELECT 
                f.id AS favourite_id,
                f.user_id,
                f.product_id,
                p.name,
                p.price,
                p.image_url,
                p.description
            FROM favourites f
            JOIN products p ON f.product_id = p.id
            WHERE f.user_id = :uid
        ");
        
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
}
?>
