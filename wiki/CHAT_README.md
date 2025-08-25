# Hướng dẫn sử dụng chức năng Chat

## Tổng quan
Chức năng chat được xây dựng với Livewire và Bootstrap 5, hỗ trợ realtime messaging, drag & drop file, và typing indicators.

## Tính năng chính

### 1. Chat 1-1 (User to User)
- Chat trực tiếp giữa các người dùng
- Hỗ trợ gửi tin nhắn text và file đính kèm
- Typing indicators
- Đánh dấu tin nhắn đã đọc

### 2. Chat nhóm (Class Chat)
- Chat trong lớp học
- Tất cả thành viên lớp có thể tham gia
- Hiển thị số tin nhắn chưa đọc
- Quản lý thành viên (Admin)

### 3. Realtime Features
- Tin nhắn xuất hiện ngay lập tức
- Typing indicators
- Notifications
- Auto-scroll to bottom

### 4. File Upload
- Drag & drop files
- Hỗ trợ nhiều định dạng: images, PDF, DOC, TXT
- Giới hạn 10MB per file
- Download files

## Cấu trúc Files

### Components
```
app/Livewire/Teacher/Chat/Index.php          # Main chat component cho Teacher
app/Livewire/Admin/Chat/Index.php            # Main chat component cho Admin  
app/Livewire/Student/Chat/Index.php          # Main chat component cho Student
app/Livewire/Teacher/Chat/Test.php           # Test component
```

### Views
```
resources/views/teacher/chat/index.blade.php  # Main chat view cho Teacher
resources/views/admin/chat/index.blade.php    # Main chat view cho Admin
resources/views/student/chat/index.blade.php  # Main chat view cho Student
resources/views/teacher/chat/test.blade.php   # Test view
```

### Models & Events
```
app/Models/Message.php                        # Message model
app/Events/MessageSent.php                    # Broadcasting event
```

## Cài đặt và Cấu hình

### 1. Broadcasting Configuration
Đảm bảo đã cấu hình broadcasting trong `config/broadcasting.php`:

```php
'default' => env('BROADCAST_DRIVER', 'pusher'),

'connections' => [
    'pusher' => [
        'driver' => 'pusher',
        'key' => env('PUSHER_APP_KEY'),
        'secret' => env('PUSHER_APP_SECRET'),
        'app_id' => env('PUSHER_APP_ID'),
        'options' => [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true,
        ],
    ],
],
```

### 2. Environment Variables
Thêm vào file `.env`:

```env
BROADCAST_DRIVER=pusher
PUSHER_APP_KEY=your_pusher_key
PUSHER_APP_SECRET=your_pusher_secret
PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_CLUSTER=your_cluster
```

### 3. Frontend Setup
Đảm bảo đã include Laravel Echo và Pusher trong layout:

```html
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>

<script>
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '{{ config('broadcasting.connections.pusher.key') }}',
    cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
    encrypted: true
});
</script>
```

## Sử dụng

### 1. Truy cập Chat
- **Teacher**: `/teacher/chat`
- **Admin**: `/admin/chat`  
- **Student**: `/student/chat`

### 2. Test Chat
- **Teacher**: `/teacher/chat/test`

### 3. Cách sử dụng
1. Chọn tab "Lớp học" hoặc "Người dùng"
2. Click vào lớp học hoặc người dùng để bắt đầu chat
3. Nhập tin nhắn và nhấn Enter hoặc click nút gửi
4. Kéo thả file để đính kèm
5. Xem typing indicators khi người khác đang nhập

## Troubleshooting

### 1. Tin nhắn không hiển thị realtime
- Kiểm tra cấu hình Pusher
- Đảm bảo Laravel Echo đã được load
- Kiểm tra console errors

### 2. File upload không hoạt động
- Kiểm tra quyền write trong thư mục storage
- Chạy `php artisan storage:link`
- Kiểm tra giới hạn file size

### 3. Broadcasting không hoạt động
- Chạy `php artisan queue:work` để xử lý jobs
- Kiểm tra log trong `storage/logs/laravel.log`
- Đảm bảo Redis hoặc database queue đã được cấu hình

### 4. Lỗi authentication
- Kiểm tra middleware auth
- Đảm bảo user đã đăng nhập
- Kiểm tra role permissions

## API Endpoints

### Download File
```
GET /teacher/chat/download/{messageId}
GET /admin/chat/download/{messageId}  
GET /student/chat/download/{messageId}
```

### Broadcast Channels
```
Private: chat-user-{userId}
Public: chat-class-{classId}
```

## Security

### 1. Authorization
- Chỉ thành viên lớp mới có thể chat trong class
- Private channels cho chat 1-1
- Role-based access control

### 2. File Security
- Validation file types
- Size limits
- Secure file storage
- Download authorization

### 3. XSS Protection
- HTML escaping
- Input validation
- CSRF protection

## Performance

### 1. Pagination
- Tin nhắn được paginate (20 per page)
- Lazy loading

### 2. Caching
- User data caching
- Class data caching

### 3. Database Optimization
- Indexed columns
- Efficient queries
- Eager loading relationships

## Customization

### 1. Styling
- Bootstrap 5 classes
- Custom CSS trong view
- Responsive design

### 2. Features
- Thêm emoji picker
- Voice messages
- Video calls
- Message reactions

### 3. Notifications
- Browser notifications
- Email notifications
- Push notifications

## Testing

### 1. Manual Testing
- Sử dụng test component tại `/teacher/chat/test`
- Test với nhiều user khác nhau
- Test file upload

### 2. Automated Testing
```bash
php artisan test --filter=ChatTest
```

## Deployment

### 1. Production Setup
- Cấu hình Pusher production keys
- Setup queue workers
- Configure caching
- Setup monitoring

### 2. Performance Monitoring
- Monitor queue jobs
- Track message delivery
- Monitor file uploads
- Error tracking

## Support

Nếu gặp vấn đề, vui lòng:
1. Kiểm tra logs trong `storage/logs/laravel.log`
2. Kiểm tra browser console
3. Test với test component
4. Liên hệ developer team 
