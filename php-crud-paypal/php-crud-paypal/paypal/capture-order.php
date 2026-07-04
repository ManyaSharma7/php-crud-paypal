<?php
/**
 * Called via AJAX once the buyer approves the payment in the PayPal popup.
 * Captures the funds for the given order ID.
 */
require_once __DIR__ . '/config.php';

header('Content-Type: application/json');

$input   = json_decode(file_get_contents('php://input'), true);
$orderId = $input['order_id'] ?? '';

if (!$orderId) {
    http_response_code(400);
    echo json_encode(['error' => 'order_id is required']);
    exit;
}

try {
    $result = paypalRequest('POST', "/v2/checkout/orders/{$orderId}/capture");

    if ($result['status'] >= 200 && $result['status'] < 300) {
        // In a real app: mark the order as paid in your DB here,
        // decrement stock, send confirmation email, etc.
        echo json_encode([
            'status' => $result['body']['status'] ?? 'UNKNOWN',
            'details' => $result['body'],
        ]);
    } else {
        http_response_code(502);
        echo json_encode(['error' => 'Capture failed', 'details' => $result['body']]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
