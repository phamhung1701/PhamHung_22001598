# Bài thực hành số 6 — VueJS

## Bài 1: Thẻ thông tin sinh viên

Mở file `index.html` bằng trình duyệt để chạy ứng dụng. Vue 3 được tải qua CDN nên cần kết nối Internet.

### Nội dung đã thực hiện
- Khởi tạo ứng dụng bằng `createApp()` và khai báo dữ liệu trong `data()`.
- Hiển thị họ tên bằng `{{ }}`, tên lớp bằng `v-text`.
- Dùng `v-bind:src` và `v-bind:alt` cho ảnh đại diện.
- Dùng `v-if / v-else-if / v-else` để xếp loại theo điểm trung bình.
- Dùng `:class` để đổi màu xếp loại.
- Hiển thị trạng thái “Đang học” hoặc “Đã nghỉ”.
- Dùng `v-show` để bật/tắt phần giới thiệu.
- Có biểu mẫu chỉnh sửa dữ liệu bằng `v-model`, giao diện cập nhật tự động.

### Ngưỡng xếp loại
- 8–10: Giỏi
- 6,5–dưới 8: Khá
- 5–dưới 6,5: Trung bình
- Dưới 5: Chưa đạt
