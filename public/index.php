<?php

declare(strict_types=1);

$version = substr(getenv('APP_VERSION') ?: 'local', 0, 7);
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demo giao hàng IS207</title>
    <style>
        :root { font-family: system-ui, sans-serif; color: #172236; background: #f5f7fb; }
        body { margin: 0; padding: 28px 16px; }
        main { max-width: 560px; margin: 5vh auto; padding: 28px; border-radius: 18px; background: white; box-shadow: 0 12px 35px #17223612; }
        h1 { margin-top: 0; font-size: 1.8rem; }
        p { line-height: 1.5; }
        label { display: block; margin: 18px 0 6px; font-weight: 600; }
        input, select, button { box-sizing: border-box; width: 100%; padding: 12px; font: inherit; border-radius: 8px; }
        input, select { border: 1px solid #a5b1c2; }
        button { margin-top: 22px; border: 0; background: #2257cc; color: white; font-weight: 700; cursor: pointer; }
        button:disabled { opacity: .6; cursor: wait; }
        output { display: block; min-height: 52px; margin-top: 24px; padding: 14px; border-radius: 8px; background: #edf3ff; }
        small { display: block; margin-top: 20px; color: #5b6678; }
    </style>
</head>
<body>
<main>
    <h1>Ước tính phí giao hàng</h1>
    <p>Demo Docker và CI cho website bán đồ gia dụng IS207. Miễn phí giao hàng khi đơn từ 500.000 ₫.</p>
    <form id="quote-form">
        <label for="subtotal">Tạm tính đơn hàng (VND)</label>
        <input id="subtotal" name="subtotal" type="number" min="0" max="100000000" step="1" value="450000" required>
        <label for="area">Khu vực giao hàng</label>
        <select id="area" name="area">
            <option value="inner">Nội thành</option>
            <option value="outer">Ngoại thành</option>
        </select>
        <button type="submit">Tính phí</button>
    </form>
    <output id="result" aria-live="polite">Nhập đơn hàng rồi nhấn Tính phí.</output>
    <small>Phiên bản triển khai: <?= htmlspecialchars($version, ENT_QUOTES, 'UTF-8') ?></small>
</main>
<script>
    const form = document.getElementById('quote-form');
    const button = form.querySelector('button');
    const result = document.getElementById('result');
    const money = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        button.disabled = true;
        result.textContent = 'Đang tính...';

        try {
            const params = new URLSearchParams(new FormData(form));
            const response = await fetch(`/api/quote.php?${params}`);
            const payload = await response.json();
            if (!response.ok || !payload.success) throw new Error(payload.message || 'Không thể tính phí.');
            const quote = payload.data;
            result.textContent = `Phí giao hàng: ${money.format(quote.shipping_fee)} · Tổng thanh toán: ${money.format(quote.total)}`;
        } catch (error) {
            result.textContent = error.message;
        } finally {
            button.disabled = false;
        }
    });
</script>
</body>
</html>
