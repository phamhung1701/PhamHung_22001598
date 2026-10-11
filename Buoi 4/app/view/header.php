<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escapeHtml($pageTitle ?? 'Quản lý sản phẩm') ?></title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<header class="site-header">
    <div class="container header-content">
        <div>
            <h1>Quản lý giỏ hàng</h1>
            <p>Bài thực hành PHP + MySQL – Quản lý sản phẩm</p>
        </div>
        <nav aria-label="Điều hướng chính">
            <a href="product_list.php">Danh sách</a>
            <a href="product_add.php">Thêm sản phẩm</a>
        </nav>
    </div>
</header>
<main class="container">
