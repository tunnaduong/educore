# Chức năng Chat & Tương tác

## Tổng quan
Chức năng Chat & Tương tác cho phép người dùng trong hệ thống EduCore gửi tin nhắn cho nhau và cho các lớp học. Giao diện được thiết kế tương tự như trang home với các icon và màu sắc nhất quán.

## Tính năng chính

### 1. Chat cá nhân
- Gửi tin nhắn trực tiếp giữa các người dùng
- Hỗ trợ đính kèm file (tối đa 10MB)
- Hiển thị trạng thái tin nhắn (đã đọc/chưa đọc)
- Tìm kiếm người dùng theo tên hoặc email

### 2. Chat nhóm (Lớp học)
- Gửi tin nhắn cho toàn bộ lớp học
- Chỉ giáo viên và admin có thể gửi tin nhắn cho lớp
- Học sinh có thể xem tin nhắn của lớp mình tham gia

### 3. Thông báo real-time
- Hiển thị số tin nhắn chưa đọc trên icon chat
- Thông báo browser notification
- Toast notification khi có tin nhắn mới
- Auto-refresh tin nhắn mỗi 30 giây

## Cấu trúc file

### Models
- `app/Models/Message.php` - Model quản lý tin nhắn

### Livewire Components
- `app/Livewire/Admin/Chat/Index.php` - Trang chat chính
- `app/Livewire/Admin/Chat/ChatNotification.php` - Component thông báo nhỏ
- `app/Livewire/Admin/Chat/RealTimeNotification.php` - Component thông báo real-time

### Views
- `resources/views/livewire/admin/chat/index.blade.php` - Giao diện chat chính
- `resources/views/livewire/admin/chat/chat-notification.blade.php` - Giao diện thông báo nhỏ
- `resources/views/livewire/admin/chat/real-time-notification.blade.php` - Giao diện thông báo real-time

### Database
- `database/migrations/2025_06_18_081452_create_messages_table.php` - Migration bảng messages
- `database/seeders/ChatSeeder.php` - Seeder dữ liệu mẫu

## Cách sử dụng

### 1. Truy cập trang chat
- Đăng nhập vào hệ thống
- Click vào icon "Chat & Tương tác" trên trang home
- Hoặc truy cập trực tiếp: `/chat`

### 2. Gửi tin nhắn cá nhân
- Chọn tab "Người dùng"
- Tìm kiếm người dùng muốn chat
- Click vào người dùng để bắt đầu cuộc trò chuyện
- Nhập tin nhắn và nhấn Enter hoặc click nút gửi

### 3. Gửi tin nhắn cho lớp
- Chọn tab "Lớp học"
- Chọn lớp muốn gửi tin nhắn
- Nhập tin nhắn và gửi

### 4. Đính kèm file
- Click vào icon đính kèm (📎)
- Chọn file muốn gửi (tối đa 10MB)
- File sẽ được lưu trong thư mục `storage/app/public/chat-attachments`

## Cài đặt và chạy

### 1. Chạy migration
```bash
php artisan migrate
```

### 2. Chạy seeder (tùy chọn)
```bash
php artisan db:seed --class=ChatSeeder
```

### 3. Tạo symbolic link cho storage
```bash
php artisan storage:link
```

### 4. Cấu hình thông báo (tùy chọn)
Để sử dụng thông báo browser, cần:
- Sử dụng HTTPS hoặc localhost
- Người dùng cho phép thông báo

## Tùy chỉnh

### Thay đổi kích thước file tối đa
Trong `app/Livewire/Admin/Chat/Index.php`, thay đổi:
```php
'attachment' => 'nullable|file|max:10240', // 10MB
```

### Thay đổi thời gian auto-refresh
Trong `resources/views/livewire/admin/chat/index.blade.php`, thay đổi:
```javascript
setInterval(() => {
    @this.refreshMessages();
}, 30000); // 30 giây
```

### Thay đổi số tin nhắn hiển thị
Trong `app/Livewire/Admin/Chat/Index.php`, thay đổi:
```php
->paginate(20); // 20 tin nhắn mỗi trang
```

## Bảo mật

- Chỉ người dùng đã đăng nhập mới có thể sử dụng chat
- Kiểm tra quyền truy cập lớp học trước khi gửi tin nhắn
- Validate kích thước và loại file đính kèm
- Sanitize nội dung tin nhắn để tránh XSS

## Troubleshooting

### Tin nhắn không hiển thị
- Kiểm tra quyền truy cập database
- Kiểm tra relationship giữa các bảng
- Xem log lỗi trong `storage/logs/laravel.log`

### File không upload được
- Kiểm tra quyền ghi thư mục `storage/app/public`
- Kiểm tra symbolic link đã được tạo chưa
- Kiểm tra cấu hình filesystem trong `config/filesystems.php`

### Thông báo không hoạt động
- Kiểm tra quyền thông báo browser
- Kiểm tra console JavaScript có lỗi không
- Đảm bảo sử dụng HTTPS hoặc localhost 
