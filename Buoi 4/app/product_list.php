<?php
require_once __DIR__ . '/common/functions.php';
require_once __DIR__ . '/model/product.php';

$pageTitle = 'Danh sách sản phẩm';
$products = [];
$error = null;
$messages = [
    'added' => 'Đã thêm sản phẩm thành công.',
    'updated' => 'Đã cập nhật sản phẩm thành công.',
    'deleted' => 'Đã xóa sản phẩm thành công.',
];
$message = $messages[$_GET['message'] ?? ''] ?? null;

try {
    $products = getAllProducts();
} catch (Throwable $exception) {
    $error = 'Không thể tải danh sách sản phẩm. Hãy kiểm tra kết nối MySQL.';
}

require __DIR__ . '/view/header.php';
?>
<section class="page-heading">
    <div>
        <h1>Danh sách sản phẩm</h1>
        <p>Quản lý thông tin các sản phẩm trong kho.</p>
    </div>
    <a class="button" href="product_add.php">+ Thêm sản phẩm</a>
</section>

<?php if ($message): ?>
    <p class="notice success" role="status"><?= e($message) ?></p>
<?php endif; ?>
<?php if ($error): ?>
    <p class="notice error" role="alert"><?= e($error) ?></p>
<?php else: ?>
    <div class="table-container">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Chức năng</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= e($product['id']) ?></td>
                    <td><?= e($product['name']) ?></td>
                    <td class="number"><?= e(number_format((float) $product['price'], 2, ',', '.')) ?> đ</td>
                    <td class="number"><?= e($product['quantity']) ?></td>
                    <td class="actions">
                        <a class="button small secondary" href="product_edit.php?id=<?= (int) $product['id'] ?>">Sửa</a>
                        <a class="button small danger-outline" href="product_delete.php?id=<?= (int) $product['id'] ?>">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($products)): ?>
                <tr><td colspan="5" class="empty">Chưa có sản phẩm nào.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
<?php require __DIR__ . '/view/footer.php'; ?>
