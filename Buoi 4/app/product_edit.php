<?php
require_once __DIR__ . '/common/functions.php';
require_once __DIR__ . '/model/product.php';

$pageTitle = 'Sửa sản phẩm';
$id = positiveIdFromQuery();
$values = ['name' => '', 'price' => '', 'quantity' => ''];
$errors = [];
$pageError = null;
$submitError = null;
$product = null;

if ($id === null) {
    $pageError = 'ID sản phẩm không hợp lệ.';
} else {
    try {
        $product = getProductById($id);
        if ($product === null) {
            $pageError = 'Không tìm thấy sản phẩm cần sửa.';
        } else {
            $values = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $product['quantity'],
            ];
        }
    } catch (Throwable $exception) {
        $pageError = 'Không thể tải sản phẩm. Hãy kiểm tra kết nối MySQL.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $product !== null) {
    [$errors, $values] = validateProductInput($_POST);
    if (!validCsrfToken($_POST['csrf_token'] ?? null)) {
        $submitError = 'Phiên làm việc không hợp lệ. Hãy tải lại trang và thử lại.';
    } elseif (!$errors) {
        try {
            updateProduct($id, $values['name'], (float) $values['price'], (int) $values['quantity']);
            header('Location: product_list.php?message=updated', true, 303);
            exit;
        } catch (Throwable $exception) {
            $submitError = 'Không thể cập nhật sản phẩm. Hãy kiểm tra kết nối MySQL.';
        }
    }
}

require __DIR__ . '/view/header.php';
?>
<div class="form-layout">
    <h1>Sửa sản phẩm</h1>
    <?php if ($pageError): ?>
        <p class="notice error" role="alert"><?= e($pageError) ?></p>
        <a class="button secondary" href="product_list.php">Về danh sách</a>
    <?php else: ?>
        <p>Đang sửa sản phẩm #<?= e($id) ?>.</p>
        <?php if ($submitError): ?><p class="notice error" role="alert"><?= e($submitError) ?></p><?php endif; ?>
        <form method="post" action="product_edit.php?id=<?= e($id) ?>" class="product-form">
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
            <label for="name">Tên sản phẩm</label>
            <input id="name" name="name" type="text" maxlength="100" required value="<?= e($values['name']) ?>">
            <?php if (isset($errors['name'])): ?><p class="field-error"><?= e($errors['name']) ?></p><?php endif; ?>

            <label for="price">Giá (đ)</label>
            <input id="price" name="price" type="number" min="0.01" max="99999999.99" step="0.01" required value="<?= e($values['price']) ?>">
            <?php if (isset($errors['price'])): ?><p class="field-error"><?= e($errors['price']) ?></p><?php endif; ?>

            <label for="quantity">Số lượng</label>
            <input id="quantity" name="quantity" type="number" min="0" step="1" required value="<?= e($values['quantity']) ?>">
            <?php if (isset($errors['quantity'])): ?><p class="field-error"><?= e($errors['quantity']) ?></p><?php endif; ?>

            <div class="form-actions">
                <button type="submit">Cập nhật</button>
                <a class="button secondary" href="product_list.php">Hủy</a>
            </div>
        </form>
    <?php endif; ?>
</div>
<?php require __DIR__ . '/view/footer.php'; ?>
