# Dusk Troubleshooting Guide

## Các lỗi thường gặp và cách sửa

### 1. Lỗi Database: `table users has no column named email_verified_at`

**Nguyên nhân:** UserFactory đang cố gắng tạo cột `email_verified_at` nhưng migration không có cột này.

**Cách sửa:**
- Đã sửa `database/factories/UserFactory.php` để phù hợp với schema thực tế
- Sử dụng `User::factory()->admin()->create()` thay vì `User::factory()->create(['role' => 'admin'])`

### 2. Lỗi Sessions: `no such table: sessions`

**Nguyên nhân:** Database chưa được migrate hoặc sử dụng database khác.

**Cách sửa:**
- Đảm bảo sử dụng SQLite in-memory trong `.env.dusk.local`:
```
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

### 3. Lỗi Element không tìm thấy: `no such element`

**Nguyên nhân:** Selector không đúng hoặc element chưa load.

**Cách sửa:**
- Sử dụng selector chính xác: `input[name="email"]` thay vì `email`
- Thêm `waitFor()` cho async operations
- Sử dụng `assertPresent()` thay vì `assertSee()` cho form elements

### 4. Lỗi 404 hoặc trang không tồn tại

**Nguyên nhân:** Route chưa được định nghĩa hoặc server chưa chạy.

**Cách sửa:**
- Đảm bảo Laravel server đang chạy: `php artisan serve`
- Kiểm tra routes trong `routes/web.php`
- Sử dụng URL đúng

## Cách chạy test an toàn

### 1. Chạy test cơ bản trước
```bash
php artisan dusk tests/Browser/BasicTest.php
```

### 2. Kiểm tra database
```bash
php artisan migrate:fresh --env=dusk
```

### 3. Kiểm tra server
```bash
php artisan serve --port=8080
```

### 4. Chạy từng test một
```bash
php artisan dusk --filter test_basic_functionality
```

## Cấu hình Database cho Testing

### File .env.dusk.local
```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:your-key-here
APP_DEBUG=true
APP_URL=http://localhost:8080

DB_CONNECTION=sqlite
DB_DATABASE=:memory:

SESSION_DRIVER=database
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

## Best Practices

1. **Luôn chạy test cơ bản trước** để đảm bảo setup đúng
2. **Sử dụng DatabaseMigrations** để reset database cho mỗi test
3. **Sử dụng factory methods** thay vì hardcode data
4. **Kiểm tra selector** trước khi chạy test
5. **Sử dụng screenshots** để debug: `->screenshot('test-name')`

## Debug Commands

```bash
# Xem log Laravel
tail -f storage/logs/laravel.log

# Xem log Dusk
tail -f storage/logs/dusk.log

# Clear cache
php artisan cache:clear
php artisan config:clear

# Reset database
php artisan migrate:fresh --env=dusk
```

## Common Selectors

```php
// Form inputs
->type('input[name="email"]', 'test@example.com')
->type('input[name="password"]', 'password')

// Buttons
->press('Đăng nhập')
->click('button[type="submit"]')

// Links
->click('a[href="/dashboard"]')

// Custom attributes
->click('@logout-button')
->assertVisible('@user-menu')
``` 