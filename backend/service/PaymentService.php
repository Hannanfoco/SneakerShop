<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/PaymentDao.php';

class PaymentService extends BaseService {
    protected $paymentDao;

    public function __construct() {
        $this->paymentDao = new PaymentDao();
        parent::__construct($this->paymentDao);
    }

    /**
     * Create a payment for a user
     */
    public function createPayment($userId, $amount, $status = 'pending'): mixed {
        $this->validateRequiredFields(
            ['user_id' => $userId, 'amount' => $amount, 'payment_status' => $status],
            ['user_id', 'amount', 'payment_status']
        );

        return $this->paymentDao->insert([
            'user_id' => $userId,
            'amount' => $amount,
            'payment_status' => $status
        ]);
    }
}
