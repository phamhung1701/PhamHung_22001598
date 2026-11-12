-- Câu 1: Tạo cơ sở dữ liệu và bảng products
CREATE DATABASE IF NOT EXISTS shop_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE shop_db;

DROP TABLE IF EXISTS products;

CREATE TABLE products (
    id    INT          NOT NULL AUTO_INCREMENT,
    name  VARCHAR(100) NOT NULL,
    price INT          NOT NULL,
    stock INT          NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    CONSTRAINT chk_name  CHECK (CHAR_LENGTH(TRIM(name)) > 0),
    CONSTRAINT chk_price CHECK (price > 0),
    CONSTRAINT chk_stock CHECK (stock >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu mẫu
INSERT INTO products (name, price, stock) VALUES
    ('Bút bi',    5000, 20),
    ('Vở ô ly',  12000, 15),
    ('Thước kẻ',  8000,  0);
