<?php
require_once '../service/PaymentService.php';

header(header: 'Content-Type: application/json');

$paymentService = new PaymentService();
$method = $_SERVER['REQUEST_METHOD'];





if ($method === 'POST') {
    $data = json_decode(json: file_get_contents(filename: 'php://input'), associative: true);

    if (!isset($data['user_id'], $data['amount'], $data['payment_status'])) {
        http_response_code(response_code: 400);
        echo json_encode(value: ['error' => 'Missing fields']);
        exit;
    }

    $payment = $paymentService->createPayment(
        userId: $data['user_id'],
        amount: $data['amount'],
        status: $data['payment_status']
    );

    echo json_encode(value: [
        'success' => true,
        'message' => 'Payment created',
        'payment' => $payment
    ]);
} else {
    http_response_code(response_code: 405);
    echo json_encode(value: ['error' => 'Method not allowed']);
}
