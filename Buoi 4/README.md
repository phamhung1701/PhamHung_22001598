# Bài thực hành 4 – Quản lý giỏ hàng (PHP + MySQL)

Bài làm theo đề thực hành: quản lý **sản phẩm** cho hệ thống giỏ hàng, không triển khai thao tác thêm sản phẩm vào giỏ hàng vì đề không yêu cầu.

## Chạy trên XAMPP (Windows)

1. Bật **Apache** và **MySQL** trong XAMPP Control Panel.
2. Mở `http://localhost/phpmyadmin`, chọn **Import** và nhập file `database.sql` để tạo database `shopping_cart`, bảng `products` và 5 dòng mẫu.
3. Sao chép thư mục `app` vào `C:\xampp\htdocs\shopping-cart` (tên thư mục đích là `shopping-cart`).
4. Truy cập `http://localhost/shopping-cart/`.
5. Mặc định kết nối MySQL tại `127.0.0.1:3306`, database `shopping_cart`, user `root`, password rỗng (cấu hình XAMPP thường dùng). Nếu khác, chỉnh trong `app/common/dbConnect.php` hoặc các biến môi trường `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`.

**Yêu cầu:** PHP 7.4+ với extension `pdo_mysql`; MySQL/MariaDB. `mbstring` là tùy chọn.

## Cấu trúc

```text
Buoi 4/
├── database.sql
├── README.md
└── app/
    ├── common/
    │   ├── dbConnect.php
    │   └── functions.php
    ├── model/
    │   └── product.php
    ├── view/
    │   ├── header.php
    │   └── footer.php
    ├── assets/
    │   └── styles.css
    ├── index.php
    ├── product_list.php
    ├── product_add.php
    ├── product_edit.php
    └── product_delete.php
```

## Chức năng theo đề

- `common/dbConnect.php`: PDO MySQL, dùng lại kết nối, thông báo khi kết nối thất bại.
- `model/product.php`: `getAllProducts`, `getProductById`, `addProduct`, `updateProduct`, `deleteProduct`.
- `product_list.php`: liệt kê ID, tên, giá, số lượng và liên kết Sửa/Xóa.
- `product_add.php`: form thêm với kiểm tra tên khác rỗng, giá > 0, số lượng >= 0.
- `product_edit.php`: load theo ID, sửa và kiểm tra đầu vào.
- `product_delete.php`: trang xác nhận trước khi xóa qua POST; báo lỗi ID không hợp lệ/không tồn tại.
- `view/header.php` và `view/footer.php`: giao diện HTML dùng chung.

Dữ liệu được gửi bằng POST để thêm/sửa/xóa. SQL sử dụng prepared statement; văn bản được escape trước khi hiển thị; form có CSRF token.

## Thử nhanh

1. Vào Danh sách: ban đầu có 5 sản phẩm từ file SQL.
2. Thêm sản phẩm hợp lệ, kiểm tra dữ liệu vừa lưu xuất hiện trong bảng.
3. Thử nhập tên trống, giá bằng 0, số lượng âm: form phải từ chối.
4. Sửa sản phẩm, kiểm tra tên/giá/số lượng được cập nhật.
5. Chọn Xóa, xác nhận rồi kiểm tra sản phẩm biến mất; nhấn Hủy thì không xóa.
6. Truy cập `product_edit.php?id=999999` hoặc `product_delete.php?id=999999`: báo không tồn tại.

Có thể kiểm tra cú pháp với `php -l` cho từng file `.php`. Bài cần một MySQL server để chạy thử toàn bộ thao tác CRUD.
