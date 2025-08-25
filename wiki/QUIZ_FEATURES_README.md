# Hướng dẫn sử dụng chức năng Quiz/Quiz

## Tổng quan
Chức năng Quiz cho phép giáo viên tạo, quản lý và theo dõi kết quả các bài kiểm tra trực tuyến cho học sinh.

## Các tính năng chính

### 1. Tạo bài kiểm tra mới
- **Đường dẫn**: `/teacher/quizzes/create`
- **Chức năng**:
  - Nhập thông tin cơ bản (tiêu đề, mô tả, lớp học)
  - Thiết lập hạn nộp và thời gian làm bài
  - Thêm các loại câu hỏi:
    - **Trắc nghiệm**: Nhiều lựa chọn với đáp án đúng
    - **Đúng/Sai**: Câu hỏi đúng hoặc sai
    - **Tự luận**: Câu hỏi mở, cần chấm điểm thủ công
  - Thiết lập điểm cho từng câu hỏi
  - Thêm giải thích cho đáp án

### 2. Quản lý danh sách bài kiểm tra
- **Đường dẫn**: `/teacher/quizzes`
- **Chức năng**:
  - Xem danh sách tất cả bài kiểm tra
  - Tìm kiếm theo tên hoặc mô tả
  - Lọc theo lớp học và trạng thái
  - Thao tác: Xem chi tiết, Chỉnh sửa, Xem kết quả, Xóa

### 3. Chỉnh sửa bài kiểm tra
- **Đường dẫn**: `/teacher/quizzes/{id}/edit`
- **Chức năng**:
  - Cập nhật thông tin bài kiểm tra
  - Thêm/sửa/xóa câu hỏi
  - Chỉnh sửa đáp án và điểm số

### 4. Xem chi tiết bài kiểm tra
- **Đường dẫn**: `/teacher/quizzes/{id}`
- **Chức năng**:
  - Hiển thị thông tin đầy đủ bài kiểm tra
  - Xem danh sách câu hỏi và đáp án
  - Thống kê cơ bản về kết quả

### 5. Xem kết quả chi tiết
- **Đường dẫn**: `/teacher/quizzes/{id}/results`
- **Chức năng**:
  - Thống kê tổng quan (số bài làm, điểm trung bình, tỷ lệ đạt)
  - Danh sách kết quả của từng học sinh
  - Tìm kiếm và lọc theo điểm
  - Phân loại kết quả (Xuất sắc, Tốt, Trung bình, Yếu)

## Cấu trúc dữ liệu

### Model Quiz
```php
protected $fillable = [
    'class_id',      // ID lớp học
    'title',         // Tiêu đề bài kiểm tra
    'description',   // Mô tả
    'questions',     // JSON chứa câu hỏi
    'deadline',      // Hạn nộp
    'time_limit',    // Thời gian làm bài (phút)
];
```

### Cấu trúc câu hỏi (JSON)
```json
{
    "question": "Nội dung câu hỏi",
    "type": "multiple_choice|true_false|essay",
    "score": 1,
    "options": ["A", "B", "C", "D"],  // Chỉ cho trắc nghiệm
    "correct_answer": "A",            // Đáp án đúng
    "explanation": "Giải thích"       // Tùy chọn
}
```

## Giao diện

### Thiết kế
- Sử dụng Bootstrap 5 cho giao diện responsive
- Layout card-based với shadow và spacing phù hợp
- Icons Bootstrap Icons cho trực quan
- Màu sắc nhất quán với theme hiện có

### Responsive
- Desktop: Layout 2 cột (8-4) cho form tạo/chỉnh sửa
- Tablet: Layout stack cho các card
- Mobile: Tối ưu cho màn hình nhỏ

## Bảo mật

### Phân quyền
- Chỉ giáo viên có thể tạo/chỉnh sửa bài kiểm tra
- Giáo viên chỉ có thể quản lý bài kiểm tra của lớp mình dạy
- Validation đầy đủ cho tất cả input

### Validation
- Tiêu đề: Bắt buộc, tối đa 255 ký tự
- Mô tả: Tùy chọn, tối đa 1000 ký tự
- Lớp học: Bắt buộc, phải tồn tại
- Hạn nộp: Phải sau thời gian hiện tại
- Thời gian làm bài: 1-480 phút
- Câu hỏi: Ít nhất 1 câu hỏi hợp lệ

## Tương lai

### Tính năng có thể mở rộng
- Import/Export bài kiểm tra
- Template câu hỏi
- Random câu hỏi
- Giới hạn số lần làm bài
- Thông báo kết quả tự động
- Biểu đồ thống kê nâng cao
- Xuất báo cáo PDF

### Tối ưu hóa
- Cache kết quả thống kê
- Lazy loading cho danh sách lớn
- Real-time updates với WebSocket
- Offline support cho mobile

## Hướng dẫn sử dụng

### Tạo bài kiểm tra mới
1. Vào trang `/teacher/quizzes`
2. Click "Tạo bài kiểm tra mới"
3. Điền thông tin cơ bản
4. Thêm câu hỏi bằng nút "Thêm câu hỏi"
5. Chọn loại câu hỏi và điền nội dung
6. Thiết lập đáp án và điểm
7. Click "Tạo bài kiểm tra"

### Xem kết quả
1. Vào trang danh sách bài kiểm tra
2. Click "Xem kết quả" hoặc "Xem chi tiết"
3. Xem thống kê tổng quan
4. Sử dụng bộ lọc để tìm kiếm học sinh cụ thể

### Chỉnh sửa bài kiểm tra
1. Vào trang danh sách bài kiểm tra
2. Click "Chỉnh sửa"
3. Thay đổi thông tin cần thiết
4. Click "Cập nhật bài kiểm tra"

## Troubleshooting

### Lỗi thường gặp
- **Không thể tạo bài kiểm tra**: Kiểm tra quyền giáo viên và lớp học
- **Câu hỏi không hiển thị**: Đảm bảo cấu trúc JSON hợp lệ
- **Kết quả không cập nhật**: Refresh trang hoặc kiểm tra cache

### Debug
- Kiểm tra logs trong `storage/logs/laravel.log`
- Sử dụng Laravel Debugbar để debug
- Kiểm tra database migrations đã chạy đầy đủ 