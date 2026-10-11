<?php
/** Kết nối MySQL dùng chung cho các trang và model. */
function getDbConnection(): PDO
{
    static $connection = null;
    if ($connection instanceof PDO) {
        return $connection;
    }

    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $port = getenv('DB_PORT') ?: '3306';
    $database = getenv('DB_NAME') ?: 'shopping_cart';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASS');
    if ($password === false) {
        $password = '';
    }

    try {
        $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
        $connection = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $exception) {
        throw new RuntimeException(
            'Không thể kết nối MySQL. Hãy kiểm tra MySQL, database và cấu hình kết nối.',
            0,
            $exception
        );
    }

    return $connection;
}
