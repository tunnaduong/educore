# Tính năng Kiểm tra Trùng Lịch Học

## Tổng quan

Tính năng này giúp phát hiện và quản lý các trường hợp trùng lịch học giữa các lớp học, đảm bảo học sinh không bị trùng lịch khi được gán vào nhiều lớp.

## Các thành phần chính

### 1. Helper Class: `ScheduleConflictHelper`

**Vị trí:** `app/Helpers/ScheduleConflictHelper.php`

**Chức năng:**
- Kiểm tra trùng lịch khi gán học sinh vào lớp mới
- Tính toán thời gian trùng chính xác
- Tạo thông báo trùng lịch bằng tiếng Việt

**Các phương thức chính:**
- `checkStudentScheduleConflict()`: Kiểm tra trùng lịch cho một học sinh
- `checkMultipleStudentsScheduleConflict()`: Kiểm tra trùng lịch cho nhiều học sinh
- `checkTimeConflict()`: Kiểm tra trùng thời gian giữa hai khoảng thời gian

### 2. Component Gán Học Sinh: `AssignStudents`

**Vị trí:** `app/Livewire/Admin/Classrooms/AssignStudents.php`

**Tính năng mới:**
- Tự động kiểm tra trùng lịch khi gán học sinh
- Hiển thị modal cảnh báo trùng lịch với scroll
- Cho phép gán bất chấp trùng lịch (với cảnh báo)

### 3. Component Cảnh Báo Trùng Lịch: `ScheduleConflictAlert`

**Vị trí:** `app/Livewire/Admin/Classrooms/ScheduleConflictAlert.php`

**Chức năng:**
- Hiển thị cảnh báo trùng lịch trong trang chi tiết lớp học
- Cho phép xem/ẩn chi tiết trùng lịch với scroll
- Hỗ trợ hiển thị nhiều học sinh trùng lịch

### 4. Báo Cáo Trùng Lịch: `ScheduleConflictReport`

**Vị trí:** `app/Livewire/Admin/Reports/ScheduleConflictReport.php`

**Chức năng:**
- Báo cáo tổng hợp tất cả trùng lịch trong hệ thống
- Lọc theo lớp học, học sinh
- Xem chi tiết từng trường hợp trùng lịch với modal scroll

### 5. Command Kiểm Tra: `CheckScheduleConflicts`

**Vị trí:** `app/Console/Commands/CheckScheduleConflicts.php`

**Chức năng:**
- Kiểm tra trùng lịch toàn bộ hệ thống
- Kiểm tra theo lớp cụ thể
- Kiểm tra theo học sinh cụ thể

## Cách sử dụng

### 1. Kiểm tra trùng lịch khi gán học sinh

Khi admin gán học sinh vào lớp học:
1. Hệ thống tự động kiểm tra trùng lịch
2. Nếu có trùng lịch, hiển thị modal cảnh báo với scroll
3. Admin có thể chọn gán bất chấp trùng lịch hoặc hủy bỏ

### 2. Xem cảnh báo trùng lịch trong lớp học

Trong trang chi tiết lớp học:
- Hiển thị cảnh báo nếu có học sinh trùng lịch
- Click "Xem chi tiết" để xem thông tin cụ thể với scroll
- Hỗ trợ hiển thị nhiều học sinh trùng lịch

### 3. Báo cáo trùng lịch

Truy cập: `/admin/reports/schedule-conflicts`
- Xem tất cả trùng lịch trong hệ thống
- Lọc theo lớp học, học sinh
- Xem chi tiết từng trường hợp với modal scroll

### 4. Command line

```bash
# Kiểm tra toàn bộ hệ thống
php artisan schedule:check-conflicts

# Kiểm tra lớp cụ thể
php artisan schedule:check-conflicts --classroom=1

# Kiểm tra học sinh cụ thể
php artisan schedule:check-conflicts --student=1
```

## Ví dụ trùng lịch

**Trường hợp 1: Trùng hoàn toàn**
- Lớp A: Thứ 2, 4, 6 - 19:00-21:30
- Lớp B: Thứ 2, 4, 6 - 19:00-21:30
- **Kết quả:** Trùng hoàn toàn

**Trường hợp 2: Trùng một phần**
- Lớp A: Thứ 2, 4, 6 - 19:00-21:30
- Lớp B: Thứ 2, 4, 6 - 18:30-21:00
- **Kết quả:** Trùng từ 19:00-21:00

**Trường hợp 3: Không trùng**
- Lớp A: Thứ 2, 4, 6 - 19:00-21:30
- Lớp B: Thứ 3, 5, 7 - 19:00-21:30
- **Kết quả:** Không trùng

## Cải tiến UI/UX

### 1. Scroll Support cho nhiều học sinh

**Vấn đề trước đây:**
- Khi có nhiều hơn 3 học sinh trùng lịch, không thể scroll xuống
- Modal bị giới hạn chiều cao
- Không thể xem đầy đủ thông tin

**Giải pháp đã áp dụng:**
- Thêm `max-height` và `overflow-y: auto` cho các container
- Sử dụng `modal-xl` thay vì `modal-lg` cho modal lớn hơn
- Thêm thông báo scroll khi có nhiều học sinh

