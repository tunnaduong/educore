# Hướng dẫn sử dụng chức năng Quiz - EduCore

## Tổng quan
Chức năng Quiz được xây dựng với Livewire và Bootstrap 5, hỗ trợ nhiều loại câu hỏi, thời gian đếm ngược, và auto-save.

## Tính năng chính

### 1. Loại câu hỏi hỗ trợ
- ✅ **Multiple Choice** - Câu hỏi trắc nghiệm
- ✅ **Fill in the Blank** - Điền vào chỗ trống
- ✅ **Drag & Drop** - Kéo thả
- ✅ **Essay** - Tự luận

### 2. Thời gian đếm ngược
- ✅ **Timer hiển thị** với màu sắc thay đổi
- ✅ **Cảnh báo tự động** khi còn 5 phút và 1 phút
- ✅ **Tự động nộp bài** khi hết thời gian
- ✅ **Browser notifications**

### 3. Auto-save
- ✅ **Tự động lưu** câu trả lời
- ✅ **Tiếp tục làm bài** nếu bị gián đoạn
- ✅ **Không mất dữ liệu** khi refresh trang

### 4. User Experience
- ✅ **Progress bar** hiển thị tiến độ
- ✅ **Navigation** giữa các câu hỏi
- ✅ **Visual indicators** cho câu đã trả lời
- ✅ **Confirmation dialog** khi nộp bài

## Cấu trúc Files

### Components
```
app/Livewire/Student/Quiz/DoQuiz.php          # Main quiz component
app/Livewire/Student/Quiz/Index.php           # Quiz list
app/Livewire/Student/Quiz/Review.php          # Quiz review
```

### Views
```
resources/views/student/quiz/do-quiz.blade.php  # Main quiz interface
resources/views/student/quiz/index.blade.php    # Quiz list
resources/views/student/quiz/review.blade.php   # Quiz review
```

### Models
```
app/Models/Quiz.php                            # Quiz model
app/Models/QuizResult.php                      # Quiz result model
```

### Commands
```
app/Console/Commands/TestQuizSystem.php        # Test command
```

## Cách sử dụng

### 1. Truy cập Quiz
```
http://localhost/student/quizzes
```

### 2. Làm bài kiểm tra
```
http://localhost/student/quizzes/{quiz_id}/do
```

### 3. Xem kết quả
```
http://localhost/student/quizzes/{quiz_id}/review
```

### 4. Test system
```bash
# Kiểm tra quiz system
php artisan quiz:test --check

# Tạo quiz test
php artisan quiz:test --create
```

## Tính năng Timer

### 1. Hiển thị thời gian
- **Màu xanh**: Thời gian còn nhiều (>10 phút)
- **Màu vàng**: Cảnh báo (≤10 phút)
- **Màu đỏ**: Khẩn cấp (≤5 phút)

### 2. Cảnh báo tự động
- **5 phút cuối**: Alert + Notification
- **1 phút cuối**: Alert khẩn cấp + Notification
- **Hết thời gian**: Tự động nộp bài

### 3. Animations
- **Pulse**: Khi thời gian sắp hết
- **Shake**: Khi hết thời gian

## Auto-save Features

### 1. Real-time saving
- **Radio buttons**: Lưu ngay khi chọn
- **Text input**: Lưu sau 500ms
- **Textarea**: Lưu sau 1000ms
- **Select**: Lưu ngay khi chọn

### 2. Resume functionality
- Tự động load câu trả lời đã lưu
- Tiếp tục từ câu hỏi cuối cùng
- Không mất dữ liệu khi refresh

## Progress Tracking

### 1. Visual indicators
- **Grey**: Chưa trả lời
- **Green**: Đã trả lời
- **Blue**: Câu hiện tại
- **Checkmark**: Xác nhận đã trả lời

### 2. Progress bar
- Hiển thị tiến độ làm bài
- Số câu đã làm / tổng số câu

## Security Features

### 1. Access control
- Chỉ học sinh trong lớp mới được làm
- Kiểm tra deadline
- Chặn làm lại nếu đã nộp

### 2. Data protection
- CSRF protection
- Input validation
- SQL injection prevention

## Troubleshooting

### 1. Timer không hiển thị
- Kiểm tra `time_limit` trong database
- Đảm bảo quiz có thời gian giới hạn
- Kiểm tra JavaScript console

### 2. Auto-save không hoạt động
- Kiểm tra database connection
- Kiểm tra permissions
- Kiểm tra Livewire events

### 3. Không thể truy cập quiz
- Kiểm tra quyền truy cập lớp học
- Kiểm tra deadline
- Kiểm tra trạng thái lớp học

## Database Schema

### Quizzes Table
```sql
- id (primary key)
- class_id (foreign key)
- title (string)
- description (text)
- questions (json)
- time_limit (integer, minutes)
- deadline (datetime)
- created_at, updated_at
```

### Quiz Results Table
```sql
- id (primary key)
- quiz_id (foreign key)
- student_id (foreign key)
- score (integer)
- answers (json)
- started_at (datetime)
- submitted_at (datetime)
- duration (integer, seconds)
```

## Performance

### 1. Optimization
- Lazy loading questions
- Efficient database queries
- Minimal JavaScript

### 2. Caching
- Quiz data caching
- Result caching
- Session storage

## Customization

### 1. Timer styling
```css
#timer-container {
    transition: all 0.3s ease;
}

.animate__pulse {
    animation: pulse 1s infinite;
}
```

### 2. Question types
Thêm loại câu hỏi mới trong `DoQuiz.php`:
```php
case 'new_type':
    // Handle new question type
    break;
```

### 3. Scoring system
Tùy chỉnh cách tính điểm trong `calculateQuestionScore()`:
```php
public function calculateQuestionScore($question, $answer)
{
    // Custom scoring logic
}
```

## Testing

### 1. Manual testing
- Tạo quiz test với `php artisan quiz:test --create`
- Test các loại câu hỏi khác nhau
- Test timer functionality
- Test auto-save

### 2. Automated testing
```bash
php artisan test --filter=QuizTest
```

## Deployment

### 1. Production setup
- Configure database
- Set up caching
- Configure notifications

### 2. Monitoring
- Monitor quiz completion rates
- Track timer accuracy
- Monitor auto-save success

## Support

Nếu gặp vấn đề:
1. Kiểm tra logs trong `storage/logs/laravel.log`
2. Chạy `php artisan quiz:test --check`
3. Kiểm tra browser console
4. Liên hệ developer team

## Changelog

### v1.0.0 (Current)
- ✅ Timer functionality
- ✅ Auto-save features
- ✅ Multiple question types
- ✅ Progress tracking
- ✅ Security features
- ✅ Test commands

### Future Features
- Audio questions
- Image questions
- Video questions
- Advanced scoring
- Analytics dashboard 
