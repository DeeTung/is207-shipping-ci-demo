# Demo Docker + CI cho seminar CI/CD

Ứng dụng PHP nhỏ mô phỏng tính phí giao hàng cho đơn gia dụng. Đơn từ 500.000 VND được miễn phí giao hàng. Demo tách khỏi checkout và MySQL của dự án IS207.

## Phần thực hiện

1. **Docker tại máy:** build image, chạy container, mở website ở `http://localhost:8088` và thử tính phí.
2. **GitHub Actions:** khi đẩy code lên một repository GitHub, workflow tự chạy test PHP, build Docker image và kiểm tra API trong container trên máy chủ của GitHub.

Workflow hiện chỉ thực hiện **CI**. Chưa có bước tự động đưa image lên registry hoặc triển khai website lên server, nên khi trình bày cần gọi phần chạy tại máy là demo Docker, không gọi là CD tự động. CD có thể được giải thích bằng sơ đồ trong seminar.

## Chạy thử tại máy

Mở Docker Desktop và đợi Engine chạy. Mở PowerShell trong thư mục `C:\UIT\IS207\DEMO_SEMINAR`:

```powershell
docker build --target test -f Dockerfile .
docker build -t is207-shipping:local -f Dockerfile .
docker run --rm --name is207-shipping -p 8088:10000 is207-shipping:local
```

Giữ PowerShell mở khi xem website. Thử `http://localhost:8088` với đơn 450.000 VND nội thành: phí 20.000 VND; đơn 600.000 VND ngoại thành: phí 0 VND. `http://localhost:8088/health.php` trả `"status":"ok"`. Nhấn `Ctrl+C` để dừng. Cổng `10080` bị Edge/Chromium chặn với lỗi `ERR_UNSAFE_PORT`.

## Chuẩn bị GitHub trước buổi báo cáo

Thư mục này có sẵn workflow tại `.github/workflows/shipping-demo.yml`. Khi đưa **toàn bộ nội dung thư mục này vào gốc một repository GitHub** và push, job **Shipping demo CI with Docker / verify** sẽ chạy. Nếu dùng repository IS207 của nhóm, tạo nhánh `feature/...`, mở PR vào `develop` và chờ một thành viên khác review trước khi merge. Có thể giữ log Actions đã chạy để trình bày, không cần đợi pipeline chạy trực tiếp trong 5 phút trên lớp.

## Kịch bản trình bày dưới 5 phút

| Thời gian | Thao tác | Ý cần nói |
| --- | --- | --- |
| 0:00–1:15 | Mở `localhost:8088`, thử hai mức đơn hàng. | Docker container đang chạy website thật trên máy. |
| 1:15–2:00 | Chỉ Dockerfile và lệnh `docker run -p 8088:10000`. | Dockerfile tạo image; container chạy ứng dụng; cổng 8088 trên máy nối với cổng 10000 trong container. |
| 2:00–3:30 | Mở commit/PR và GitHub Actions, chỉ các bước test, build, smoke test màu xanh. | GitHub tự chạy CI trên runner riêng, không phụ thuộc IDE của người trình bày. |
| 3:30–4:00 | Nói một câu về bước tiếp theo trong sơ đồ seminar. | CD sẽ đưa bản đã kiểm tra lên môi trường triển khai; demo này dừng ở CI. |

API demo: `GET /api/quote.php?subtotal=450000&area=inner` trả phí 20.000 VND và tổng 470.000 VND. Dữ liệu sai trả HTTP 422. Demo không lưu đơn hàng hay xử lý thanh toán.
