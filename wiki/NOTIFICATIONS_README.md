# Chức năng Thông báo & Nhắc lịch - EduCore

## Tổng quan

Chức năng Thông báo & Nhắc lịch cho phép giáo viên và admin tạo, quản lý và gửi thông báo đến học viên. Hệ thống hỗ trợ thông báo theo lịch, thông báo cho lớp học cụ thể hoặc tất cả học viên.

## Tính năng chính

### 1. Quản lý thông báo (Admin/Teacher)
- ✅ Tạo thông báo mới
- ✅ Chỉnh sửa thông báo
- ✅ Xóa thông báo
- ✅ Lọc và tìm kiếm thông báo
- ✅ Đánh dấu đã đọc/tất cả đã đọc
- ✅ Lập lịch gửi thông báo
- ✅ Thông báo cho học viên cụ thể hoặc tất cả
- ✅ Thông báo cho lớp học cụ thể

### 2. Xem thông báo (Student)
- ✅ Xem danh sách thông báo
- ✅ Lọc theo loại và trạng thái
- ✅ Đánh dấu đã đọc
- ✅ Tìm kiếm thông báo
- ✅ Hiển thị thông báo real-time trên navbar

### 3. Loại thông báo
- 📢 **Thông tin**: Thông báo chung
- ⚠️ **Cảnh báo**: Thông báo quan trọng
- ✅ **Thành công**: Thông báo tích cực
- 🚨 **Nguy hiểm**: Thông báo khẩn cấp
- ⏰ **Nhắc nhở**: Nhắc lịch học, bài tập

## Cấu trúc Database

### Bảng `notifications`
```sql
- id (Primary Key)
- user_id (Foreign Key - nullable)
- class_id (Foreign Key - nullable)
- title (String)
- message (Text)
- type (Enum: info, warning, success, danger, reminder)
- is_read (Boolean)
- scheduled_at (Timestamp - nullable)
- expires_at (Timestamp - nullable)
- created_at, updated_at
```

## Cách sử dụng

### 1. Truy cập quản lý thông báo (Admin)
```
/admin/notifications
```

### 2. Truy cập xem thông báo (Student)
```
/student/notifications
```

### 3. Tạo thông báo mới
1. Vào trang quản lý thông báo
2. Click "Tạo thông báo mới"
3. Điền thông tin:
   - **Tiêu đề**: Tiêu đề thông báo
   - **Nội dung**: Nội dung chi tiết
   - **Loại**: Chọn loại thông báo
   - **Người nhận**: Chọn học viên cụ thể hoặc tất cả
   - **Lớp học**: Chọn lớp học (tùy chọn)
   - **Lịch gửi**: Thời gian gửi (để trống = gửi ngay)
   - **Hết hạn**: Thời gian hết hạn (tùy chọn)

### 4. Lập lịch gửi thông báo
- Đặt thời gian trong trường "Lịch gửi"
- Hệ thống sẽ tự động gửi thông báo khi đến thời gian
- Chạy command: `php artisan notifications:send-scheduled`

## API Endpoints

### Admin Routes
```php
Route::get('/admin/notifications', AdminNotificationsIndex::class)->name('notifications.index');
```

### Student Routes
```php
Route::get('/student/notifications', StudentNotificationsIndex::class)->name('student.notifications.index');
```

## Components

### 1. Admin Notifications Index
- **File**: `app/Livewire/Admin/Notifications/Index.php`
- **View**: `resources/views/livewire/admin/notifications/index.blade.php`
- **Chức năng**: Quản lý đầy đủ thông báo

### 2. Student Notifications Index
- **File**: `app/Livewire/Student/Notifications/Index.php`
- **View**: `resources/views/livewire/student/notifications/index.blade.php`
- **Chức năng**: Xem thông báo dành cho học viên

### 3. Notification Bell Component
- **File**: `app/Livewire/Components/NotificationBell.php`
- **View**: `resources/views/livewire/components/notification-bell.blade.php`
- **Chức năng**: Hiển thị thông báo real-time trên navbar

## Commands

### Gửi thông báo theo lịch
```bash
php artisan notifications:send-scheduled
```

**Cách sử dụng với Cron Job:**
```bash
# Thêm vào crontab để chạy mỗi phút
* * * * * cd /path/to/your/project && php artisan notifications:send-scheduled >> /dev/null 2>&1
```

## Seeder

### NotificationSeeder
- **File**: `database/seeders/NotificationSeeder.php`
- **Chức năng**: Tạo dữ liệu mẫu cho thông báo
- **Chạy**: `php artisan db:seed --class=NotificationSeeder`

## Tính năng nâng cao

### 1. Real-time Notifications
- Sử dụng Livewire để cập nhật real-time
- Notification bell component hiển thị số thông báo chưa đọc
- Dropdown hiển thị 5 thông báo gần nhất

### 2. Lọc và Tìm kiếm
- Tìm kiếm theo tiêu đề và nội dung
- Lọc theo loại thông báo
- Lọc theo trạng thái đã đọc/chưa đọc

### 3. Responsive Design
- Giao diện responsive cho mobile và desktop
- Bootstrap 5 components
- Bootstrap Icons

### 4. Validation
- Validation đầy đủ cho form tạo/chỉnh sửa
- Hiển thị lỗi validation
- Sanitize input data

## Tùy chỉnh

### 1. Thêm loại thông báo mới
1. Cập nhật migration để thêm giá trị mới vào enum
2. Cập nhật arrays trong views
3. Thêm icon và màu sắc tương ứng

### 2. Thêm tính năng gửi email
1. Tạo Mail class cho notification
2. Cập nhật command `SendScheduledNotifications`
3. Cấu hình mail settings

### 3. Thêm WebSocket/Pusher
1. Cài đặt Pusher package
2. Tạo event cho notification
3. Cập nhật frontend để listen events

## Troubleshooting

### 1. Thông báo không hiển thị
- Kiểm tra user_id và class_id
- Kiểm tra trạng thái is_read
- Kiểm tra thời gian scheduled_at và expires_at

### 2. Command không chạy
- Kiểm tra quyền thực thi
- Kiểm tra log files
- Kiểm tra cron job configuration

### 3. Performance issues
- Thêm index cho các trường thường query
- Sử dụng pagination cho danh sách lớn
- Cache notification counts

## Contributing

Khi thêm tính năng mới:
1. Tạo migration nếu cần
2. Cập nhật model với relationships
3. Tạo/update Livewire components
4. Tạo/update views
5. Thêm routes
6. Cập nhật documentation

## License

Chức năng này là một phần của hệ thống EduCore. 