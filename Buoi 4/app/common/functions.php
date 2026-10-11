<?php
function escapeHtml($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function readProductId($value): ?int
{
    if (!is_string($value) && !is_int($value)) {
        return null;
    }
    $id = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    return $id === false ? null : $id;
}

/** @return array{errors: string[], name: string, price: string, quantity: string} */
function validateProductInput(array $input): array
{
    $name = isset($input['name']) && is_scalar($input['name']) ? trim((string) $input['name']) : '';
    $price = isset($input['price']) && is_scalar($input['price']) ? trim((string) $input['price']) : '';
    $quantity = isset($input['quantity']) && is_scalar($input['quantity']) ? trim((string) $input['quantity']) : '';
    $errors = [];

    if ($name === '') {
        $errors[] = 'Tên sản phẩm không được để trống.';
    } elseif (mbLength($name) > 100) {
        $errors[] = 'Tên sản phẩm không được vượt quá 100 ký tự.';
    }

    // DECIMAL(10,2): at most 8 digits before and 2 digits after the dot.
    if (!preg_match('/^\d{1,8}(?:\.\d{1,2})?$/D', $price) || (float) $price <= 0) {
        $errors[] = 'Giá phải lớn hơn 0 và có tối đa 2 chữ số thập phân (ví dụ 15000 hoặc 15000.50).';
    }

    if (!ctype_digit($quantity) || strlen($quantity) > 10
        || (float) $quantity > 2147483647) {
        $errors[] = 'Số lượng phải là số nguyên từ 0 đến 2147483647.';
    }

    return ['errors' => $errors, 'name' => $name, 'price' => $price, 'quantity' => $quantity];
}

function mbLength(string $text): int
{
    if (function_exists('mb_strlen')) {
        return mb_strlen($text, 'UTF-8');
    }
    return preg_match_all('/./us', $text, $matches) ?: 0;
}

function startProductSession(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function getCsrfToken(): string
{
    startProductSession();
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function isValidCsrfToken($submitted): bool
{
    startProductSession();
    return is_string($submitted) && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $submitted);
}
