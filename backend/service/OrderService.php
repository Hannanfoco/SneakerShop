<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/OrderDao.php';

class OrderService extends BaseService {
    protected $orderDao;

    public function __construct() {
        $this->orderDao = new OrderDao();
        parent::__construct($this->orderDao);
    }

    public function createOrder($userId, $totalPrice, $status = 'pending') {
        $this->validateRequiredFields(
            data: ['user_id' => $userId, 'total_price' => $totalPrice, 'order_status' => $status],
            fields: ['user_id', 'total_price', 'order_status']
        );

        return $this->orderDao->insert([
            'user_id' => $userId,
            'total_price' => $totalPrice,
            'order_status' => $status
        ]);
    }

    public function getOrdersByUser($userId) {
        return $this->orderDao->getOrdersByUser($userId);
    }
}
