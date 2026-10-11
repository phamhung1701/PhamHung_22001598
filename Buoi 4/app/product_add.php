<?php
require_once __DIR__ . '/common/functions.php';
require_once __DIR__ . '/model/product.php';

$pageTitle = 'Thêm sản phẩm';
$name = $price = $quantity = '';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isValidCsrfToken($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Phiên làm việc không hợp lệ. Hãy tải lại trang và thử lại.';
    } else {
        $validated = validateProductInput($_POST);
        $errors = $validated['errors'];
        $name = $validated['name'];
        $price = $validated['price'];
        $quantity = $validated['quantity'];
        if (!$errors) {
            try {
                addProduct($name, $price, $quantity);
                header('Location: product_list.php?status=added');
                exit;
            } catch (Throwable $exception) {
                error_log($exception->getMessage());
                $errors[] = 'Không thể thêm sản phẩm. Kiểm tra kết nối MySQL.';
            }
        }
    }
    if (!isValidCsrfToken($_POST['csrf_token'] ?? null)) {
        $name = isset($_POST['name']) && is_scalar($_POST['name']) ? trim((string) $_POST['name']) : '';
        $price = isset($_POST['price']) && is_scalar($_POST['price']) ? trim((string) $_POST['price']) : '';
        $quantity = isset($_POST['quantity']) && is_scalar($_POST['quantity']) ? trim((string) $_POST['quantity']) : '';
    }
}
require __DIR__ . '/view/header.php';
?>
<h2>Thêm sản phẩm</h2>
<?php if ($errors): ?>
    <div class="notice error" role="alert"><strong>Vui lòng kiểm tra:</strong>
        <ul><?php foreach ($errors as $error): ?><li><?= escapeHtml($error) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>
<form method="post" action="product_add.php" class="product-form">
    <input type="hidden" name="csrf_token" value="<?= escapeHtml(getCsrfToken()) ?>">
    <label for="name">Tên sản phẩm</label>
    <input type="text" id="name" name="name" maxlength="100" required value="<?= escapeHtml($name) ?>">

    <label for="price">Giá (VNĐ)</label>
    <input type="number" id="price" name="price" min="0.01" max="99999999.99" step="0.01" required value="<?= escapeHtml($price) ?>">

    <label for="quantity">Số lượng</label>
    <input type="number" id="quantity" name="quantity" min="0" max="2147483647" step="1" required value="<?= escapeHtml($quantity) ?>">

    <div class="form-actions">
        <button type="submit">Lưu sản phẩm</button>
        <a class="button secondary" href="product_list.php">Hủy</a>
    </div>
</form>
<?php require __DIR__ . '/view/footer.php'; ?>
