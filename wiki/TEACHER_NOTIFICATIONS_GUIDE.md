# 🔔 Hướng dẫn Thông báo & Nhắc lịch cho Teacher

## 🎯 Tổng quan
Chức năng Thông báo & Nhắc lịch cho Teacher cho phép giáo viên tạo và quản lý thông báo cho học viên trong các lớp họ đang dạy.

## ✅ Tính năng chính

### 📝 **Tạo thông báo**
- Tạo thông báo mới với tiêu đề và nội dung
- Chọn loại thông báo (Thông tin, Cảnh báo, Thành công, Nguy hiểm, Nhắc nhở)
- Chọn lớp học cụ thể hoặc gửi cho tất cả lớp đang dạy
- Lên lịch gửi thông báo trong tương lai
- Đánh dấu thông báo khẩn cấp

### 🔍 **Quản lý thông báo**
- Xem danh sách tất cả thông báo đã tạo
- Tìm kiếm theo tiêu đề hoặc nội dung
- Lọc theo loại thông báo và trạng thái
- Chỉnh sửa thông báo đã tạo
- Sao chép thông báo để tạo mới
- Xóa thông báo không cần thiết

### ⏰ **Lịch gửi**
- Lên lịch gửi thông báo trong tương lai
- Gửi ngay thông báo đã lên lịch
- Xem trạng thái gửi (Đã gửi/Chờ gửi)

### 📊 **Thống kê**
- Đếm số thông báo chưa đọc
- Xem thông báo theo lớp học
- Theo dõi thời gian tạo và gửi

## 🚀 Cách sử dụng

### 1. **Truy cập chức năng**
- Đăng nhập với tài khoản Teacher
- Vào menu "Thông báo" trong sidebar
- Hoặc click vào icon "Thông báo" trên trang chủ

### 2. **Tạo thông báo mới**
1. Click nút "Tạo thông báo mới"
2. Điền thông tin:
   - **Tiêu đề**: Tên thông báo
   - **Nội dung**: Chi tiết thông báo
   - **Loại**: Chọn loại thông báo
   - **Lớp học**: Chọn lớp cụ thể hoặc "Tất cả lớp học"
   - **Lịch gửi**: Chọn thời gian gửi (để trống = gửi ngay)
   - **Thông báo khẩn cấp**: Đánh dấu nếu cần
3. Click "Tạo thông báo"

### 3. **Quản lý thông báo**
- **Tìm kiếm**: Nhập từ khóa vào ô tìm kiếm
- **Lọc**: Chọn loại thông báo và trạng thái
- **Chỉnh sửa**: Click icon bút chì
- **Sao chép**: Click icon files để tạo bản sao
- **Gửi ngay**: Click icon gửi cho thông báo đã lên lịch
- **Xóa**: Click icon thùng rác

### 4. **Thao tác hàng loạt**
- **Đánh dấu tất cả đã đọc**: Click nút "Đánh dấu tất cả đã đọc"
- **Xóa hết hạn**: Click nút "Xóa hết hạn" để xóa thông báo cũ

## 📋 Các loại thông báo

### ℹ️ **Thông tin** (info)
- Thông báo chung, cập nhật
- Màu xanh dương
- Ví dụ: Cập nhật lịch học, thông báo nghỉ học

### ⚠️ **Cảnh báo** (warning)
- Thông báo quan trọng cần lưu ý
- Màu vàng
- Ví dụ: Nhắc nhở deadline, thay đổi lịch học

### ✅ **Thành công** (success)
- Thông báo tích cực
- Màu xanh lá
- Ví dụ: Kết quả kiểm tra tốt, hoàn thành bài tập

### 🚨 **Nguy hiểm** (danger)
- Thông báo khẩn cấp, quan trọng
- Màu đỏ
- Ví dụ: Thông báo thi, deadline gấp

### 🔔 **Nhắc nhở** (reminder)
- Nhắc nhở lịch trình, deadline
- Màu xanh dương nhạt
- Ví dụ: Nhắc nhở nộp bài, chuẩn bị thuyết trình

## 🔧 Tính năng nâng cao

### 📅 **Lịch gửi**
- Lên lịch gửi thông báo trong tương lai
- Hệ thống tự động gửi khi đến thời gian
- Có thể gửi ngay thông báo đã lên lịch

### 🚨 **Thông báo khẩn cấp**
- Đánh dấu thông báo quan trọng
- Hiển thị nổi bật cho học viên
- Ưu tiên hiển thị trong danh sách

### 📊 **Phân quyền**
- Teacher chỉ thấy thông báo của lớp họ đang dạy
- Không thể tạo thông báo cho lớp không dạy
- Chỉ có thể chỉnh sửa thông báo mình tạo

## 🎯 Best Practices

### ✅ **Nên làm**
- Viết tiêu đề ngắn gọn, rõ ràng
- Nội dung chi tiết nhưng dễ hiểu
- Chọn loại thông báo phù hợp
- Lên lịch gửi trước thời gian cần thiết
- Đánh dấu khẩn cấp cho thông báo quan trọng

### ❌ **Không nên**
- Viết tiêu đề quá dài hoặc mơ hồ
- Nội dung quá ngắn hoặc khó hiểu
- Gửi quá nhiều thông báo cùng lúc
- Quên lên lịch cho thông báo quan trọng
- Đánh dấu khẩn cấp cho thông báo thường

## 🔧 Technical Details

### Files chính:
- `app/Livewire/Teacher/Notifications/Index.php` - Component chính
- `resources/views/teacher/notifications/index.blade.php` - View
- `app/Livewire/Components/NotificationBell.php` - Notification bell
- `database/seeders/TeacherNotificationSeeder.php` - Test data

### Database:
- `notifications` table với các trường:
  - `title`, `message`, `type`
  - `classroom_id`, `user_id`
  - `scheduled_at`, `is_read`, `is_urgent`

### Routes:
- `GET /teacher/notifications` - Trang chính
- Tất cả actions được xử lý qua Livewire

## 🚨 Troubleshooting

### Thông báo không hiển thị:
1. Kiểm tra teacher có được gán vào lớp không
2. Kiểm tra quyền truy cập
3. Refresh trang và thử lại

### Không thể tạo thông báo:
1. Kiểm tra form validation
2. Đảm bảo đã chọn lớp học
3. Kiểm tra thời gian lên lịch

### Thông báo không gửi:
1. Kiểm tra lịch gửi có đúng không
2. Đảm bảo thời gian gửi trong tương lai
3. Thử gửi ngay thay vì lên lịch

## 📈 Kết quả mong đợi

Sau khi triển khai:
1. ✅ Teacher có thể tạo và quản lý thông báo
2. ✅ Học viên nhận được thông báo từ teacher
3. ✅ Hệ thống hỗ trợ lên lịch gửi
4. ✅ Phân quyền theo lớp học
5. ✅ Giao diện thân thiện và dễ sử dụng 