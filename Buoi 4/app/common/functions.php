<?php
/** Các hàm hỗ trợ hiển thị, kiểm tra dữ liệu và bảo vệ form. */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function positiveIdFromQuery(): ?int
{
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]);
    return $id === false || $id === null ? null : $id;
}

function validateProductInput(array $input): array
{
    $name = trim((string) ($input['name'] ?? ''));
    $price = trim((string) ($input['price'] ?? ''));
    $quantity = trim((string) ($input['quantity'] ?? ''));
    $errors = [];

    $nameLength = function_exists('mb_strlen')
        ? mb_strlen($name, 'UTF-8')
        : preg_match_all('/./us', $name);

    if ($name === '') {
        $errors['name'] = 'Tên sản phẩm không được để trống.';
    } elseif ($nameLength === false || $nameLength > 100) {
        $errors['name'] = 'Tên sản phẩm không được vượt quá 100 ký tự.';
    }

    // DECIMAL(10,2): tối đa 8 chữ số nguyên và 2 chữ số thập phân.
    if (!preg_match('/^\d{1,8}(?:\.\d{1,2})?$/', $price) || (float) $price <= 0) {
        $errors['price'] = 'Giá phải lớn hơn 0, tối đa 2 chữ số thập phân.';
    }

    $quantityNumber = filter_var($quantity, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 0],
    ]);
    if ($quantityNumber === false) {
        $errors['quantity'] = 'Số lượng phải là số nguyên lớn hơn hoặc bằng 0.';
    }

    return [$errors, [
        'name' => $name,
        'price' => $price,
        'quantity' => $quantity,
    ]];
}

function csrfToken(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validCsrfToken($token): bool
{
    return is_string($token) && hash_equals(csrfToken(), $token);
}
