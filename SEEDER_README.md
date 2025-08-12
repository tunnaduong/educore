# Hướng dẫn sử dụng Seeder cho dự án EduCore

## Tổng quan

Dự án EduCore đã được tích hợp đầy đủ các seeder để tạo dữ liệu mẫu cho hệ thống quản lý giáo dục tiếng Trung. Các seeder được thiết kế để tạo dữ liệu thực tế và phù hợp với ngữ cảnh giáo dục tại Việt Nam.

## Danh sách Seeder

### 1. UserSeeder

-   **Chức năng**: Tạo users, students, classrooms và relationships
-   **Dữ liệu tạo**:
    -   1 admin
    -   8 giáo viên với tên tiếng Việt thực tế
    -   25 học viên với tên tiếng Việt thực tế
    -   8 lớp học với các cấp độ HSK 1-6
    -   Gán giáo viên và học viên vào lớp

### 2. AssignmentSeeder

-   **Chức năng**: Tạo bài tập cho các lớp
-   **Dữ liệu tạo**:
    -   2-4 bài tập cho mỗi lớp
    -   Các loại bài tập: viết, đọc, nghe, nói
    -   Deadline hợp lý (1-7 ngày)
    -   Mô tả chi tiết bằng tiếng Việt

### 3. AttendanceSeeder

-   **Chức năng**: Tạo dữ liệu điểm danh
-   **Dữ liệu tạo**:
    -   Điểm danh cho 3 tuần gần đây
    -   Tỷ lệ có mặt: 85-95%
    -   Các trạng thái: present, absent, late
    -   Ghi chú hợp lý

### 4. LessonSeeder

-   **Chức năng**: Tạo bài giảng cho các lớp
-   **Dữ liệu tạo**:
    -   3-5 bài giảng cho mỗi lớp
    -   Nội dung đa dạng theo cấp độ HSK
    -   File đính kèm và video (có thể null)

### 5. QuizSeeder

-   **Chức năng**: Tạo bài kiểm tra
-   **Dữ liệu tạo**:
    -   2-3 bài kiểm tra cho mỗi lớp
    -   Các loại: trắc nghiệm, tự luận, hỗn hợp
    -   Thời gian làm bài: 15-60 phút
    -   Điểm tối đa: 10-100 điểm

### 6. QuestionBankSeeder

-   **Chức năng**: Tạo ngân hàng câu hỏi
-   **Dữ liệu tạo**:
    -   50+ câu hỏi đa dạng
    -   Các loại: multiple_choice, true_false, essay
    -   Nội dung tiếng Việt và tiếng Trung
    -   Độ khó: easy, medium, hard

### 7. AssignmentSubmissionSeeder

-   **Chức năng**: Tạo bài nộp của học viên
-   **Dữ liệu tạo**:
    -   Bài nộp cho 70-90% học viên
    -   Điểm số và ghi chú của giáo viên
    -   File đính kèm (có thể null)

### 8. QuizResultSeeder

-   **Chức năng**: Tạo kết quả bài kiểm tra
-   **Dữ liệu tạo**:
    -   Kết quả cho 80-95% học viên
    -   Điểm số và thời gian làm bài
    -   Trạng thái và ghi chú

### 9. PaymentSeeder

-   **Chức năng**: Tạo giao dịch thanh toán
-   **Dữ liệu tạo**:
    -   30-40 giao dịch thanh toán
    -   Các loại: course_fee, material_fee, exam_fee, certificate_fee, other
    -   Số tiền hợp lý (500k - 5M VND)
    -   Trạng thái: pending, completed, failed, cancelled

### 10. ExpenseSeeder

-   **Chức năng**: Tạo chi phí
-   **Dữ liệu tạo**:
    -   15-20 chi phí
    -   Các loại: salary, material, utility, rent, equipment, marketing, maintenance, other
    -   Số tiền hợp lý theo loại chi phí

### 11. NotificationSeeder (giữ nguyên)

-   **Chức năng**: Tạo thông báo
-   **Dữ liệu tạo**: 30-50 thông báo đa dạng

### 12. ChatSeeder (giữ nguyên)

-   **Chức năng**: Tạo tin nhắn chat
-   **Dữ liệu tạo**: 50-100 tin nhắn đa dạng

## Cách sử dụng

### Chạy tất cả seeder

```bash
php artisan db:seed
```

### Chạy seeder cụ thể

```bash
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=AssignmentSeeder
php artisan db:seed --class=AttendanceSeeder
# ... và các seeder khác
```

### Reset database và chạy seeder

```bash
php artisan migrate:fresh --seed
```

## Thông tin đăng nhập mặc định

### Admin

-   **Email**: admin@gmail.com
-   **Password**: Admin@12

### Giáo viên

-   **Email**: dinhdanghung@educore.test
-   **Password**: password

### Học viên

-   **Email**: nguyenvana@educore.test
-   **Password**: password

## Lưu ý quan trọng

1. **Thứ tự chạy**: Các seeder được thiết kế để chạy theo thứ tự dependency. Không nên thay đổi thứ tự trong DatabaseSeeder.

2. **Dữ liệu thực tế**: Tất cả dữ liệu được tạo với nội dung tiếng Việt thực tế, phù hợp với ngữ cảnh giáo dục tiếng Trung tại Việt Nam.

3. **Relationships**: Các relationships giữa các model được thiết lập đúng cách, đảm bảo tính nhất quán của dữ liệu.

4. **Faker**: Sử dụng Faker với locale 'vi_VN' để tạo dữ liệu tiếng Việt.

5. **Timestamps**: Tất cả timestamps được tạo hợp lý, phản ánh thời gian thực tế.

## Tùy chỉnh

Để tùy chỉnh dữ liệu, bạn có thể:

1. Chỉnh sửa các mảng dữ liệu trong từng seeder
2. Thay đổi số lượng bản ghi được tạo
3. Điều chỉnh các tham số ngẫu nhiên
4. Thêm các loại dữ liệu mới

## Troubleshooting

### Lỗi thường gặp

1. **Lỗi foreign key**: Đảm bảo chạy seeder theo đúng thứ tự
2. **Lỗi duplicate**: Một số seeder có thể chạy nhiều lần, cần kiểm tra logic
3. **Lỗi memory**: Với dữ liệu lớn, có thể cần tăng memory limit

### Giải pháp

1. Chạy `php artisan migrate:fresh --seed` để reset hoàn toàn
2. Kiểm tra logs trong `storage/logs/laravel.log`
3. Chạy từng seeder riêng lẻ để debug
