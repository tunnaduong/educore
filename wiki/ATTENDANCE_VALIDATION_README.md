# Chức năng Validation Điểm Danh

## Tổng quan

Chức năng này đã được thêm vào để ngăn chặn việc điểm danh quá khứ, tương lai và đảm bảo rằng điểm danh chỉ có thể được thực hiện trong thời gian học hợp lệ.

## Các tính năng chính

### 1. Không cho phép điểm danh tương lai
- Không thể điểm danh cho các ngày trong tương lai
- Hiển thị thông báo rõ ràng khi cố gắng điểm danh tương lai

### 2. Không cho phép điểm danh quá khứ
- Không thể điểm danh cho các ngày đã qua (trừ ngày hôm nay)
- Hiển thị thông báo rõ ràng khi cố gắng điểm danh quá khứ

### 3. Điểm danh trong khung thời gian mở rộng
- Cho phép điểm danh trước 15 phút trước giờ học
- Cho phép điểm danh sau 15 phút sau giờ học
- Ví dụ: Lớp học từ 8:25 - 11:00 thì có thể điểm danh từ 8:10 - 11:15
- Kiểm tra ngày học theo lịch đã cấu hình

### 4. Validation theo lịch học
- Kiểm tra xem ngày đã chọn có phải là ngày học không
- Chỉ cho phép điểm danh vào các ngày có lịch học

## Cách hoạt động

### Logic validation

1. **Kiểm tra ngày tương lai**:
   ```php
   if ($selectedDate->isFuture()) {
       return ['can' => false, 'message' => 'Không thể điểm danh cho ngày trong tương lai.'];
   }
   ```

2. **Kiểm tra ngày quá khứ**:
   ```php
   if ($selectedDate->isPast() && !$selectedDate->isToday()) {
       return ['can' => false, 'message' => 'Không thể điểm danh cho ngày trong quá khứ.'];
   }
   ```

3. **Kiểm tra ngày học**:
   ```php
   $dayOfWeek = $selectedDate->format('l'); // Monday, Tuesday, etc.
   if (!in_array($dayOfWeek, $days)) {
       return ['can' => false, 'message' => 'Ngày này không phải là ngày học của lớp.'];
   }
   ```

4. **Kiểm tra thời gian điểm danh (mở rộng 15 phút)**:
   ```php
   if ($selectedDate->isToday()) {
       // Cho phép điểm danh trước 15 phút trước giờ học và sau 15 phút sau giờ học
       $attendanceStartTime = $classStartTime->copy()->subMinutes(15);
       $attendanceEndTime = $classEndTime->copy()->addMinutes(15);

       // Kiểm tra xem đã đến thời gian điểm danh chưa
       if ($now->isBefore($attendanceStartTime)) {
           return ['can' => false, 'message' => 'Chưa đến thời gian điểm danh. Chỉ có thể điểm danh từ ' . $attendanceStartTime->format('H:i') . ' đến ' . $attendanceEndTime->format('H:i') . '.'];
       }

       // Kiểm tra xem đã qua thời gian điểm danh chưa
       if ($now->isAfter($attendanceEndTime)) {
           return ['can' => false, 'message' => 'Đã qua thời gian điểm danh. Không thể điểm danh lại.'];
       }
   }
   ```

### Các file đã được cập nhật

1. **Models**:
   - `app/Models/Attendance.php`: Thêm method `canTakeAttendance()` với logic mới

2. **Livewire Components**:
   - `app/Livewire/Admin/Attendance/TakeAttendance.php`: Thêm validation logic
   - `app/Livewire/Teacher/Attendance/TakeAttendance.php`: Thêm validation logic

3. **Views**:
   - `resources/views/admin/attendance/take-attendance.blade.php`: Hiển thị thông báo và disable controls
   - `resources/views/teacher/attendance/take-attendance.blade.php`: Hiển thị thông báo và disable controls

4. **Database**:
   - `database/migrations/2025_01_27_000000_add_indexes_to_attendances_table.php`: Thêm indexes cho performance

5. **Tests**:
   - `tests/Feature/AttendanceValidationTest.php`: Test cases cho validation mới

## Sử dụng

### Trong code

```php
// Kiểm tra xem có thể điểm danh không
$result = Attendance::canTakeAttendance($classroom, $date);
if ($result['can']) {
    // Có thể điểm danh
} else {
    // Không thể điểm danh, lý do: $result['message']
}
```

