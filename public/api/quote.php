<?php

declare(strict_types=1);

require __DIR__ . '/../../src/shipping.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$subtotal = $_GET['subtotal'] ?? null;
$area = $_GET['area'] ?? null;

if (!is_string($subtotal) || !ctype_digit($subtotal) || !is_string($area)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Nhập tạm tính VND và khu vực giao hàng.']);
    exit;
}

try {
    $quote = calculateShippingQuote((int) $subtotal, $area);
    echo json_encode(['success' => true, 'data' => $quote]);
} catch (InvalidArgumentException $exception) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => $exception->getMessage()]);
}