### 2. Cải tiến Alert Component

```blade
<!-- Trước đây -->
<div class="alert">
    @if($showConflicts)
        <div class="mt-3">
            <!-- Nội dung bị giới hạn -->
        </div>
    @endif
</div>

<!-- Sau khi cải tiến -->
<div class="alert">
    @if($showConflicts)
        <div class="mt-3" style="max-height: 300px; overflow-y: auto;">
            <!-- Nội dung có thể scroll -->
        </div>
        @if(count($conflicts) > 3)
            <small class="text-muted">
                Có thể scroll để xem tất cả {{ count($conflicts) }} học sinh
            </small>
        @endif
    @endif
</div>
```

### 3. Cải tiến Modal

```blade
<!-- Modal với scroll support -->
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
            <!-- Nội dung có thể scroll -->
        </div>
    </div>
</div>
```

### 4. Responsive Design

- Sử dụng `align-items-start` thay vì `align-items-center` cho layout tốt hơn
- Thêm `mt-1` cho icons để căn chỉnh tốt hơn
- Sử dụng `flex-grow-1` cho responsive layout

## Cấu trúc dữ liệu

### Schedule Format
```json
{
    "days": ["Monday", "Wednesday", "Friday"],
    "time": "19:00 - 21:30"
}
```

### Conflict Result
```php
[
    'hasConflict' => true,
    'conflicts' => [
        [
            'classroom' => Classroom,
            'conflictingDays' => ['Monday', 'Wednesday'],
            'newTime' => '19:00 - 21:30',
            'currentTime' => '18:30 - 21:00',
            'overlapTime' => '19:00 - 21:00',
            'message' => 'Trùng lịch với lớp HSK2-K1 vào Thứ 2, Thứ 4 từ 19:00 - 21:00'
        ]
    ]
]
```

## Cải tiến tương lai

1. **Thông báo email:** Gửi email cảnh báo cho admin khi phát hiện trùng lịch
2. **Gợi ý lịch học:** Đề xuất lịch học thay thế khi có trùng lịch
3. **Lịch sử trùng lịch:** Lưu lịch sử các trường hợp trùng lịch đã xử lý
4. **Tự động điều chỉnh:** Tự động đề xuất điều chỉnh lịch học để tránh trùng lịch
5. **Export báo cáo:** Xuất báo cáo trùng lịch ra PDF/Excel
6. **Real-time notifications:** Thông báo real-time khi phát hiện trùng lịch

## Troubleshooting

### Lỗi thường gặp

1. **Không phát hiện trùng lịch:**
   - Kiểm tra định dạng schedule trong database
   - Đảm bảo schedule có đúng format JSON

2. **Modal không hiển thị:**
   - Kiểm tra JavaScript console
   - Đảm bảo Bootstrap CSS/JS đã load

3. **Command không chạy:**
   - Kiểm tra namespace và autoload
   - Đảm bảo command đã được register trong Kernel

4. **Lỗi RootTagMissingFromViewException:**
   - **Nguyên nhân:** Livewire component view không có root HTML tag
   - **Giải pháp:** Đảm bảo tất cả Livewire component views đều có một root `<div>` tag
   - **Ví dụ:**
     ```blade
     <!-- ĐÚNG -->
     <div>
         @if($condition)
             <div>Content</div>
         @endif
     </div>
     
     <!-- SAI -->
     @if($condition)
         <div>Content</div>
     @endif
     ```

5. **Scroll không hoạt động:**
   - Kiểm tra CSS `overflow-y: auto` đã được áp dụng
   - Đảm bảo container có `max-height` được set
   - Kiểm tra z-index của modal

### Debug

```php
// Kiểm tra schedule của lớp
$classroom = Classroom::find(1);
dd($classroom->schedule);

// Kiểm tra trùng lịch thủ công
$conflict = ScheduleConflictHelper::checkStudentScheduleConflict($student, $classroom);
dd($conflict);
```

### Khắc phục lỗi RootTagMissingFromViewException

1. **Kiểm tra tất cả Livewire component views:**
   ```bash
   # Tìm tất cả file .blade.php trong thư mục livewire
   find resources/views/livewire -name "*.blade.php"
   ```

2. **Đảm bảo mỗi view có root tag:**
   ```blade
   <!-- Luôn bắt đầu với một div -->
   <div>
       <!-- Nội dung component -->
   </div>
   ```

3. **Clear cache sau khi sửa:**
   ```bash
   php artisan optimize:clear
   php artisan view:clear
   php artisan config:clear
   ```

4. **Kiểm tra logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

### Khắc phục vấn đề Scroll

1. **Kiểm tra CSS:**
   ```css
   .scrollable-content {
       max-height: 300px;
       overflow-y: auto;
       overflow-x: hidden;
   }
   ```

2. **Kiểm tra Modal:**
   ```blade
   <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
       <!-- Nội dung -->
   </div>
   ```

3. **Test với nhiều dữ liệu:**
   ```bash
   # Tạo dữ liệu test với nhiều học sinh
   php artisan schedule:check-conflicts --classroom=1
   ```
