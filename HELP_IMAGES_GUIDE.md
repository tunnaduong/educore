# Hướng dẫn chèn ảnh vào trang trợ giúp

## 📁 Cấu trúc thư mục ảnh

Tạo thư mục: `public/images/help/`

## 📸 Danh sách ảnh cần chụp và chèn

### 1. Quản lý học sinh
- `add-student-form.png` - Form thêm học sinh mới
- `student-detail-tabs.png` - Trang chi tiết học sinh với các tab

### 2. Quản lý lớp học  
- `create-classroom-form.png` - Form tạo lớp học mới
- `assign-students-interface.png` - Giao diện phân công học sinh
- `classroom-progress-report.png` - Báo cáo tiến độ lớp học

### 3. Quản lý lịch học
- `create-schedule-form.png` - Form tạo lịch học mới
- `schedule-conflict-warning.png` - Cảnh báo xung đột lịch học
- `export-schedule-interface.png` - Giao diện xuất lịch học

### 4. Quản lý bài tập
- `create-assignment-form.png` - Form tạo bài tập mới
- `assignment-list-filters.png` - Danh sách bài tập với bộ lọc
- `grade-assignment-interface.png` - Giao diện chấm điểm bài tập
- `assignment-statistics-report.png` - Báo cáo thống kê bài tập

### 5. Quản lý bài kiểm tra
- `create-quiz-form.png` - Form tạo bài kiểm tra mới
- `add-questions-interface.png` - Giao diện thêm câu hỏi
- `quiz-results-report.png` - Báo cáo kết quả bài kiểm tra
- `question-analysis-detail.png` - Phân tích chi tiết từng câu hỏi

### 6. Quản lý bài học
- `create-lesson-form.png` - Form tạo bài học mới
- `organize-lesson-content.png` - Giao diện tổ chức nội dung bài học
- `lesson-progress-statistics.png` - Thống kê tiến độ học bài

### 7. Báo cáo và thống kê
- `dashboard-overview-report.png` - Dashboard báo cáo tổng quan
- `class-detail-report.png` - Báo cáo chi tiết lớp học
- `student-detail-report.png` - Báo cáo chi tiết học sinh
- `grade-distribution-chart.png` - Biểu đồ phân bố điểm số
- `auto-report-settings.png` - Cài đặt báo cáo tự động

### 8. Quản lý tài chính
- `finance-dashboard-overview.png` - Dashboard tài chính tổng quan
- `tuition-fee-management-list.png` - Danh sách quản lý học phí
- `add-expense-form.png` - Form thêm chi phí mới
- `financial-report-detail.png` - Báo cáo tài chính chi tiết

## 🔧 Cách chèn ảnh

### Bước 1: Chụp màn hình
1. Mở trang cần chụp trong hệ thống
2. Chụp màn hình (Ctrl + Shift + 4 trên Mac, Snipping Tool trên Windows)
3. Lưu ảnh với tên tương ứng vào thư mục `public/images/help/`

### Bước 2: Tối ưu ảnh
- Kích thước khuyến nghị: 800x600px hoặc 1200x800px
- Định dạng: PNG hoặc JPG
- Dung lượng: Dưới 500KB mỗi ảnh

### Bước 3: Chèn vào code
Ảnh đã được cấu hình sẵn trong file `resources/views/admin/help/index.blade.php` với cú pháp:

```html
<img src="{{ asset('images/help/ten-anh.png') }}" 
     alt="Mô tả ảnh" 
     class="img-fluid rounded shadow-sm" 
     style="max-width: 100%; height: auto;">
```

## 📝 Lưu ý
- Đảm bảo ảnh có độ phân giải tốt và dễ đọc
- Chụp toàn bộ màn hình hoặc vùng quan trọng
- Đặt tên file theo đúng quy ước đã định
- Kiểm tra hiển thị ảnh sau khi upload

## 🎯 Mục tiêu
Tạo hướng dẫn trực quan giúp người dùng dễ dàng hiểu và sử dụng hệ thống EduCore một cách hiệu quả.
