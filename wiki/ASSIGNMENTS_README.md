# Chức năng Bài tập (Assignments) cho Học viên

## Tổng quan
Chức năng bài tập cho phép học viên xem, làm và nộp bài tập với nhiều loại khác nhau như điền từ, upload ảnh, ghi âm và video.

## Tính năng chính

### 1. Xem danh sách bài tập
- **Route**: `/student/assignments`
- **Component**: `App\Livewire\Student\Assignments\Index`
- **Tính năng**:
  - Hiển thị tất cả bài tập của học viên
  - Lọc theo trạng thái (tất cả, chưa đến hạn, quá hạn, đã hoàn thành)
  - Lọc theo lớp học và giảng viên
  - Tìm kiếm theo tên bài tập
  - Phân trang

### 2. Xem chi tiết bài tập
- **Route**: `/student/assignments/{assignmentId}`
- **Component**: `App\Livewire\Student\Assignments\Show`
- **Tính năng**:
  - Hiển thị chi tiết bài tập
  - Xem tài liệu đính kèm và video hướng dẫn
  - Kiểm tra trạng thái bài tập (cần làm, đã hoàn thành, quá hạn)
  - Xem bài nộp và điểm số (nếu đã nộp)

### 3. Nộp bài tập
- **Route**: `/student/assignments/{assignmentId}/submit`
- **Component**: `App\Livewire\Student\Assignments\Submit`
- **Tính năng**:
      - Hỗ trợ 5 loại bài nộp:
      - **Điền từ**: Nhập text trực tiếp
      - **Tự luận**: Viết bài luận dài với cấu trúc phức tạp
      - **Upload ảnh**: Tải lên ảnh bài viết tay (JPG, PNG, GIF, tối đa 10MB)
      - **Ghi âm**: Tải lên file âm thanh (MP3, WAV, M4A, tối đa 50MB)
      - **Video**: Tải lên file video (MP4, AVI, MOV, tối đa 100MB)
  - Preview file trước khi nộp
  - Kiểm tra hạn nộp và trạng thái bài tập

### 4. Xem bài tập đã nộp
- **Route**: `/student/assignments/submissions`
- **Component**: `App\Livewire\Student\Assignments\MySubmissions`
- **Tính năng**:
  - Hiển thị tất cả bài tập đã nộp
  - Lọc theo trạng thái chấm điểm (đã chấm, chưa chấm)
  - Xem điểm số và nhận xét của giảng viên
  - Preview nội dung bài nộp

## Các loại bài tập hỗ trợ

### 1. Điền từ (Text)
- Học viên nhập text trực tiếp vào form
- Hỗ trợ định dạng văn bản cơ bản
- Giới hạn 10,000 ký tự

### 2. Tự luận (Essay)
- Dành cho bài luận dài với cấu trúc phức tạp
- Hỗ trợ viết bài luận có mở bài, thân bài, kết luận
- Giới hạn 50,000 ký tự
- Có hướng dẫn viết bài luận
- Hiển thị số ký tự đã viết

### 3. Upload ảnh (Image)
- Dành cho bài viết tay bằng tiếng Trung
- Hỗ trợ định dạng: JPG, PNG, GIF, WEBP
- Giới hạn kích thước: 10MB
- Preview ảnh trước khi nộp

### 4. Ghi âm (Audio)
- Dành cho luyện phát âm
- Hỗ trợ định dạng: MP3, WAV, M4A
- Giới hạn kích thước: 50MB
- Player âm thanh để nghe thử

### 5. Video (Video)
- Dành cho luyện nói và thuyết trình
- Hỗ trợ định dạng: MP4, AVI, MOV
- Giới hạn kích thước: 100MB
- Player video để xem thử

## Quy tắc hoạt động

### Kiểm tra quyền truy cập
- Chỉ học viên mới có thể truy cập
- Học viên chỉ thấy bài tập của các lớp mình đang học
- Kiểm tra quyền truy cập thông qua middleware `role:student`

### Kiểm tra hạn nộp
- Bài tập quá hạn không thể nộp
- Hiển thị thời gian còn lại hoặc đã quá hạn
- Cảnh báo khi gần đến hạn

### Kiểm tra trạng thái
- Bài tập chưa nộp: có thể làm
- Bài tập đã nộp: chỉ có thể xem
- Bài tập quá hạn: không thể nộp

## Cấu trúc Database

### Bảng `assignments`
- `id`: ID bài tập
- `class_id`: ID lớp học
- `title`: Tiêu đề bài tập
- `description`: Mô tả bài tập
- `deadline`: Hạn nộp
- `types`: Loại bài tập (JSON array)
- `attachment_path`: Đường dẫn tài liệu đính kèm
- `video_path`: Đường dẫn video hướng dẫn

### Bảng `assignment_submissions`
- `id`: ID bài nộp
- `assignment_id`: ID bài tập
- `student_id`: ID học viên
- `content`: Nội dung bài nộp (text hoặc đường dẫn file)
- `submission_type`: Loại bài nộp (text, image, audio, video)
- `score`: Điểm số
- `feedback`: Nhận xét của giảng viên
- `submitted_at`: Thời gian nộp

## Cài đặt và sử dụng

### 1. Chạy migration
```bash
php artisan migrate
```

### 2. Tạo symbolic link cho storage
```bash
php artisan storage:link
```

### 3. Chạy seeder để tạo dữ liệu mẫu
```bash
php artisan db:seed --class=AssignmentSeeder
```

### 4. Truy cập chức năng
- Đăng nhập với tài khoản học viên
- Truy cập `/student/assignments` để xem danh sách bài tập
- Sử dụng navigation để chuyển đổi giữa các trang

## Lưu ý quan trọng

1. **File upload**: Đảm bảo thư mục `storage/app/public` có quyền ghi
2. **Kích thước file**: Cấu hình `php.ini` để hỗ trợ upload file lớn
3. **Bảo mật**: Kiểm tra quyền truy cập file trong storage
4. **Performance**: Sử dụng pagination cho danh sách lớn
5. **Validation**: Kiểm tra định dạng và kích thước file

## Tùy chỉnh

### Thêm loại bài tập mới
1. Thêm loại vào array `types` trong component Submit
2. Cập nhật validation rules
3. Thêm xử lý trong method `submitAssignment`
4. Cập nhật view để hiển thị loại mới

### Thay đổi giới hạn file
1. Cập nhật validation rules trong component Submit
2. Cập nhật thông báo lỗi
3. Kiểm tra cấu hình server

### Tùy chỉnh giao diện
1. Chỉnh sửa các file view trong `resources/views/student/assignments/`
2. Sử dụng Tailwind CSS classes
3. Thêm JavaScript nếu cần thiết 