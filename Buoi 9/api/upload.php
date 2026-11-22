<?php
require __DIR__ . '/common.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    fail(405, 'Chỉ chấp nhận POST.');
}

$id = toInt($_POST['id'] ?? null);
$file = $_FILES['image'] ?? [];
if (!$id || $id < 1 || ($file['error'] ?? null) !== UPLOAD_ERR_OK) {
    fail(400, 'Cần mã sản phẩm và ảnh tải lên hợp lệ (tối đa 2 MB).');
}
$tmp = $file['tmp_name'];
if (!is_uploaded_file($tmp) || filesize($tmp) > 2 * 1024 * 1024) {
    fail(400, 'Ảnh phải có dung lượng tối đa 2 MB.');
}
$types = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
$mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);
$info = @getimagesize($tmp);
if (!isset($types[$mime]) || ($info['mime'] ?? null) !== $mime) {
    fail(400, 'Chỉ chấp nhận ảnh JPG, JPEG hoặc PNG.');
}

$root = dirname(__DIR__);
$saved = null;
try {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare('SELECT image FROM products WHERE id = ? FOR UPDATE');
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    if (!$product) {
        $pdo->rollBack();
        fail(404, 'Sản phẩm không tồn tại.');
    }
    $dir = $root . '/uploads';
    if (!is_dir($dir) && !@mkdir($dir, 0755) && !is_dir($dir)) {
        throw new RuntimeException();
    }
    $path = 'uploads/' . bin2hex(random_bytes(16)) . '.' . $types[$mime];
    if (!move_uploaded_file($tmp, $root . '/' . $path)) {
        throw new RuntimeException();
    }
    $saved = $root . '/' . $path;
    $stmt = $pdo->prepare('UPDATE products SET image = ? WHERE id = ?');
    $stmt->execute([$path, $id]);
    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
        if ($saved !== null) {
            @unlink($saved);
        }
    }
    fail(500, 'Không lưu được ảnh. Hãy thử lại.');
}

if (preg_match('~^uploads/[a-f0-9]{32}\.(jpg|png)$~D', $product['image'] ?? '')) {
    @unlink($root . '/' . $product['image']);
}
respond(200, ['success' => true, 'message' => 'Đã lưu ảnh.', 'image' => $path]);
