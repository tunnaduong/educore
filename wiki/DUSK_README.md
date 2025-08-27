# Laravel Dusk - Hướng dẫn sử dụng

## Cài đặt

Laravel Dusk đã được cài đặt thành công trong dự án này.

## Cấu hình

### File cấu hình
- `tests/DuskTestCase.php` - File cấu hình chính cho Dusk
- `.env.dusk.local` - File môi trường cho testing

### ChromeDriver
ChromeDriver sẽ được tự động tải khi chạy tests lần đầu tiên.

## Test Cases đã tạo

### 1. AuthTest.php
**Chức năng:** Test authentication
- `test_admin_login_success()` - Đăng nhập admin thành công
- `test_teacher_login_success()` - Đăng nhập teacher thành công  
- `test_student_login_success()` - Đăng nhập student thành công
- `test_login_failure()` - Đăng nhập thất bại
- `test_logout()` - Đăng xuất

### 2. AdminTest.php
**Chức năng:** Test các chức năng admin
- `test_create_user()` - Tạo user mới
- `test_create_classroom()` - Tạo classroom mới
- `test_manage_students()` - Quản lý students
- `test_view_reports()` - Xem báo cáo
- `test_finance_management()` - Quản lý tài chính

### 3. TeacherTest.php
**Chức năng:** Test các chức năng teacher
- `test_create_lesson()` - Tạo bài học mới
- `test_create_assignment()` - Tạo bài tập mới
- `test_take_attendance()` - Điểm danh học sinh
- `test_grade_assignment()` - Chấm điểm bài tập
- `test_create_quiz()` - Tạo quiz
- `test_view_class_report()` - Xem báo cáo lớp học

### 4. StudentTest.php
**Chức năng:** Test các chức năng student
- `test_view_lessons()` - Xem bài học
- `test_view_assignments()` - Xem bài tập
- `test_submit_assignment()` - Nộp bài tập
- `test_take_quiz()` - Làm quiz
- `test_view_schedule()` - Xem lịch học
- `test_view_grades()` - Xem điểm số
- `test_chat_with_teacher()` - Chat với giáo viên
- `test_view_notifications()` - Xem thông báo

### 5. AITest.php
**Chức năng:** Test các chức năng AI
- `test_ai_grading()` - AI chấm điểm bài tập
- `test_ai_quiz_generator()` - AI tạo quiz
- `test_ai_chat()` - AI trả lời câu hỏi
- `test_ai_essay_analysis()` - AI phân tích bài viết
- `test_ai_lesson_generator()` - AI tạo bài học

### 6. NavigationTest.php
**Chức năng:** Test navigation và UI
- `test_admin_navigation()` - Navigation menu admin
- `test_teacher_navigation()` - Navigation menu teacher
- `test_student_navigation()` - Navigation menu student
- `test_responsive_design()` - Responsive design
- `test_breadcrumb_navigation()` - Breadcrumb navigation
- `test_search_functionality()` - Chức năng tìm kiếm
- `test_pagination()` - Phân trang
- `test_modal_dialogs()` - Modal dialogs

### 7. ErrorHandlingTest.php
**Chức năng:** Test error handling và validation
- `test_user_validation_errors()` - Validation errors khi tạo user
- `test_classroom_validation_errors()` - Validation errors khi tạo classroom
- `test_404_error_page()` - Trang lỗi 404
- `test_403_forbidden_error()` - Lỗi 403 forbidden
- `test_invalid_email_validation()` - Validation email không hợp lệ
- `test_password_confirmation_validation()` - Validation xác nhận mật khẩu
- `test_file_upload_validation()` - Validation upload file
- `test_duplicate_email_validation()` - Validation email trùng lặp
- `test_required_field_validation()` - Validation trường bắt buộc

## Chạy Tests

### Cách 1: Chạy tất cả tests
```bash
php artisan dusk
```

### Cách 2: Sử dụng script
- Windows: `dusk-test.bat`
- Linux/Mac: `./dusk-test.sh`

