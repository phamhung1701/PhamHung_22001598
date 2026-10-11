<?php
/** Create and reuse one PDO connection for the current request. */
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
    $password = $password === false ? '' : $password;

    // Do not allow untrusted environment values to change DSN structure.
    if (!ctype_digit((string) $port) || (int) $port < 1 || (int) $port > 65535
        || !preg_match('/^[a-zA-Z0-9_.-]+$/', $host)
        || !preg_match('/^[a-zA-Z0-9_]+$/', $database)) {
        throw new RuntimeException('Cấu hình database không hợp lệ.');
    }

    try {
        $connection = new PDO(
            "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4",
            $username,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
        return $connection;
    } catch (PDOException $exception) {
        error_log('MySQL connection failed: ' . $exception->getMessage());
        throw new RuntimeException(
            'Không thể kết nối MySQL. Kiểm tra MySQL đã chạy và cấu hình trong common/dbConnect.php.'
        );
    }
}
