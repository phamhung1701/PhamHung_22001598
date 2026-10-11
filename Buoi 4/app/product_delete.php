<?php
require_once __DIR__ . '/common/functions.php';
require_once __DIR__ . '/model/product.php';

$pageTitle = 'Xóa sản phẩm';
$isPost = $_SERVER['REQUEST_METHOD'] === 'POST';
$id = readProductId($isPost ? ($_POST['id'] ?? null) : ($_GET['id'] ?? null));
$errors = [];
$product = null;

if ($id === null) {
    $errors[] = 'ID sản phẩm không hợp lệ.';
} else {
    try {
        $product = getProductById($id);
        if ($product === null) {
            $errors[] = 'Sản phẩm không tồn tại hoặc đã được xóa.';
        }
    } catch (Throwable $exception) {
        error_log($exception->getMessage());
        $errors[] = 'Không thể tải sản phẩm. Kiểm tra MySQL.';
    }
}

if ($isPost && $product !== null) {
    if (!isValidCsrfToken($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Phiên làm việc không hợp lệ. Hãy tải lại trang và thử lại.';
    } else {
        try {
            deleteProduct($id);
            header('Location: product_list.php?status=deleted');
            exit;
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            $errors[] = 'Không thể xóa sản phẩm. Kiểm tra MySQL.';
        }
    }
}
require __DIR__ . '/view/header.php';
?>
<h2>Xác nhận xóa sản phẩm</h2>
<?php if ($errors): ?>
    <div class="notice error" role="alert"><ul>
        <?php foreach ($errors as $error): ?><li><?= escapeHtml($error) ?></li><?php endforeach; ?>
    </ul></div>
<?php endif; ?>
<?php if ($product !== null): ?>
    <p>Bạn có chắc muốn xóa sản phẩm <strong><?= escapeHtml($product['name']) ?></strong> (ID: <?= escapeHtml($id) ?>)?</p>
    <p>Thao tác này không thể hoàn tác.</p>
    <form method="post" action="product_delete.php" class="inline-form">
        <input type="hidden" name="csrf_token" value="<?= escapeHtml(getCsrfToken()) ?>">
        <input type="hidden" name="id" value="<?= escapeHtml($id) ?>">
        <button type="submit" class="danger">Xác nhận xóa</button>
        <a class="button secondary" href="product_list.php">Hủy</a>
    </form>
<?php else: ?>
    <p><a href="product_list.php">Quay lại danh sách sản phẩm</a></p>
<?php endif; ?>
<?php require __DIR__ . '/view/footer.php'; ?>
