<?php

declare(strict_types=1);

require __DIR__ . '/../src/shipping.php';

/** Dừng pipeline ngay khi quy tắc tính phí cho kết quả sai. */
function expectSame(mixed $actual, mixed $expected, string $case): void
{
    if ($actual !== $expected) {
        fwrite(STDERR, "$case: expected $expected, got $actual\n");
        exit(1);
    }
}

expectSame(calculateShippingQuote(499_999, 'inner')['shipping_fee'], 20_000, 'Inner below threshold');
expectSame(calculateShippingQuote(499_999, 'outer')['total'], 534_999, 'Outer below threshold');
expectSame(calculateShippingQuote(500_000, 'inner')['shipping_fee'], 0, 'At threshold');
expectSame(calculateShippingQuote(800_000, 'outer')['shipping_fee'], 0, 'Above threshold');

foreach ([[-1, 'inner'], [100_000_001, 'inner'], [100_000, 'unknown']] as [$subtotal, $area]) {
    try {
        calculateShippingQuote($subtotal, $area);
        fwrite(STDERR, "Invalid input was accepted: $subtotal / $area\n");
        exit(1);
    } catch (InvalidArgumentException) {
        // Dữ liệu ngoài miền hợp lệ phải bị từ chối.
    }
}

echo "Shipping tests passed\n";
