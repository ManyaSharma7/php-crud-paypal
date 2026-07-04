<?php
/**
 * Called via AJAX from the "Buy Now" button.
 * Creates a PayPal order for the given product/price and returns the order ID.
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json');

$input      = json_decode(file_get_contents('php://input'), true);
$productId  = (int)($input['product_id'] ?? 0);

if (!$productId) {
    http_response_code(400);
    echo json_encode(['error' => 'product_id is required']);
    exit;
}

// Look up the real price server-side — never trust a price sent from the browser
$stmt = getDB()->prepare('SELECT id, name, price FROM products WHERE id = ?');
$stmt->execute([$productId]);
$product = $stmt->fetch();

if (!$product) {
    http_response_code(404);
    echo json_encode(['error' => 'Product not found']);
    exit;
}

try {
    $result = paypalRequest('POST', '/v2/checkout/orders', [
        'intent' => 'CAPTURE',
        'purchase_units' => [[
            'reference_id' => (string)$product['id'],
            'description'  => $product['name'],
            'amount' => [
                'currency_code' => 'USD',
                'value'          => number_format((float)$product['price'], 2, '.', ''),
            ],
        ]],
    ]);

    if ($result['status'] >= 200 && $result['status'] < 300) {
        echo json_encode(['id' => $result['body']['id']]);
    } else {
        http_response_code(502);
        echo json_encode(['error' => 'PayPal order creation failed', 'details' => $result['body']]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
