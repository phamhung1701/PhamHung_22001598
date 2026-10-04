-- BAI THUC HANH BUOI 3 - MYSQL
-- Ho ten: Phạm Hưng
-- Ma sinh vien: 22001598
-- File nop bai gom toan bo cau lenh cho ca 2 bai.
-- Chay tren MySQL voi bang cart_items va movies chua ton tai.
-- Khong xoa database hoac bang co san de tranh mat du lieu.

SET NAMES utf8mb4;

-- ============================================================
-- BAI 1: QUAN LY GIO HANG
-- ============================================================

CREATE DATABASE IF NOT EXISTS shopping_cart
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE shopping_cart;

CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT NOT NULL
);

-- Kiem tra cau truc bang.
DESCRIBE cart_items;

-- 1. Them it nhat 5 san pham.
INSERT INTO cart_items (name, price, quantity) VALUES
    ('Bàn phím', 650000.00, 1),
    ('Chuột không dây', 320000.00, 6),
    ('Tai nghe', 480000.00, 2),
    ('USB 64GB', 180000.00, 8),
    ('Sổ tay', 90000.00, 10);

-- 2. Hien thi toan bo san pham.
SELECT id, name, price, quantity
FROM cart_items
ORDER BY id;

-- 3. Hien thi san pham co gia lon hon 100000.
SELECT id, name, price, quantity
FROM cart_items
WHERE price > 100000
ORDER BY id;

-- 4. Hien thi san pham co so luong lon hon 5.
SELECT id, name, price, quantity
FROM cart_items
WHERE quantity > 5
ORDER BY id;

-- 5. Sap xep san pham theo gia giam dan.
SELECT id, name, price, quantity
FROM cart_items
ORDER BY price DESC, id ASC;

-- 6. Cap nhat gia cua mot san pham.
UPDATE cart_items
SET price = 700000.00
WHERE id = 1;

-- Kiem tra gia moi cua Ban phim.
SELECT id, name, price
FROM cart_items
WHERE id = 1;

-- 7. Cap nhat so luong cua mot san pham.
UPDATE cart_items
SET quantity = 7
WHERE id = 2;

-- Kiem tra so luong moi cua Chuot khong day.
SELECT id, name, quantity
FROM cart_items
WHERE id = 2;

-- 8. Xoa mot san pham (So tay).
DELETE FROM cart_items
WHERE id = 5;

-- Kiem tra san pham da xoa: truy van nay tra ve 0 dong.
SELECT id, name, price, quantity
FROM cart_items
WHERE id = 5;

-- 9. Hien thi ten san pham, gia, so luong va thanh tien.
SELECT
    name,
    price,
    quantity,
    price * quantity AS thanh_tien
FROM cart_items
ORDER BY id;

-- 10. Tinh tong tien cua toan bo gio hang.
-- Ket qua du kien sau cac thao tac tren: 5340000.00.
SELECT COALESCE(SUM(price * quantity), 0) AS tong_tien_gio_hang
FROM cart_items;

-- ============================================================
-- BAI 2: QUAN LY VE XEM PHIM
-- Bang movies duoc dat trong cung database shopping_cart.
-- ============================================================

CREATE TABLE movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    total_seats INT NOT NULL,
    available_seats INT NOT NULL
);

-- Kiem tra cau truc bang.
DESCRIBE movies;

-- 1. Them it nhat 5 bo phim.
INSERT INTO movies (title, price, total_seats, available_seats) VALUES
    ('Avengers', 100000.00, 100, 80),
    ('Avatar', 120000.00, 80, 45),
    ('Batman', 90000.00, 120, 100),
    ('Interstellar', 150000.00, 90, 50),
    ('Doraemon', 80000.00, 150, 130);

-- 2. Hien thi toan bo danh sach phim.
SELECT id, title, price, total_seats, available_seats
FROM movies
ORDER BY id;

-- 3. Hien thi phim co gia ve lon hon 100000.
SELECT id, title, price, total_seats, available_seats
FROM movies
WHERE price > 100000
ORDER BY id;

-- 4. Hien thi phim con nhieu hon 50 ghe.
SELECT id, title, price, total_seats, available_seats
FROM movies
WHERE available_seats > 50
ORDER BY id;

-- 5. Sap xep phim theo gia ve giam dan.
SELECT id, title, price, total_seats, available_seats
FROM movies
ORDER BY price DESC, id ASC;

-- 6. Cap nhat so ghe con lai cua mot phim.
UPDATE movies
SET available_seats = 60
WHERE id = 1;

-- Kiem tra so ghe con lai cua Avengers.
SELECT id, title, total_seats, available_seats
FROM movies
WHERE id = 1;

-- 7. Xoa mot phim (Doraemon).
DELETE FROM movies
WHERE id = 5;

-- Kiem tra phim da xoa: truy van nay tra ve 0 dong.
SELECT id, title, price, total_seats, available_seats
FROM movies
WHERE id = 5;

-- 8. Hien thi so ve da ban cua tung phim.
SELECT
    id,
    title,
    total_seats,
    available_seats,
    total_seats - available_seats AS so_ve_da_ban
FROM movies
ORDER BY id;

-- 9. Tinh doanh thu cua tung phim.
SELECT
    id,
    title,
    total_seats - available_seats AS so_ve_da_ban,
    price,
    (total_seats - available_seats) * price AS doanh_thu
FROM movies
ORDER BY id;

-- 10. Tinh tong doanh thu cua tat ca cac phim.
-- Ket qua du kien: 16000000.00.
SELECT
    COALESCE(SUM((total_seats - available_seats) * price), 0)
        AS tong_doanh_thu
FROM movies;

-- 11. Tim phim co so ve ban ra nhieu nhat.
-- Dung MAX va truy van con de tra ve tat ca phim dong hang.
-- Ket qua du kien: Avengers va Interstellar, moi phim ban 40 ve.
SELECT
    id,
    title,
    total_seats - available_seats AS so_ve_da_ban
FROM movies
WHERE total_seats - available_seats = (
    SELECT MAX(total_seats - available_seats)
    FROM movies
)
ORDER BY id;
