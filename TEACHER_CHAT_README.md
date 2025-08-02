# Chức năng Chat cho Teacher

## Mô tả
Chức năng chat cho Teacher cho phép giáo viên tương tác với học sinh, giáo viên khác và các lớp học thông qua giao diện chat real-time với khả năng kéo thả file.

## Tính năng chính

### 1. Chat với học sinh
- Xem danh sách học sinh trong các lớp mà giáo viên phụ trách
- Gửi tin nhắn riêng tư cho từng học sinh
- Hiển thị số tin nhắn chưa đọc

### 2. Chat với giáo viên khác
- Xem danh sách các giáo viên khác trong hệ thống
- Gửi tin nhắn riêng tư cho giáo viên
- Hiển thị số tin nhắn chưa đọc

### 3. Chat nhóm lớp học
- Xem danh sách các lớp học mà giáo viên phụ trách
- Gửi tin nhắn nhóm cho toàn bộ lớp học
- Hiển thị số tin nhắn chưa đọc

### 4. Kéo thả file
- Kéo thả file trực tiếp vào vùng chat để đính kèm
- Hỗ trợ nhiều loại file (doc, docx, pdf, jpg, png, etc.)
- Giới hạn kích thước file 10MB

### 5. Real-time messaging
- Tin nhắn được gửi và nhận real-time
- Thông báo desktop khi có tin nhắn mới
- Auto-scroll đến tin nhắn mới nhất

## Cấu trúc file

```
app/Livewire/Teacher/Chat/
├── Index.php                    # Component Livewire chính

resources/views/teacher/chat/
├── index.blade.php             # View giao diện chat
```

## Routes

```php
// Teacher chat route
Route::get('/teacher/chat', \App\Livewire\Teacher\Chat\Index::class)->name('teacher.chat.index');
```

## Sử dụng

### 1. Truy cập chat
- Đăng nhập với tài khoản Teacher
- Vào menu "Chat & Tương tác" trong sidebar

### 2. Chọn người chat
- Tab "Học sinh": Chọn học sinh để chat riêng
- Tab "Lớp học": Chọn lớp để chat nhóm
- Tab "Giáo viên": Chọn giáo viên khác để chat

### 3. Gửi tin nhắn
- Nhập tin nhắn vào ô input
- Nhấn nút gửi hoặc Enter
- Đính kèm file bằng nút paperclip hoặc kéo thả

### 4. Kéo thả file
- Kéo file từ máy tính vào vùng chat
- File sẽ được tự động đính kèm
- Nhấn gửi để gửi tin nhắn với file đính kèm

## Tính năng kỹ thuật

### Real-time messaging
- Sử dụng Laravel Broadcasting với Pusher
- Event `MessageSent` được dispatch khi có tin nhắn mới
- JavaScript lắng nghe events để cập nhật UI real-time

### File upload
- Sử dụng Livewire `WithFileUploads` trait
- File được lưu trong `storage/app/public/chat-attachments`
- Validation: tối đa 10MB per file

### Drag & Drop
- JavaScript xử lý drag & drop events
- DataTransfer API để xử lý file được kéo thả
- Visual feedback khi kéo file vào vùng chat

## Dependencies

```php
// Livewire traits
use Livewire\WithPagination;
use Livewire\WithFileUploads;

// Models
use App\Models\Message;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Student;

// Events
use App\Events\MessageSent;
```

## Cấu hình

### Broadcasting (Pusher)
Đảm bảo đã cấu hình Pusher trong `.env`:

```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=your_cluster
```

### Storage
Đảm bảo đã tạo symbolic link cho storage:

```bash
php artisan storage:link
```

## Bảo mật

- Chỉ giáo viên mới có thể truy cập chức năng này
- Validation file upload để tránh upload file độc hại
- Kiểm tra quyền truy cập trước khi hiển thị tin nhắn
- Sanitize input để tránh XSS

## Troubleshooting

### Tin nhắn không hiển thị real-time
- Kiểm tra cấu hình Pusher
- Đảm bảo đã cài đặt và cấu hình Laravel Echo
- Kiểm tra console browser để xem lỗi JavaScript

### File upload không hoạt động
- Kiểm tra quyền ghi thư mục storage
- Kiểm tra cấu hình filesystem trong config/filesystems.php
- Đảm bảo đã tạo symbolic link storage

### Kéo thả file không hoạt động
- Kiểm tra JavaScript console để xem lỗi
- Đảm bảo browser hỗ trợ HTML5 Drag & Drop API
- Kiểm tra event listeners đã được đăng ký đúng cách 
