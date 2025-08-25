# 🎓 Hướng dẫn Setup Teacher Lessons

## 🔧 Vấn đề và Giải pháp

### Vấn đề
Teacher không thấy lớp học trong dropdown "Chọn lớp học" khi tạo bài học mới.

### Nguyên nhân
Teacher chưa được gán vào lớp học nào trong bảng `class_user`.

### Giải pháp

#### 1. Chạy Seeder để thêm dữ liệu test
```bash
php artisan db:seed --class=TeacherClassroomSeeder
```

#### 2. Hoặc chạy toàn bộ seeder
```bash
php artisan db:seed
```

#### 3. Kiểm tra dữ liệu bằng command
```bash
php artisan teacher:check-classrooms
```

#### 4. Tự động sửa lỗi (nếu có)
```bash
php artisan teacher:check-classrooms --fix
```

## 📊 Kiểm tra dữ liệu

### Kiểm tra trong database
```sql
-- Kiểm tra teacher
SELECT * FROM users WHERE role = 'teacher';

-- Kiểm tra lớp học
SELECT * FROM classrooms;

-- Kiểm tra bảng class_user
SELECT 
    cu.*,
    u.name as teacher_name,
    c.name as classroom_name
FROM class_user cu
JOIN users u ON cu.user_id = u.id
JOIN classrooms c ON cu.class_id = c.id
WHERE cu.role = 'teacher';
```

### Kiểm tra trong code
```php
// Trong tinker hoặc controller
$teacher = User::where('role', 'teacher')->first();
$teachingClassrooms = $teacher->teachingClassrooms;
dd($teachingClassrooms->toArray());
```

## 🛠️ Các thay đổi đã thực hiện

### 1. Components đã cập nhật
- `app/Livewire/Teacher/Lessons/Create.php` - Thêm fallback logic
- `app/Livewire/Teacher/Lessons/Index.php` - Thêm fallback logic
- `app/Livewire/Teacher/Lessons/Edit.php` - Thêm fallback logic
- `app/Livewire/Teacher/Lessons/Show.php` - Thêm fallback logic

### 2. Views đã cập nhật
- `resources/views/teacher/lessons/create.blade.php` - Thêm thông báo debug

### 3. Files mới tạo
- `app/Console/Commands/CheckTeacherClassrooms.php` - Command kiểm tra
- `database/seeders/TeacherClassroomSeeder.php` - Seeder thêm dữ liệu

## 🔍 Debug

### Log files
Kiểm tra log trong `storage/logs/laravel.log` để xem thông tin debug:
```
Teacher ID: 1
Teacher Role: teacher
Teaching Classrooms Count: 0
Fallback: Using all classrooms. Count: 5
```

### View debug
Trong form tạo bài học, sẽ hiển thị:
- Số lượng lớp học tìm thấy
- Thông báo cảnh báo nếu không có lớp học

## ✅ Kết quả mong đợi

Sau khi chạy seeder, teacher sẽ thấy:
1. ✅ Dropdown "Chọn lớp học" có danh sách lớp học
2. ✅ Có thể tạo bài học cho lớp học đã được gán
3. ✅ Chỉ thấy bài học của các lớp mình đang dạy
4. ✅ Có thể chỉnh sửa và xem chi tiết bài học

## 🚨 Lưu ý

- Nếu teacher chưa được gán vào lớp học nào, hệ thống sẽ hiển thị tất cả lớp học (fallback)
- Để bảo mật tốt hơn, admin nên gán teacher vào lớp học cụ thể
- Có thể sử dụng command `php artisan teacher:check-classrooms --fix` để tự động gán teacher vào lớp học đầu tiên 