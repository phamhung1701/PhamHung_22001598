<?php
require_once __DIR__ . '/common/functions.php';
require_once __DIR__ . '/model/product.php';

$pageTitle = 'Sửa sản phẩm';
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
            $errors[] = 'Sản phẩm không tồn tại.';
        }
    } catch (Throwable $exception) {
        error_log($exception->getMessage());
        $errors[] = 'Không thể tải thông tin sản phẩm. Kiểm tra MySQL.';
    }
}

$name = $product['name'] ?? '';
$price = $product['price'] ?? '';
$quantity = $product['quantity'] ?? '';

if ($isPost && $product !== null) {
    $validated = validateProductInput($_POST);
    $name = $validated['name'];
    $price = $validated['price'];
    $quantity = $validated['quantity'];
    $errors = array_merge($errors, $validated['errors']);
    if (!isValidCsrfToken($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Phiên làm việc không hợp lệ. Hãy tải lại trang và thử lại.';
    }
    if (!$errors) {
        try {
            updateProduct($id, $name, $price, $quantity);
            header('Location: product_list.php?status=updated');
            exit;
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            $errors[] = 'Không thể cập nhật sản phẩm. Kiểm tra MySQL.';
        }
    }
}
require __DIR__ . '/view/header.php';
?>
<h2>Sửa sản phẩm<?= $id !== null ? ' #' . escapeHtml($id) : '' ?></h2>
<?php if ($errors): ?>
    <div class="notice error" role="alert"><ul>
        <?php foreach ($errors as $error): ?><li><?= escapeHtml($error) ?></li><?php endforeach; ?>
    </ul></div>
<?php endif; ?>
<?php if ($product !== null): ?>
    <form method="post" action="product_edit.php" class="product-form">
        <input type="hidden" name="csrf_token" value="<?= escapeHtml(getCsrfToken()) ?>">
        <input type="hidden" name="id" value="<?= escapeHtml($id) ?>">
        <label for="name">Tên sản phẩm</label>
        <input type="text" id="name" name="name" maxlength="100" required value="<?= escapeHtml($name) ?>">

        <label for="price">Giá (VNĐ)</label>
        <input type="number" id="price" name="price" min="0.01" max="99999999.99" step="0.01" required value="<?= escapeHtml($price) ?>">

        <label for="quantity">Số lượng</label>
        <input type="number" id="quantity" name="quantity" min="0" max="2147483647" step="1" required value="<?= escapeHtml($quantity) ?>">

        <div class="form-actions">
            <button type="submit">Cập nhật</button>
            <a class="button secondary" href="product_list.php">Hủy</a>
        </div>
    </form>
<?php else: ?>
    <p><a href="product_list.php">Quay lại danh sách sản phẩm</a></p>
<?php endif; ?>
<?php require __DIR__ . '/view/footer.php'; ?>
