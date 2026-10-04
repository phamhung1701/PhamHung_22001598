# Buổi 3 - Thực hành MySQL

**Sinh viên:** Phạm Hưng  
**Mã sinh viên:** 22001598

File nộp bài: `PhamHung_22001598_Buoi3.sql`.

File SQL chứa toàn bộ câu lệnh cho hai bài, đánh số theo đề:
- Bài 1: 10 yêu cầu quản lý giỏ hàng.
- Bài 2: 11 yêu cầu quản lý vé xem phim.

Database là `shopping_cart`, gồm hai bảng `cart_items` và `movies`. Đề không quy định database riêng cho bảng phim nên hai bảng được đặt chung trong database này.

## Cách chạy trong MySQL Workbench

1. Kết nối đến MySQL Server bằng tài khoản có quyền tạo database và bảng.
2. Mở file SQL bằng **File > Open SQL Script**.
3. Chạy toàn bộ file một lần trên database chưa có bảng `cart_items` và `movies`.
4. Xem kết quả các câu SELECT trong Result Grid. Sau mỗi UPDATE và DELETE đã có SELECT để kiểm tra thay đổi.

File không có DROP hoặc TRUNCATE. Nếu các bảng đã tồn tại, CREATE TABLE sẽ báo lỗi. Không chạy lại phần INSERT/UPDATE/DELETE trên dữ liệu cũ; các ID từ 1 đến 5 tương ứng dữ liệu tạo mới trong file.

## Kết quả dự kiến sau cập nhật và xóa

### Bài 1

| Sản phẩm | Giá (VND) | Số lượng | Thành tiền (VND) |
| --- | ---: | ---: | ---: |
| Bàn phím | 700.000 | 1 | 700.000 |
| Chuột không dây | 320.000 | 7 | 2.240.000 |
| Tai nghe | 480.000 | 2 | 960.000 |
| USB 64GB | 180.000 | 8 | 1.440.000 |

Tổng tiền giỏ hàng: **5.340.000 VND**. Sổ tay đã được xóa.

### Bài 2

| Phim | Tổng ghế | Ghế còn lại | Vé đã bán | Doanh thu (VND) |
| --- | ---: | ---: | ---: | ---: |
| Avengers | 100 | 60 | 40 | 4.000.000 |
| Avatar | 80 | 45 | 35 | 4.200.000 |
| Batman | 120 | 100 | 20 | 1.800.000 |
| Interstellar | 90 | 50 | 40 | 6.000.000 |

Tổng doanh thu: **16.000.000 VND**. Doraemon đã được xóa.

Phim bán nhiều vé nhất: **Avengers và Interstellar**, cùng 40 vé. Câu lệnh sử dụng MAX trả về cả hai phim đồng hạng.

Các kết quả trên là kết quả dự kiến từ dữ liệu mẫu. Chưa xác nhận bằng MySQL Server trong môi trường hiện tại.
