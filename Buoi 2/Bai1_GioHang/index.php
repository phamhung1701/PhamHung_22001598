<?php

declare(strict_types=1);

class CartItem
{
    public string $name;
    public float $price;
    public int $quantity;

    public function __construct(string $name, float $price, int $quantity)
    {
        $name = trim($name);
        if ($name === '') {
            throw new InvalidArgumentException('Tên sản phẩm không được để trống.');
        }
        if ($price <= 0) {
            throw new InvalidArgumentException('Giá sản phẩm phải lớn hơn 0.');
        }
        if ($quantity <= 0) {
            throw new InvalidArgumentException('Số lượng sản phẩm phải lớn hơn 0.');
        }

        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function getTotal(): float
    {
        return $this->price * $this->quantity;
    }
}

class ShoppingCart
{
    /** @var CartItem[] */
    private array $items = [];

    public function addItem(CartItem $item): void
    {
        $this->items[] = $item;
        echo "Đã thêm sản phẩm: {$item->name}.\n";
    }

    public function removeItem(string $name): bool
    {
        foreach ($this->items as $index => $item) {
            if (strcasecmp($item->name, trim($name)) === 0) {
                unset($this->items[$index]);
                $this->items = array_values($this->items);
                echo "Đã xóa sản phẩm: {$item->name}.\n";
                return true;
            }
        }

        echo "Không tìm thấy sản phẩm '{$name}' trong giỏ hàng.\n";
        return false;
    }

    public function calculateTotal(): float
    {
        $total = 0.0;
        foreach ($this->items as $item) {
            $total += $item->getTotal();
        }
        return $total;
    }

    public function displayCart(): void
    {
        echo "\nDanh sách sản phẩm trong giỏ hàng:\n";
        if ($this->items === []) {
            echo "Giỏ hàng đang trống.\n";
            echo "Tổng tiền: 0 VND\n";
            return;
        }

        printf("%-24s %14s %10s %16s\n", 'Tên sản phẩm', 'Đơn giá', 'Số lượng', 'Thành tiền');
        foreach ($this->items as $item) {
            printf(
                "%-24s %14s %10d %16s\n",
                $item->name,
                number_format($item->price, 0, ',', '.'),
                $item->quantity,
                number_format($item->getTotal(), 0, ',', '.')
            );
        }
        printf("Tổng tiền: %s VND\n", number_format($this->calculateTotal(), 0, ',', '.'));
    }
}

$cart = new ShoppingCart();
$cart->addItem(new CartItem('Bàn phím', 650000, 1));
$cart->addItem(new CartItem('Chuột không dây', 320000, 2));
$cart->addItem(new CartItem('Tai nghe', 480000, 1));
$cart->addItem(new CartItem('USB 64GB', 180000, 3));

$cart->displayCart();
echo "Tổng tiền giỏ hàng: " . number_format($cart->calculateTotal(), 0, ',', '.') . " VND\n";

$cart->removeItem('Tai nghe');
$cart->displayCart();
$cart->removeItem('Máy ảnh');

try {
    new CartItem('Sản phẩm không hợp lệ', 0, 1);
} catch (InvalidArgumentException $exception) {
    echo "Không thể thêm sản phẩm: {$exception->getMessage()}\n";
}