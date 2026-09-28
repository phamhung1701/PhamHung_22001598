<?php

declare(strict_types=1);

class Movie
{
    public int $id;
    public string $title;
    public float $price;
    public int $totalSeats;
    public int $availableSeats;

    public function __construct(int $id, string $title, float $price, int $totalSeats)
    {
        $title = trim($title);
        if ($id <= 0 || $title === '') {
            throw new InvalidArgumentException('Mã phim phải lớn hơn 0 và tên phim không được để trống.');
        }
        if ($price <= 0) {
            throw new InvalidArgumentException('Giá vé phải lớn hơn 0.');
        }
        if ($totalSeats <= 0) {
            throw new InvalidArgumentException('Tổng số ghế phải lớn hơn 0.');
        }

        $this->id = $id;
        $this->title = $title;
        $this->price = $price;
        $this->totalSeats = $totalSeats;
        $this->availableSeats = $totalSeats;
    }

    public function bookTicket(int $quantity): bool
    {
        if ($quantity <= 0) {
            return false;
        }
        if ($quantity > $this->availableSeats) {
            return false;
        }

        $this->availableSeats -= $quantity;
        return true;
    }

    public function cancelTicket(int $quantity): bool
    {
        if ($quantity <= 0 || $quantity > $this->getSoldSeats()) {
            return false;
        }

        $this->availableSeats += $quantity;
        return true;
    }

    public function getSoldSeats(): int
    {
        return $this->totalSeats - $this->availableSeats;
    }

    public function getRevenue(): float
    {
        return $this->getSoldSeats() * $this->price;
    }

    public function displayInfo(): void
    {
        printf(
            "Mã: %d | Phim: %s | Giá vé: %s VND | Tổng ghế: %d | Còn lại: %d | Đã bán: %d | Doanh thu: %s VND\n",
            $this->id,
            $this->title,
            number_format($this->price, 0, ',', '.'),
            $this->totalSeats,
            $this->availableSeats,
            $this->getSoldSeats(),
            number_format($this->getRevenue(), 0, ',', '.')
        );
    }
}

/** @param Movie[] $movies */
function findMovieById(array $movies, int $id): ?Movie
{
    foreach ($movies as $movie) {
        if ($movie->id === $id) {
            return $movie;
        }
    }
    return null;
}

/** @param Movie[] $movies */
function getTotalRevenue(array $movies): float
{
    $total = 0.0;
    foreach ($movies as $movie) {
        $total += $movie->getRevenue();
    }
    return $total;
}

/** @param Movie[] $movies */
function getBestSellingMovie(array $movies): ?Movie
{
    if ($movies === []) {
        return null;
    }

    $bestSellingMovie = $movies[0];
    foreach ($movies as $movie) {
        if ($movie->getSoldSeats() > $bestSellingMovie->getSoldSeats()) {
            $bestSellingMovie = $movie;
        }
    }
    return $bestSellingMovie;
}

$movies = [
    new Movie(1, 'Avengers', 100000, 100),
    new Movie(2, 'Avatar', 120000, 80),
    new Movie(3, 'Batman', 90000, 120),
];

$avengers = findMovieById($movies, 1);
$avatar = findMovieById($movies, 2);

if ($avengers !== null && $avengers->bookTicket(20)) {
    echo "Đặt thành công 20 vé Avengers.\n";
} else {
    echo "Không thể đặt vé Avengers.\n";
}
if ($avatar !== null && $avatar->bookTicket(35)) {
    echo "Đặt thành công 35 vé Avatar.\n";
} else {
    echo "Không thể đặt vé Avatar.\n";
}
if ($avengers !== null && $avengers->cancelTicket(5)) {
    echo "Đã hủy 5 vé Avengers.\n";
} else {
    echo "Không thể hủy vé Avengers.\n";
}

if ($avengers !== null && !$avengers->bookTicket(0)) {
    echo "Từ chối đặt vé với số lượng 0.\n";
}
if ($avatar !== null && !$avatar->bookTicket(1000)) {
    echo "Từ chối đặt vé vượt quá số ghế còn lại.\n";
}
if ($avengers !== null && !$avengers->cancelTicket(1000)) {
    echo "Từ chối hủy nhiều vé hơn số vé đã bán.\n";
}
if (findMovieById($movies, 999) === null) {
    echo "Không tìm thấy phim có mã 999.\n";
}

printf("\nThông tin các phim:\n");
foreach ($movies as $movie) {
    $movie->displayInfo();
}
printf("Tổng doanh thu: %s VND\n", number_format(getTotalRevenue($movies), 0, ',', '.'));

$bestSellingMovie = getBestSellingMovie($movies);
if ($bestSellingMovie !== null) {
    echo "Phim bán được nhiều vé nhất: {$bestSellingMovie->title} (" . $bestSellingMovie->getSoldSeats() . " vé).\n";
} else {
    echo "Danh sách phim trống, không có phim bán chạy nhất.\n";
}
printf("Doanh thu với danh sách trống: %s VND\n", number_format(getTotalRevenue([]), 0, ',', '.'));