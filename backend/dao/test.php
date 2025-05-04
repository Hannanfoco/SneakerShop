<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../service/PaymentService.php';

$paymentService = new PaymentService();

// Test user + amount
$userId = 1;
$amount = 99.99;

// 1️⃣ Create new payment
try {
    $paymentId = $paymentService->createPayment($userId, $amount, 'completed');
    echo "✅ Payment recorded successfully! Payment ID: $paymentId<br>";
} catch (Exception $e) {
    echo "❌ Failed to record payment: " . $e->getMessage() . "<br>";
}
