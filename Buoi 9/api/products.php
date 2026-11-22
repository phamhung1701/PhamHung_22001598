<?php
require __DIR__ . '/common.php';

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
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $page = $_GET['page'] ?? '1';
            if (!is_string($page) || !ctype_digit($page) || (int) $page < 1 || ($_GET['limit'] ?? '5') !== '5') {
                fail(400, 'Trang phải là số nguyên dương và giới hạn phải bằng 5.');
            }
            $pdo->beginTransaction();
            $total = (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
            $pages = max(1, (int) ceil($total / 5));
            $page = min((int) $page, $pages);
            $stmt = $pdo->prepare('SELECT id, name, price, stock, image FROM products ORDER BY id DESC LIMIT 5 OFFSET ?');
            $stmt->bindValue(1, ($page - 1) * 5, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll();
            $pdo->commit();
            respond(200, ['success' => true, 'data' => $rows, 'pagination' => [
                'page' => $page, 'limit' => 5, 'total' => $total, 'total_pages' => $pages
            ]]);

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
