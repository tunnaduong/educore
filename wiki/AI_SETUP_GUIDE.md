# Hướng dẫn Cấu hình AI cho Educore

## 1. Cấu hình API Key Gemini

Để sử dụng các tính năng AI trong Educore, bạn cần cấu hình API key của Google Gemini.

### Bước 1: Lấy API Key

1. Truy cập [Google AI Studio](https://makersuite.google.com/app/apikey)
2. Đăng nhập bằng tài khoản Google
3. Tạo API key mới
4. Sao chép API key

### Bước 2: Cấu hình trong file .env

Thêm các dòng sau vào file `.env`:

```env
# Gemini AI API Configuration
GEMINI_API_KEY=your_gemini_api_key_here
GEMINI_BASE_URL=https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent
```

**Lưu ý**: Thay `your_gemini_api_key_here` bằng API key thực tế của bạn.

## 2. Kiểm tra Cấu hình

Sau khi cấu hình, bạn có thể kiểm tra bằng cách:

1. Truy cập trang tạo ngân hàng câu hỏi
2. Thử tạo một ngân hàng câu hỏi tiếng Trung
3. Kiểm tra log trong `storage/logs/laravel.log` để xem lỗi cụ thể

## 3. Các Tính năng AI Hiện có

### 3.1 Chấm bài tự động

-   Sửa lỗi ngữ pháp và phát âm
-   Chấm điểm bài tự luận thông minh
-   Đưa ra gợi ý cải thiện

### 3.2 Tạo Quiz tự động

-   Tạo quiz tiếng Trung từ nội dung bài học
-   Hỗ trợ các loại câu hỏi: trắc nghiệm, điền khuyết, tự luận
-   Độ khó theo chuẩn HSK (1-6)

### 3.3 Ngân hàng câu hỏi

-   Tạo ngân hàng câu hỏi tiếng Trung với tối đa 100 câu
-   Phân loại theo độ khó và loại câu hỏi
-   Thống kê chi tiết

### 3.4 Kiểm tra lỗi Quiz

-   Tự động kiểm tra và sửa lỗi trong quiz
-   Đảm bảo chất lượng câu hỏi

## 4. Xử lý Lỗi

### Lỗi "AI service không khả dụng"

-   Kiểm tra API key đã được cấu hình chưa
-   Kiểm tra kết nối internet
-   Xem log trong `storage/logs/laravel.log`

### Lỗi "Không thể tạo ngân hàng câu hỏi"

-   Kiểm tra API key có hợp lệ không
-   Thử giảm số lượng câu hỏi (từ 100 xuống 50)
-   Kiểm tra log để xem lỗi cụ thể

## 5. Bảo mật

-   Không chia sẻ API key với người khác
-   Không commit API key vào git repository
-   Sử dụng biến môi trường để lưu trữ API key

## 6. Hỗ trợ

Nếu gặp vấn đề, hãy:

1. Kiểm tra log trong `storage/logs/laravel.log`
2. Đảm bảo API key đã được cấu hình đúng
3. Kiểm tra kết nối internet
4. Thử lại sau vài phút
