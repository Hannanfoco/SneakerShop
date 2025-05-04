<?php
require_once __DIR__ . '/../service/PaymentService.php';

class PaymentBusinessLogic {
    private $paymentService;


    public function getPaymentById($id): mixed {
        return $this->paymentService->getById(id: $id);
    }


    public function __construct() {
        $this->paymentService = new PaymentService();
    }

    public function createPayment($userId, $amount, $status): mixed {
        if ($amount <= 0) {
            throw new Exception(message: "Amount must be greater than zero.");
        }

        return $this->paymentService->createPayment(userId: $userId, amount: $amount, status: $status);
    }
}
