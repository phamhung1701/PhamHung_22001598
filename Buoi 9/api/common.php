<?php

header('Content-Type: application/json; charset=utf-8');

const DB_HOST = 'localhost';
const DB_NAME = 'shop_db';
const DB_USER = 'root';
const DB_PASS = '';

function respond(int $status, array $body): void
{
    http_response_code($status);
    echo json_encode($body, JSON_UNESCAPED_UNICODE);
    exit;
}

function fail(int $status, string $message): void
{
    respond($status, ['success' => false, 'message' => $message]);
}

function toInt($value): ?int
{
    if (is_int($value)) {
        return $value;
    }
    if (is_string($value) && preg_match('/^\s*-?\d+\s*$/', $value)) {
        return (int) trim($value);
    }
    return null;
}

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    fail(500, 'Không kết nối được cơ sở dữ liệu.');
}
