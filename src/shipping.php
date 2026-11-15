<?php

declare(strict_types=1);

/** Tính phí giao hàng demo bằng số nguyên VND, không phụ thuộc cơ sở dữ liệu. */
function calculateShippingQuote(int $subtotal, string $area): array
{
    if ($subtotal < 0 || $subtotal > 100_000_000) {
        throw new InvalidArgumentException('Tạm tính phải từ 0 đến 100.000.000 VND.');
    }

    $fees = [
        'inner' => 20_000,
        'outer' => 35_000,
    ];

    if (!array_key_exists($area, $fees)) {
        throw new InvalidArgumentException('Khu vực giao hàng không hợp lệ.');
    }

    $freeShippingThreshold = 600_000;
    $shippingFee = $subtotal >= $freeShippingThreshold ? 0 : $fees[$area];

    return [
        'subtotal' => $subtotal,
        'area' => $area,
        'shipping_fee' => $shippingFee,
        'total' => $subtotal + $shippingFee,
        'free_shipping_threshold' => $freeShippingThreshold,
    ];
}
