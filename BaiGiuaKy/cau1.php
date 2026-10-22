<?php
$name = "Nguyễn Văn An";
$score = 7.5;

if ($score >= 8) {
    $rank = "Giỏi";
} elseif ($score >= 6.5) {
    $rank = "Khá";
} elseif ($score >= 5) {
    $rank = "Trung bình";
} else {
    $rank = "Yếu";
}

echo "Tên học sinh: " . $name . "<br>\n";
echo "Điểm: " . $score . "<br>\n";
echo "Xếp loại: " . $rank . "<br>\n";
?>
