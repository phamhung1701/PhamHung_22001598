CREATE DATABASE IF NOT EXISTS shop_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE shop_db;

CREATE TABLE IF NOT EXISTS products (
    id    INT          NOT NULL AUTO_INCREMENT,
    name  VARCHAR(100) NOT NULL,
    price INT          NOT NULL,
    stock INT          NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    CONSTRAINT chk_name  CHECK (CHAR_LENGTH(TRIM(name)) > 0),
    CONSTRAINT chk_price CHECK (price > 0),
    CONSTRAINT chk_stock CHECK (stock >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET @migration = IF(
    EXISTS (SELECT 1 FROM information_schema.columns
            WHERE table_schema = DATABASE() AND table_name = 'products' AND column_name = 'image'),
    'SELECT 1', 'ALTER TABLE products ADD COLUMN image VARCHAR(255) NULL'
);
PREPARE statement FROM @migration;
EXECUTE statement;
DEALLOCATE PREPARE statement;

INSERT INTO products (name, price, stock)
SELECT sample.name, sample.price, sample.stock
FROM (
    SELECT 'Bút bi' AS name, 5000 AS price, 20 AS stock
    UNION ALL SELECT 'Vở ô ly', 12000, 15
    UNION ALL SELECT 'Thước kẻ', 8000, 0
    UNION ALL SELECT 'Bút chì', 4000, 18
    UNION ALL SELECT 'Tẩy', 3000, 30
    UNION ALL SELECT 'Hộp bút', 35000, 8
    UNION ALL SELECT 'Sổ tay', 22000, 12
    UNION ALL SELECT 'Giấy nhớ', 9000, 25
    UNION ALL SELECT 'Kéo', 15000, 10
    UNION ALL SELECT 'Hồ dán', 6000, 16
    UNION ALL SELECT 'Bìa hồ sơ', 7000, 24
    UNION ALL SELECT 'Bút dạ', 11000, 14
) AS sample
WHERE NOT EXISTS (SELECT 1 FROM products WHERE products.name = sample.name);
