# 📁 Hướng dẫn khắc phục vấn đề Upload File

## 🔧 Vấn đề
Teacher không thể upload tài liệu đính kèm khi tạo bài học mới.

## 🛠️ Giải pháp

### 1. Kiểm tra và sửa lỗi Storage
```bash
# Kiểm tra storage
php artisan storage:check

# Tự động sửa lỗi storage
php artisan storage:check --fix
```

### 2. Test file upload
```bash
# Test toàn bộ cấu hình upload
php artisan test:file-upload
```

### 2. Tạo Symbolic Link (nếu chưa có)
```bash
php artisan storage:link
```

### 3. Kiểm tra quyền thư mục
```bash
# Đảm bảo thư mục storage có quyền ghi
chmod -R 755 storage/
chmod -R 755 public/storage/
```

### 4. Kiểm tra cấu hình PHP
Đảm bảo các extension PHP sau được bật:
- `fileinfo`
- `gd` hoặc `imagick`
- `zip`

### 5. Khắc phục lỗi validation
Đã cập nhật validation để tránh lỗi "Trường attachment tải lên thất bại":
- Tách validation file upload ra khỏi rules chính
- Thêm debug logging để theo dõi quá trình upload
- Cải thiện error handling

## 🔍 Debug

### Kiểm tra Log
Xem log trong `storage/logs/laravel.log` để tìm lỗi:
```bash
tail -f storage/logs/laravel.log
```

### Kiểm tra thông tin file
Trong form upload, sẽ hiển thị:
- Tên file đã chọn
- Kích thước file
- Thông báo lỗi (nếu có)

### Test Upload
```bash
# Test quyền ghi
php artisan storage:check
```

## 📋 Các thay đổi đã thực hiện

### 1. Components đã cập nhật
- `app/Livewire/Teacher/Lessons/Create.php` - Thêm debug và xử lý lỗi
- `app/Livewire/Teacher/Lessons/Edit.php` - Thêm debug và xử lý lỗi

### 2. Views đã cập nhật
- `resources/views/teacher/lessons/create.blade.php` - Thêm preview file và thông báo lỗi
- `resources/views/teacher/lessons/edit.blade.php` - Thêm preview file và thông báo lỗi

### 3. Files mới tạo
- `app/Console/Commands/CheckStorage.php` - Command kiểm tra storage
- `app/Console/Commands/TestFileUpload.php` - Command test file upload

## ✅ Kết quả mong đợi

Sau khi khắc phục, teacher sẽ thấy:
1. ✅ Có thể chọn file từ dropdown
2. ✅ Hiển thị preview file đã chọn
3. ✅ Upload file thành công
4. ✅ Thông báo lỗi rõ ràng nếu có vấn đề

## 🚨 Lưu ý

### Kích thước file
- Tối đa: 10MB
- Định dạng hỗ trợ: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT

### Cấu hình server
- `upload_max_filesize` trong php.ini
- `post_max_size` trong php.ini
- `max_execution_time` trong php.ini

### Kiểm tra nhanh
```bash
# Kiểm tra symbolic link
ls -la public/storage

# Kiểm tra thư mục storage
ls -la storage/app/public/lessons/attachments

# Kiểm tra quyền
ls -la storage/app/public/
```

## 🔧 Troubleshooting

### Lỗi "File too large"
```bash
# Tăng giới hạn upload trong php.ini
upload_max_filesize = 20M
post_max_size = 20M
max_execution_time = 300
```

### Lỗi "Permission denied"
```bash
# Sửa quyền thư mục
sudo chown -R www-data:www-data storage/
sudo chmod -R 755 storage/
```

### Lỗi "Symbolic link failed"
```bash
# Xóa và tạo lại symbolic link
rm public/storage
php artisan storage:link
``` 