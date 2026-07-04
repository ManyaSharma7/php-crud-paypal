<?php
/**
 * Products REST API
 *   GET    /api/products.php          -> list all
 *   GET    /api/products.php?id=1     -> get one
 *   POST   /api/products.php          -> create   (JSON body)
 *   PUT    /api/products.php?id=1     -> update   (JSON body)
 *   DELETE /api/products.php?id=1     -> delete
 */

require_once __DIR__ . '/../db.php';

header('Content-Type: application/json');

$pdo    = getDB();
$method = $_SERVER['REQUEST_METHOD'];

// PUT/DELETE bodies don't populate $_GET automatically in all setups, so parse manually
parse_str($_SERVER['QUERY_STRING'] ?? '', $query);
$id = isset($query['id']) ? (int)$query['id'] : null;

switch ($method) {

    case 'GET':
        if ($id) {
            $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
            $stmt->execute([$id]);
            $row = $stmt->fetch();
            $row ? print(json_encode($row)) : respondError(404, 'Product not found');
        } else {
            $stmt = $pdo->query('SELECT * FROM products ORDER BY id DESC');
            echo json_encode($stmt->fetchAll());
        }
        break;

    case 'POST':
        $data = readJsonBody();
        $errors = validate($data);
        if ($errors) { respondError(422, implode(', ', $errors)); break; }

        $stmt = $pdo->prepare(
            'INSERT INTO products (name, description, price, stock) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$data['name'], $data['description'] ?? '', $data['price'], $data['stock'] ?? 0]);

        echo json_encode(['id' => $pdo->lastInsertId(), 'message' => 'Product created']);
        break;

    case 'PUT':
        if (!$id) { respondError(400, 'Missing id'); break; }
        $data = readJsonBody();
        $errors = validate($data);
        if ($errors) { respondError(422, implode(', ', $errors)); break; }

        $stmt = $pdo->prepare(
            'UPDATE products SET name = ?, description = ?, price = ?, stock = ? WHERE id = ?'
        );
        $stmt->execute([$data['name'], $data['description'] ?? '', $data['price'], $data['stock'] ?? 0, $id]);

        echo json_encode(['message' => 'Product updated']);
        break;

    case 'DELETE':
        if (!$id) { respondError(400, 'Missing id'); break; }
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode(['message' => 'Product deleted']);
        break;

    default:
        respondError(405, 'Method not allowed');
}

function readJsonBody(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function validate(array $data): array {
    $errors = [];
    if (empty($data['name'])) $errors[] = 'Name is required';
    if (!isset($data['price']) || !is_numeric($data['price'])) $errors[] = 'Valid price is required';
    return $errors;
}

function respondError(int $code, string $message): void {
    http_response_code($code);
    echo json_encode(['error' => $message]);
}
