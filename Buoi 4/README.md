# Buổi 4 - Quản lý sản phẩm giỏ hàng (PHP + MySQL)

Bài thực hành PHP + MySQL: quản lý **sản phẩm** cho hệ thống giỏ hàng, gồm xem danh sách, thêm, sửa, xóa và kiểm tra dữ liệu đầu vào. Đây là bài quản lý sản phẩm theo đề, **không phải** chức năng thêm hàng vào giỏ/thanh toán.

## Chạy với XAMPP

1. Bật **Apache** và **MySQL** trong XAMPP.
2. Mở `http://localhost/phpmyadmin`, chọn **Import**, nhập file `database.sql` để tạo database `shopping_cart`, bảng `products` và 5 sản phẩm mẫu.
3. Chép thư mục `app` của bài vào `C:\xampp\htdocs\shopping_cart\app`.
4. Vào `http://localhost/shopping_cart/app/` trên trình duyệt.
5. Nếu MySQL dùng tài khoản/mật khẩu khác, sửa cấu hình trong `app/common/dbConnect.php` hoặc đặt các biến môi trường `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`.

Mặc định: host `127.0.0.1`, port `3306`, database `shopping_cart`, tài khoản `root`, mật khẩu rỗng (XAMPP mặc định).

## Các file chính

```text
app/
├── common/
│   ├── dbConnect.php         # Kết nối MySQL bằng PDO
│   └── functions.php         # Kiểm tra dữ liệu, escaping, CSRF
├── model/
│   └── product.php           # 5 hàm truy vấn CRUD theo đề
├── view/
│   ├── header.php
│   └── footer.php
├── assets/
│   └── style.css
├── index.php                 # Chuyển đến danh sách
├── product_list.php
├── product_add.php
├── product_edit.php
└── product_delete.php
```

## Kiểm thử nhanh

- Danh sách ban đầu có tối thiểu 5 sản phẩm sau khi import SQL.
- Thêm sản phẩm hợp lệ, sau đó sửa tên/giá/số lượng.
- Không được thêm hoặc sửa với tên trống, giá bằng/nhỏ hơn 0, hoặc số lượng âm.
- Xóa có trang xác nhận và chỉ thực hiện bằng POST.
- Truy cập `product_edit.php?id=999999` hoặc `product_delete.php?id=999999` hiển thị thông báo không tìm thấy.
- Nếu tắt MySQL, trang hiển thị thông báo lỗi kết nối thay vì lộ thông tin đăng nhập.

Các truy vấn dùng prepared statements; form sửa/thêm/xóa có CSRF token và xác thực ở server.