### Trong Livewire component

```php
public function checkAttendancePermission()
{
    $result = Attendance::canTakeAttendance($this->classroom, $this->selectedDate);
    $this->canTakeAttendance = $result['can'];
    $this->attendanceMessage = $result['message'];
}
```

## Ví dụ thực tế

### Tình huống 1: Điểm danh tương lai
- **Lịch học**: Thứ 2, 4, 6 từ 14:30 - 17:00
- **Ngày hiện tại**: 2/6/2025
- **Ngày muốn điểm danh**: 4/6/2025

**Kết quả**: Không thể điểm danh vì không thể điểm danh cho ngày trong tương lai.

### Tình huống 2: Điểm danh trước giờ học
- **Lịch học**: Thứ 2, 4, 6 từ 14:30 - 17:00
- **Ngày**: 4/6/2025 (Thứ 2)
- **Thời gian hiện tại**: 14:10 (trước giờ học 20 phút)

**Kết quả**: Có thể điểm danh vì đã đến thời gian điểm danh (từ 14:15 - 17:15).

### Tình huống 3: Điểm danh sau giờ học
- **Lịch học**: Thứ 2, 4, 6 từ 14:30 - 17:00
- **Ngày**: 4/6/2025 (Thứ 2)
- **Thời gian hiện tại**: 17:10 (sau giờ học 10 phút)

**Kết quả**: Có thể điểm danh vì vẫn trong thời gian điểm danh (từ 14:15 - 17:15).

### Tình huống 4: Điểm danh quá sớm
- **Lịch học**: Thứ 2, 4, 6 từ 14:30 - 17:00
- **Ngày**: 4/6/2025 (Thứ 2)
- **Thời gian hiện tại**: 14:00 (trước giờ học 30 phút)

**Kết quả**: Không thể điểm danh vì chưa đến thời gian điểm danh. Chỉ có thể điểm danh từ 14:15 đến 17:15.

### Tình huống 5: Điểm danh quá muộn
- **Lịch học**: Thứ 2, 4, 6 từ 14:30 - 17:00
- **Ngày**: 4/6/2025 (Thứ 2)
- **Thời gian hiện tại**: 17:20 (sau giờ học 20 phút)

**Kết quả**: Không thể điểm danh vì đã qua thời gian điểm danh.

### Tình huống 6: Điểm danh trong giờ học
- **Lịch học**: Thứ 2, 4, 6 từ 14:30 - 17:00
- **Ngày**: 4/6/2025 (Thứ 2)
- **Thời gian hiện tại**: 15:30 (trong giờ học)

**Kết quả**: Có thể điểm danh bình thường.

### Tình huống 7: Điểm danh ngày không phải ngày học
- **Lịch học**: Thứ 2, 4, 6 từ 14:30 - 17:00
- **Ngày**: 3/6/2025 (Thứ 3)

**Kết quả**: Không thể điểm danh vì không phải ngày học.

## Quyền hạn

### Giáo viên và Admin
- ✅ Điểm danh trong thời gian học (từ giờ bắt đầu đến giờ kết thúc)
- ✅ Điểm danh trước 15 phút trước giờ học
- ✅ Điểm danh sau 15 phút sau giờ học
- ✅ Điểm danh ngày hôm nay (trong khung thời gian mở rộng)
- ❌ Điểm danh quá khứ
- ❌ Điểm danh tương lai
- ❌ Điểm danh quá sớm (trước 15 phút trước giờ học)
- ❌ Điểm danh quá muộn (sau 15 phút sau giờ học)
- ❌ Điểm danh ngày không phải ngày học

## Lưu ý

1. **Timezone**: Hệ thống sử dụng timezone mặc định của ứng dụng
2. **Performance**: Đã thêm indexes cho các truy vấn thường xuyên
3. **User Experience**: Hiển thị thông báo rõ ràng và disable controls khi không thể điểm danh
4. **Backward Compatibility**: Không ảnh hưởng đến dữ liệu hiện có
5. **Security**: Chỉ cho phép điểm danh trong thời gian học hợp lệ

## Testing

Chạy tests để kiểm tra chức năng:

```bash
php artisan test tests/Feature/AttendanceValidationTest.php
```

## Migration

Chạy migration để thêm indexes:

```bash
php artisan migrate
``` 