### Cách 3: Chạy test cụ thể
```bash
# Chạy test authentication
php artisan dusk tests/Browser/AuthTest.php

# Chạy test admin
php artisan dusk tests/Browser/AdminTest.php

# Chạy test teacher
php artisan dusk tests/Browser/TeacherTest.php

# Chạy test student
php artisan dusk tests/Browser/StudentTest.php

# Chạy test AI
php artisan dusk tests/Browser/AITest.php

# Chạy test navigation
php artisan dusk tests/Browser/NavigationTest.php

# Chạy test error handling
php artisan dusk tests/Browser/ErrorHandlingTest.php
```

### Cách 4: Chạy method cụ thể
```bash
php artisan dusk --filter test_admin_login_success
```

## Tạo Test Mới

```bash
php artisan dusk:make TênTest
```

## Cấu trúc Test

```php
<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    public function test_example(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Nội dung cần kiểm tra');
        });
    }
}
```

## Các phương thức Dusk thường dùng

### Navigation
- `visit('/path')` - Truy cập URL
- `back()` - Quay lại trang trước
- `refresh()` - Refresh trang
- `resize(1920, 1080)` - Thay đổi kích thước browser

### Interaction
- `type('selector', 'text')` - Nhập text
- `click('selector')` - Click element
- `press('text')` - Click button theo text
- `select('selector', 'value')` - Chọn option
- `attach('selector', 'file_path')` - Upload file

### Assertions
- `assertSee('text')` - Kiểm tra có text
- `assertDontSee('text')` - Kiểm tra không có text
- `assertPathIs('/path')` - Kiểm tra URL
- `assertVisible('selector')` - Kiểm tra element hiển thị
- `assertNotVisible('selector')` - Kiểm tra element không hiển thị

### Waiting
- `waitFor('selector')` - Đợi element xuất hiện
- `waitForText('text')` - Đợi text xuất hiện
- `pause(1000)` - Tạm dừng 1 giây

## Lưu ý

1. **Đảm bảo server Laravel đang chạy** khi test
2. **Tests sẽ chạy trong Chrome headless mode** để tối ưu hiệu suất
3. **Screenshots sẽ được lưu** trong `tests/Browser/screenshots/` nếu test fail
4. **Console logs sẽ được lưu** trong `tests/Browser/console/`
5. **Database sẽ được migrate** cho mỗi test case
6. **Sử dụng `@` prefix** cho các element selector (ví dụ: `@login-btn`)

## Troubleshooting

### Lỗi ZipArchive
Nếu gặp lỗi ZipArchive, cần cài đặt PHP zip extension:
```bash
# Ubuntu/Debian
sudo apt-get install php-zip

# Windows
# Bật extension zip trong php.ini
```

### Lỗi ChromeDriver
Nếu ChromeDriver không tải được, có thể tải thủ công từ:
https://chromedriver.chromium.org/

### Lỗi Database
Đảm bảo database được cấu hình đúng trong `.env.dusk.local`

### Lỗi Timeout
Tăng timeout trong `tests/DuskTestCase.php`:
```php
protected function driver(): RemoteWebDriver
{
    $options = (new ChromeOptions)->addArguments([
        '--disable-gpu',
        '--headless=new',
        '--window-size=1920,1080',
        '--no-sandbox',
        '--disable-dev-shm-usage',
    ]);

    return RemoteWebDriver::create(
        $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515',
        DesiredCapabilities::chrome()->setCapability(
            ChromeOptions::CAPABILITY, $options
        )->setCapability('timeouts', [
            'implicit' => 10000,
            'pageLoad' => 30000,
            'script' => 30000,
        ])
    );
}
```

## Best Practices

1. **Sử dụng descriptive test names** - Tên test rõ ràng, dễ hiểu
2. **Group related tests** - Nhóm các test liên quan
3. **Use page objects** - Tạo page objects cho các trang phức tạp
4. **Handle async operations** - Sử dụng `waitFor()` cho async operations
5. **Clean up data** - Dọn dẹp data sau mỗi test
6. **Use factories** - Sử dụng factories để tạo test data
7. **Test edge cases** - Test các trường hợp đặc biệt
8. **Keep tests independent** - Mỗi test độc lập với nhau 