<?php
require_once __DIR__ . '/../common/dbConnect.php';

/** Lấy toàn bộ sản phẩm. */
function getAllProducts(): array
{
    $statement = getDbConnection()->query('SELECT id, name, price, quantity FROM products ORDER BY id ASC');
    return $statement->fetchAll();
}

/** Lấy sản phẩm theo id; trả về null nếu không tồn tại. */
function getProductById(int $id): ?array
{
    $statement = getDbConnection()->prepare('SELECT id, name, price, quantity FROM products WHERE id = :id');
    $statement->execute(['id' => $id]);
    $product = $statement->fetch();
    return $product === false ? null : $product;
}

/** Thêm sản phẩm. */
function addProduct(string $name, float $price, int $quantity): bool
{
    $statement = getDbConnection()->prepare(
        'INSERT INTO products (name, price, quantity) VALUES (:name, :price, :quantity)'
    );
    return $statement->execute([
        'name' => $name,
        'price' => $price,
        'quantity' => $quantity,
    ]);
}

/** Cập nhật sản phẩm. */
function updateProduct(int $id, string $name, float $price, int $quantity): bool
{
    $statement = getDbConnection()->prepare(
        'UPDATE products SET name = :name, price = :price, quantity = :quantity WHERE id = :id'
    );
    return $statement->execute([
        'id' => $id,
        'name' => $name,
        'price' => $price,
        'quantity' => $quantity,
    ]);
}

/** Xóa theo id; false nếu không có sản phẩm nào bị xóa. */
function deleteProduct(int $id): bool
{
    $statement = getDbConnection()->prepare('DELETE FROM products WHERE id = :id');
    $statement->execute(['id' => $id]);
    return $statement->rowCount() > 0;
}
