<?php
require_once __DIR__ . '/../business/PaymentBusinessLogic.php';


class PaymentController {
    private $paymentBusinessLogic;

    public function __construct() {
        $this->paymentBusinessLogic = new PaymentBusinessLogic();
    }

    public function createPayment(): void {
        $data = Flight::request()->data->getData();
        $userId = $data['user_id'];
        $amount = $data['amount'];
        $status = $data['payment_status'] ?? 'pending';

        $payment = $this->paymentBusinessLogic->createPayment($userId, $amount, $status);
        Flight::json($payment);
    }

    public function getPaymentById($id): void {
        $payment = $this->paymentBusinessLogic->getPaymentById($id);
        if ($payment) {
            Flight::json($payment);
        } else {
            Flight::halt(404, "Payment not found");
        }
    }
}
