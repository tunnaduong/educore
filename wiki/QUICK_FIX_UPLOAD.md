# 🚀 Khắc phục nhanh lỗi "Trường attachment tải lên thất bại"

## 🔧 Vấn đề
Teacher gặp lỗi "Trường attachment tải lên thất bại" khi upload file trong form tạo bài học.

## ⚡ Giải pháp nhanh

### Bước 1: Chạy test để kiểm tra
```bash
php artisan test:file-upload
```

### Bước 2: Sửa lỗi storage (nếu cần)
```bash
php artisan storage:check --fix
```

### Bước 3: Tạo symbolic link (nếu chưa có)
```bash
php artisan storage:link
```

### Bước 4: Kiểm tra log
```bash
tail -f storage/logs/laravel.log
```

## 🔍 Nguyên nhân chính

### 1. **Validation Error**
- Đã sửa: Tách validation file upload ra khỏi rules chính
- Đã thêm: Debug logging để theo dõi quá trình upload

### 2. **Storage Configuration**
- Đã thêm: Command kiểm tra và sửa storage
- Đã thêm: Test file upload functionality

### 3. **File Upload Handling**
- Đã cải thiện: Error handling trong components
- Đã thêm: Preview file trong UI

## ✅ Các thay đổi đã thực hiện

### Components đã cập nhật:
- `app/Livewire/Teacher/Lessons/Create.php`
  - ✅ Tách validation file upload
  - ✅ Thêm debug logging
  - ✅ Cải thiện error handling
  - ✅ Thêm method `updatedAttachment()`

- `app/Livewire/Teacher/Lessons/Edit.php`
  - ✅ Tách validation file upload
  - ✅ Thêm debug logging
  - ✅ Cải thiện error handling
  - ✅ Thêm method `updatedAttachment()`

### Views đã cập nhật:
- `resources/views/teacher/lessons/create.blade.php`
  - ✅ Thêm preview file
  - ✅ Thêm thông báo lỗi
  - ✅ Thêm accept attribute

- `resources/views/teacher/lessons/edit.blade.php`
  - ✅ Thêm preview file
  - ✅ Thêm thông báo lỗi
  - ✅ Thêm accept attribute

### Commands mới:
- `app/Console/Commands/TestFileUpload.php` - Test toàn bộ cấu hình upload
- `app/Console/Commands/CheckStorage.php` - Kiểm tra và sửa storage

## 🎯 Kết quả mong đợi

Sau khi áp dụng các thay đổi:
1. ✅ Không còn lỗi "Trường attachment tải lên thất bại"
2. ✅ File upload hoạt động bình thường
3. ✅ Hiển thị preview file đã chọn
4. ✅ Thông báo lỗi rõ ràng nếu có vấn đề
5. ✅ Debug logging để theo dõi quá trình upload

## 🚨 Lưu ý quan trọng

- **File size limit**: 10MB
- **Supported formats**: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT
- **Storage location**: `storage/app/public/lessons/attachments/`
- **Debug logs**: `storage/logs/laravel.log`

## 🔧 Troubleshooting

### Nếu vẫn gặp lỗi:
1. Kiểm tra log: `tail -f storage/logs/laravel.log`
2. Chạy test: `php artisan test:file-upload`
3. Kiểm tra quyền: `ls -la storage/app/public/`
4. Tạo lại symbolic link: `php artisan storage:link` 