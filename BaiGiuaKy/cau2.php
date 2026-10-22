<?php
$prices = [150000, 320000, 89000, 450000, 210000];

function calculateTotal($prices) {
    $total = 0;
    foreach ($prices as $price) {
        $total += $price;
    }
    return $total;
}

function findMaxPrice($prices) {
    $max = $prices[0];
    foreach ($prices as $price) {
        if ($price > $max) {
            $max = $price;
        }
    }
    return $max;
}

$total = calculateTotal($prices);
$maxPrice = findMaxPrice($prices);

echo "Danh sách giá: " . implode(", ", $prices) . "<br>\n";
echo "Tổng giá trị sản phẩm: " . number_format($total) . " VNĐ<br>\n";
echo "Giá sản phẩm cao nhất: " . number_format($maxPrice) . " VNĐ<br>\n";
?>
