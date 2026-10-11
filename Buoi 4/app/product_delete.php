<?php
require_once __DIR__ . '/common/functions.php';
require_once __DIR__ . '/model/product.php';

$pageTitle = 'Xóa sản phẩm';
$id = positiveIdFromQuery();
$pageError = null;
$product = null;

if ($id === null) {
    $pageError = 'ID sản phẩm không hợp lệ.';
} else {
    try {
        $product = getProductById($id);
        if ($product === null) {
            $pageError = 'Không tìm thấy sản phẩm cần xóa.';
        }
    } catch (Throwable $exception) {
        $pageError = 'Không thể tải sản phẩm. Hãy kiểm tra kết nối MySQL.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $product !== null) {
    if (!validCsrfToken($_POST['csrf_token'] ?? null)) {
        $pageError = 'Phiên làm việc không hợp lệ. Hãy tải lại trang và thử lại.';
    } else {
        try {
            if (deleteProduct($id)) {
                header('Location: product_list.php?message=deleted', true, 303);
                exit;
            }
            $pageError = 'Sản phẩm không còn tồn tại.';
            $product = null;
        } catch (Throwable $exception) {
            $pageError = 'Không thể xóa sản phẩm. Hãy kiểm tra kết nối MySQL.';
        }
    }
}

require __DIR__ . '/view/header.php';
?>
<div class="form-layout">
    <h1>Xác nhận xóa sản phẩm</h1>
    <?php if ($pageError): ?><p class="notice error" role="alert"><?= e($pageError) ?></p><?php endif; ?>
    <?php if ($product !== null): ?>
        <p>Bạn có chắc muốn xóa <strong><?= e($product['name']) ?></strong> (ID: <?= e($product['id']) ?>)?</p>
        <p class="muted">Thao tác này không thể hoàn tác.</p>
        <form method="post" action="product_delete.php?id=<?= e($id) ?>" class="form-actions">
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <button type="submit" class="danger">Xác nhận xóa</button>
            <a class="button secondary" href="product_list.php">Hủy</a>
        </form>
    <?php else: ?>
        <a class="button secondary" href="product_list.php">Về danh sách</a>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/view/footer.php'; ?>
