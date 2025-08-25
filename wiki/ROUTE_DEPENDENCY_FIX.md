# 🔧 Khắc phục lỗi "Unable to resolve dependency [Parameter #0 [ <required> $id ]]"

## 🔍 Vấn đề
Teacher gặp lỗi dependency injection khi truy cập trang edit lesson:
```
Unable to resolve dependency [Parameter #0 [ <required> $id ]] in class App\Livewire\Teacher\Lessons\Edit
```

## 🛠️ Nguyên nhân và Giải pháp

### Nguyên nhân
1. **Parameter mismatch**: Component đang sử dụng `$id` nhưng route truyền `{lesson}`
2. **Model binding**: Không sử dụng Laravel model binding đúng cách
3. **Route parameter**: Route parameter không khớp với component parameter

### Giải pháp đã thực hiện

#### 1. **Sửa Model Binding**
```php
// Trước (SAI)
public function mount($id) { ... }

// Sau (ĐÚNG)
public function mount(Lesson $lesson) { ... }
```

#### 2. **Cập nhật Logic Authorization**
```php
// Kiểm tra quyền teacher
$this->lesson = Lesson::whereIn('classroom_id', $this->classrooms->pluck('id'))
    ->findOrFail($lesson->id);
```

#### 3. **Đảm bảo Route Parameter đúng**
```php
// Route đã đúng
Route::get('/teacher/lessons/{lesson}/edit', \App\Livewire\Teacher\Lessons\Edit::class)->name('lessons.edit');
```

## ✅ Các thay đổi đã thực hiện

### Components đã cập nhật:
- `app/Livewire/Teacher/Lessons/Edit.php`
  - ✅ Sử dụng `Lesson $lesson` thay vì `$id`
  - ✅ Thêm authorization check
  - ✅ Cải thiện error handling

- `app/Livewire/Teacher/Lessons/Show.php`
  - ✅ Sử dụng `Lesson $lesson` thay vì `$id`
  - ✅ Thêm authorization check
  - ✅ Thêm import Classroom

### Commands mới:
- `app/Console/Commands/TestTeacherRoutes.php` - Test routes và permissions

## 🧪 Test và Debug

### Bước 1: Test routes
```bash
php artisan test:teacher-routes
```

### Bước 2: Kiểm tra route list
```bash
php artisan route:list --name=teacher.lessons
```

### Bước 3: Test model binding
```bash
# Trong tinker
php artisan tinker
>>> $lesson = App\Models\Lesson::first();
>>> route('teacher.lessons.edit', $lesson);
```

## 🎯 Kết quả mong đợi

Sau khi áp dụng các thay đổi:
1. ✅ **Không còn lỗi** dependency injection
2. ✅ **Model binding** hoạt động đúng
3. ✅ **Authorization** kiểm tra quyền teacher
4. ✅ **Route generation** hoạt động bình thường
5. ✅ **Error handling** rõ ràng nếu không có quyền

## 🚨 Lưu ý quan trọng

### Model Binding
- Laravel sẽ tự động resolve Lesson model từ route parameter
- Component nhận được Lesson instance thay vì ID
- Cần kiểm tra quyền truy cập trong mount method

### Authorization
- Teacher chỉ có thể edit lesson của lớp họ đang dạy
- Sử dụng `findOrFail()` để tránh lỗi 404
- Log error nếu teacher không có quyền

### Route Parameters
- Route parameter `{lesson}` phải khớp với component parameter
- Sử dụng model binding để tự động resolve model
- Đảm bảo route name và parameter đúng

## 🔧 Troubleshooting

### Nếu vẫn gặp lỗi:
1. **Clear cache**: `php artisan route:clear && php artisan config:clear`
2. **Check route**: `php artisan route:list --name=teacher.lessons`
3. **Test binding**: `php artisan test:teacher-routes`
4. **Check logs**: `tail -f storage/logs/laravel.log`

### Debug steps:
1. Kiểm tra route parameter trong `web.php`
2. Kiểm tra component mount method
3. Kiểm tra model binding
4. Kiểm tra authorization logic 