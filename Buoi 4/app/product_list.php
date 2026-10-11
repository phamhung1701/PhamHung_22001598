<?php
require_once __DIR__ . '/common/functions.php';
require_once __DIR__ . '/model/product.php';

$pageTitle = 'Danh sách sản phẩm';
$error = '';
$products = [];
try {
    $products = getAllProducts();
} catch (Throwable $exception) {
    error_log($exception->getMessage());
    $error = 'Không thể tải danh sách sản phẩm. Hãy kiểm tra kết nối MySQL.';
}

$messages = [
    'added' => 'Đã thêm sản phẩm thành công.',
    'updated' => 'Đã cập nhật sản phẩm thành công.',
    'deleted' => 'Đã xóa sản phẩm thành công.',
];
$status = isset($_GET['status']) && is_string($_GET['status']) ? $_GET['status'] : '';
require __DIR__ . '/view/header.php';
?>
<div class="page-heading">
    <h2>Danh sách sản phẩm</h2>
    <a class="button" href="product_add.php">+ Thêm sản phẩm</a>
</div>
<?php if (isset($messages[$status])): ?>
    <p class="notice success" role="status"><?= escapeHtml($messages[$status]) ?></p>
<?php endif; ?>
<?php if ($error !== ''): ?>
    <p class="notice error" role="alert"><?= escapeHtml($error) ?></p>
<?php else: ?>
    <div class="table-wrapper">
        <table>
            <thead>
            <tr><th>ID</th><th>Tên sản phẩm</th><th>Giá (VNĐ)</th><th>Số lượng</th><th>Chức năng</th></tr>
            </thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= escapeHtml($product['id']) ?></td>
                    <td><?= escapeHtml($product['name']) ?></td>
                    <td><?= escapeHtml(number_format((float) $product['price'], 2, ',', '.')) ?></td>
                    <td><?= escapeHtml($product['quantity']) ?></td>
                    <td class="actions">
                        <a href="product_edit.php?id=<?= urlencode((string) $product['id']) ?>">Sửa</a>
                        <a class="delete-link" href="product_delete.php?id=<?= urlencode((string) $product['id']) ?>">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (count($products) === 0): ?>
                <tr><td colspan="5" class="empty">Chưa có sản phẩm nào. Hãy thêm sản phẩm mới.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?php require __DIR__ . '/view/footer.php'; ?>
