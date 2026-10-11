CREATE DATABASE IF NOT EXISTS shopping_cart
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE shopping_cart;

CREATE TABLE IF NOT EXISTS products (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dữ liệu mẫu (không nhân đôi nếu chạy lại script).
INSERT IGNORE INTO products (id, name, price, quantity) VALUES
    (1, 'Bút bi', 5000.00, 120),
    (2, 'Vở ô ly', 12000.00, 75),
    (3, 'Thước kẻ', 8000.00, 40),
    (4, 'Bút chì', 4000.00, 90),
    (5, 'Tẩy', 3000.00, 60);
