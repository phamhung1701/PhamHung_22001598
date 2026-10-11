<?php
require_once __DIR__ . '/../common/dbConnect.php';

/** @return array<int, array<string, mixed>> */
function getAllProducts(): array
{
    $statement = getDbConnection()->query('SELECT id, name, price, quantity FROM products ORDER BY id ASC');
    return $statement->fetchAll();
}

/** @return array<string, mixed>|null */
function getProductById($id): ?array
{
    $statement = getDbConnection()->prepare('SELECT id, name, price, quantity FROM products WHERE id = :id');
    $statement->execute(['id' => $id]);
    $product = $statement->fetch();
    return $product === false ? null : $product;
}

function addProduct($name, $price, $quantity): bool
{
    $statement = getDbConnection()->prepare(
        'INSERT INTO products (name, price, quantity) VALUES (:name, :price, :quantity)'
    );
    return $statement->execute(['name' => $name, 'price' => $price, 'quantity' => $quantity]);
}

function updateProduct($id, $name, $price, $quantity): bool
{
    $statement = getDbConnection()->prepare(
        'UPDATE products SET name = :name, price = :price, quantity = :quantity WHERE id = :id'
    );
    return $statement->execute(['id' => $id, 'name' => $name, 'price' => $price, 'quantity' => $quantity]);
}

function deleteProduct($id): bool
{
    $statement = getDbConnection()->prepare('DELETE FROM products WHERE id = :id');
    return $statement->execute(['id' => $id]);
}
