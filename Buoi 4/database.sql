-- Bài thực hành 4: Quản lý sản phẩm cho hệ thống giỏ hàng
-- Import vào phpMyAdmin hoặc chạy bằng mysql client.
CREATE DATABASE IF NOT EXISTS shopping_cart CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shopping_cart;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu mẫu: thực hiện INSERT này một lần khi khởi tạo database.
INSERT INTO products (name, price, quantity) VALUES
    ('Bàn phím cơ', 650000.00, 12),
    ('Chuột không dây', 250000.00, 25),
    ('Tai nghe', 450000.00, 18),
    ('USB 32GB', 120000.00, 40),
    ('Lót chuột', 80000.00, 30);
