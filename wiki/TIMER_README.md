# Hướng dẫn sử dụng Timer Quiz - EduCore

## 🎯 Tổng quan
Tính năng Timer Quiz đã được cải thiện với giao diện đẹp mắt, cảnh báo thông minh và đồng bộ real-time giữa client và server.

## ✨ Tính năng chính

### 1. **Hiển thị thời gian đẹp mắt**
- **Format thời gian**: HH:MM:SS hoặc MM:SS (tùy theo thời gian)
- **Font chữ**: Courier New với letter-spacing để dễ đọc
- **Kích thước**: Font size 1.2rem với font-weight bold

### 2. **Màu sắc thay đổi theo thời gian**
- **🔵 Xanh dương** (>10 phút): Thời gian còn nhiều
- **🟡 Vàng** (≤10 phút): Cảnh báo nhẹ
- **🔴 Đỏ** (≤5 phút): Cảnh báo khẩn cấp

### 3. **Hiệu ứng animation**
- **Pulse**: Khi thời gian sắp hết (≤10 phút)
- **Shake**: Khi hết thời gian
- **Fade In/Out**: Hiệu ứng mượt mà
- **Hover effect**: Nâng timer lên khi hover

### 4. **Cảnh báo thông minh**
- **5 phút cuối**: Alert + Browser Notification
- **1 phút cuối**: Alert khẩn cấp + Browser Notification
- **Hết thời gian**: Tự động nộp bài sau 2 giây

### 5. **Đồng bộ real-time**
- **Client timer**: Cập nhật mỗi giây
- **Server sync**: Đồng bộ mỗi 30 giây
- **Auto-submit**: Tự động nộp khi hết thời gian

## 🎨 Giao diện

### Timer Container
```html
<div class="d-inline-block px-3 py-2 rounded {{ $this->getTimerClass() }}" id="timer-container">
    <i class="bi bi-clock mr-2"></i>
    <span id="timer" class="fw-bold fs-5">
        {{ $this->getFormattedTimeRemaining() }}
    </span>
    <!-- Badges cảnh báo -->
</div>
```

### Badges cảnh báo
- **Cảnh báo**: `<span class="badge bg-warning text-dark">⚠️ Cảnh báo</span>`
- **Khẩn cấp**: `<span class="badge bg-danger text-white">🚨 Khẩn cấp</span>`

## 🔧 Cách sử dụng

### 1. **Trong Livewire Component**
```php
// Lấy thông tin timer
$timerInfo = $this->getTimerInfo();

// Cập nhật timer
$this->refreshTimer();

// Kiểm tra cảnh báo
if ($this->shouldShowWarning()) {
    // Hiển thị cảnh báo
}
```

### 2. **Trong Blade View**
```php
@if ($timeRemaining)
    <div class="{{ $this->getTimerClass() }}">
        {{ $this->getFormattedTimeRemaining() }}
    </div>
@endif
```

### 3. **JavaScript Integration**
```javascript
// Cập nhật timer mỗi giây
const timer = setInterval(function() {
    timeRemaining--;
    updateTimerDisplay();
    updateTimerClass();
    checkWarnings();
}, 1000);

// Đồng bộ với server mỗi 30 giây
setInterval(function() {
    @this.call('refreshTimer');
}, 30000);
```

## 🎯 Logic hoạt động

### 1. **Khởi tạo Timer**
```php
public function startQuiz()
{
    // ... existing code ...
    $this->calculateTimeRemaining();
}
```

### 2. **Tính toán thời gian**
```php
public function calculateTimeRemaining()
{
    if (!$this->quiz->time_limit || $this->isFinished) {
        $this->timeRemaining = null;
        return;
    }

    $timeLimitInSeconds = $this->quiz->time_limit * 60;
    $elapsedTime = $this->startedAt->diffInSeconds(now(), false);
    $this->timeRemaining = max(0, $timeLimitInSeconds - $elapsedTime);

    if ($this->timeRemaining <= 0) {
        $this->submitQuiz();
    }
}
```

### 3. **Auto-submit khi hết thời gian**
```php
public function updateTimer()
{
    if ($this->timeRemaining && $this->timeRemaining > 0) {
        $this->timeRemaining--;
        
        if ($this->timeRemaining <= 0) {
            $this->submitQuiz();
        }
    }
}
```

## 🚀 Cải tiến mới

### 1. **Performance**
- **Lazy loading**: Chỉ tính toán khi cần thiết
- **Caching**: Cache timer info để giảm tính toán
- **Optimized queries**: Giảm số lần query database

### 2. **User Experience**
- **Visual feedback**: Màu sắc và animation rõ ràng
- **Smart warnings**: Cảnh báo đúng thời điểm
- **Responsive design**: Hoạt động tốt trên mọi thiết bị

### 3. **Reliability**
- **Server sync**: Đảm bảo tính chính xác
- **Fallback handling**: Xử lý lỗi gracefully
- **Data integrity**: Bảo vệ dữ liệu quiz

## 🐛 Troubleshooting

### Timer không hiển thị
1. Kiểm tra `time_limit` trong database
2. Đảm bảo quiz có thời gian giới hạn
3. Kiểm tra JavaScript console

### Timer không đồng bộ
1. Kiểm tra Livewire connection
2. Đảm bảo `refreshTimer()` được gọi
3. Kiểm tra network latency

### Cảnh báo không hoạt động
1. Kiểm tra browser notification permission
2. Đảm bảo JavaScript được load
3. Kiểm tra console errors

## 📱 Responsive Design

### Mobile
- Font size: 1rem
- Padding: px-2 py-1
- Badge size: small

### Tablet
- Font size: 1.1rem
- Padding: px-3 py-2
- Badge size: normal

### Desktop
- Font size: 1.2rem
- Padding: px-3 py-2
- Badge size: normal

## 🔒 Security Features

### 1. **Access Control**
- Chỉ học sinh trong lớp mới được làm quiz
- Kiểm tra deadline và time limit
- Chặn làm lại nếu đã nộp

### 2. **Data Protection**
- CSRF protection
- Input validation
- SQL injection prevention
- XSS protection

### 3. **Timer Integrity**
- Server-side validation
- Client-server sync
- Anti-tampering measures

## 🎉 Kết luận

Tính năng Timer Quiz đã được cải thiện đáng kể với:
- ✅ Giao diện đẹp mắt và responsive
- ✅ Cảnh báo thông minh và đúng thời điểm
- ✅ Đồng bộ real-time giữa client và server
- ✅ Performance tối ưu và bảo mật cao
- ✅ User experience tuyệt vời

Hệ thống timer giờ đây hoạt động mượt mà, đáng tin cậy và cung cấp trải nghiệm làm quiz tốt nhất cho học sinh.
