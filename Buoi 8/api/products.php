<?php
// Câu 2: API quản lý sản phẩm (GET, POST, PUT, DELETE)

header('Content-Type: application/json; charset=utf-8');

// Cấu hình kết nối — sửa lại cho đúng máy của bạn
const DB_HOST = 'localhost';
const DB_NAME = 'shop_db';
const DB_USER = 'root';
const DB_PASS = '';

/** Gửi phản hồi JSON và kết thúc. */
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

/** Chuyển giá trị sang số nguyên; trả về null nếu không phải số nguyên. */
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

/** Đọc JSON từ body và kiểm tra dữ liệu; trả về [name, price, stock]. */
function readProductInput(): array
{
    $data = json_decode(file_get_contents('php://input'), true);
    if (!is_array($data)) {
        fail(400, 'Dữ liệu gửi lên không phải JSON hợp lệ.');
    }

    $name = isset($data['name']) && is_string($data['name']) ? trim($data['name']) : '';
    $len  = mb_strlen($name, 'UTF-8');
    if ($len < 1 || $len > 100) {
        fail(400, 'Tên sản phẩm phải có từ 1 đến 100 ký tự.');
    }

    $price = toInt($data['price'] ?? null);
    if ($price === null || $price <= 0) {
        fail(400, 'Đơn giá phải là số nguyên lớn hơn 0.');
    }

    $stock = toInt($data['stock'] ?? null);
    if ($stock === null || $stock < 0) {
        fail(400, 'Số lượng tồn phải là số nguyên không âm.');
    }

    return [$name, $price, $stock];
}

/** Lấy id từ URL (?id=...). */
function readId(): int
{
    $id = toInt($_GET['id'] ?? null);
    if ($id === null || $id <= 0) {
        fail(400, 'Thiếu hoặc sai mã sản phẩm (id) trên URL.');
    }
    return $id;
}

function productExists(PDO $pdo, int $id): bool
{
    $stmt = $pdo->prepare('SELECT 1 FROM products WHERE id = ?');
    $stmt->execute([$id]);
    return (bool) $stmt->fetchColumn();
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

try {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $rows = $pdo->query('SELECT id, name, price, stock FROM products ORDER BY id')->fetchAll();
            respond(200, ['success' => true, 'data' => $rows]);

        case 'POST':
            [$name, $price, $stock] = readProductInput();
            $stmt = $pdo->prepare('INSERT INTO products (name, price, stock) VALUES (?, ?, ?)');
            $stmt->execute([$name, $price, $stock]);
            respond(201, [
                'success' => true,
                'message' => 'Đã thêm sản phẩm.',
                'id'      => (int) $pdo->lastInsertId(),
            ]);

        case 'PUT':
            $id = readId();
            [$name, $price, $stock] = readProductInput();
            if (!productExists($pdo, $id)) {
                fail(404, 'Sản phẩm không tồn tại.');
            }
            $stmt = $pdo->prepare('UPDATE products SET name = ?, price = ?, stock = ? WHERE id = ?');
            $stmt->execute([$name, $price, $stock, $id]);
            respond(200, ['success' => true, 'message' => 'Đã cập nhật sản phẩm.']);

        case 'DELETE':
            $id = readId();
            $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
            $stmt->execute([$id]);
            if ($stmt->rowCount() === 0) {
                fail(404, 'Sản phẩm không tồn tại.');
            }
            respond(200, ['success' => true, 'message' => 'Đã xóa sản phẩm.']);

        default:
            fail(405, 'Phương thức không được hỗ trợ.');
    }
} catch (PDOException $e) {
    fail(500, 'Lỗi cơ sở dữ liệu.');
}
