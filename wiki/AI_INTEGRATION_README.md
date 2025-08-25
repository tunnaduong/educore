# Tích hợp AI vào Educore

## Tổng quan

Hệ thống Educore đã được tích hợp AI Gemini để hỗ trợ giáo viên trong các chức năng:

1. **Sửa lỗi ngữ pháp và phát âm** cho bài nộp của học sinh
2. **Chấm bài tự luận thông minh** với đánh giá chi tiết
3. **Tự động sửa lỗi quiz** khi giáo viên tạo
4. **Tự động tạo quiz** từ nội dung bài học
5. **Tạo ngân hàng câu hỏi** với tối đa 100 câu hỏi

## Cài đặt

### 1. Cài đặt dependencies

```bash
composer install
```

### 2. Cấu hình API Gemini

Thêm vào file `.env`:

```env
GEMINI_API_KEY=your_gemini_api_key_here
GEMINI_BASE_URL=https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent
```

### 3. Chạy migrations

```bash
php artisan migrate
```

## Cách sử dụng

### 1. Chấm bài bằng AI

1. Vào trang **Chấm điểm bài tập**
2. Chọn bài nộp cần chấm
3. Nhấn nút **AI** bên cạnh nút **Xem**
4. Hệ thống sẽ:
    - Sửa lỗi ngữ pháp
    - Chấm điểm tự động
    - Đưa ra nhận xét chi tiết
    - Phân tích điểm mạnh/yếu
    - Gợi ý cải thiện

### 2. Tạo Quiz bằng AI

1. Vào trang **Tạo bài kiểm tra**
2. Sử dụng các công cụ AI:
    - **Tạo Quiz AI**: Tạo quiz từ nội dung bài học
    - **Tạo Ngân hàng**: Tạo ngân hàng câu hỏi
    - **Kiểm tra AI**: Sửa lỗi quiz hiện có

### 3. Tạo Ngân hàng Câu hỏi

1. Vào **Tạo Ngân hàng Câu hỏi bằng AI**
2. Điền thông tin:
    - Tên ngân hàng
    - Môn học
    - Chủ đề
    - Số câu hỏi (tối đa 100)
3. Nhấn **Tạo Ngân hàng Câu hỏi**
4. Xem preview và lưu

## Cấu trúc Database

### Bảng `assignment_submissions` (các trường AI mới)

```sql
-- AI correction fields
ai_corrected_content TEXT NULL
ai_errors_found JSON NULL
ai_suggestions JSON NULL

-- AI grading fields
ai_score DECIMAL(3,1) NULL
ai_feedback TEXT NULL
ai_criteria_scores JSON NULL
ai_strengths JSON NULL
ai_weaknesses JSON NULL
ai_graded_at TIMESTAMP NULL

-- AI analysis fields
ai_analysis JSON NULL
ai_score_breakdown JSON NULL
ai_improvement_suggestions JSON NULL
ai_learning_resources JSON NULL
ai_analyzed_at TIMESTAMP NULL
```

### Bảng `quizzes` (các trường AI mới)

```sql
-- AI validation fields
ai_validation_errors JSON NULL
ai_suggestions JSON NULL
ai_validated_at TIMESTAMP NULL

-- AI generation fields
ai_generated BOOLEAN DEFAULT FALSE
ai_generation_source VARCHAR(255) NULL
ai_generation_params JSON NULL
ai_generated_at TIMESTAMP NULL
```

### Bảng `question_banks` (mới)

```sql
id BIGINT PRIMARY KEY
name VARCHAR(255)
description TEXT NULL
subject VARCHAR(100)
topic VARCHAR(255)
questions JSON
statistics JSON
ai_generated BOOLEAN DEFAULT FALSE
ai_generation_params JSON NULL
ai_generated_at TIMESTAMP NULL
created_by BIGINT
created_at TIMESTAMP
updated_at TIMESTAMP
```

## API Endpoints

### Routes cho Teacher

```php
// AI Grading
Route::get('/teacher/ai/grading/{submissionId}', AIGrading::class)->name('ai.grading');

// AI Quiz Generator
Route::get('/teacher/ai/quiz-generator', AIQuizGenerator::class)->name('ai.quiz-generator');

// Question Bank Generator
Route::get('/teacher/ai/question-bank-generator', QuestionBankGenerator::class)->name('ai.question-bank-generator');
```

## Các Service và Helper

### GeminiService

Service chính để tương tác với Gemini API:

-   `correctGrammarAndPronunciation()`: Sửa lỗi ngữ pháp
-   `gradeEssay()`: Chấm bài tự luận
-   `validateAndFixQuiz()`: Kiểm tra và sửa quiz
-   `generateQuiz()`: Tạo quiz từ bài học
-   `generateQuestionBank()`: Tạo ngân hàng câu hỏi
-   `analyzeAssignment()`: Phân tích bài tập

### AIHelper

Helper để tích hợp AI vào các chức năng hiện có:

-   `correctStudentSubmission()`: Sửa bài nộp học sinh
-   `gradeEssayWithAI()`: Chấm bài bằng AI
-   `validateQuizWithAI()`: Kiểm tra quiz
-   `generateQuizFromLesson()`: Tạo quiz từ bài học
-   `generateQuestionBank()`: Tạo ngân hàng câu hỏi
-   `analyzeAssignmentWithAI()`: Phân tích bài tập

## Tính năng AI

### 1. Sửa lỗi ngữ pháp và phát âm

-   Tự động phát hiện và sửa lỗi ngữ pháp
-   Sửa lỗi chính tả
-   Cải thiện cấu trúc câu
-   Đưa ra giải thích cho từng lỗi

### 2. Chấm bài tự luận thông minh

-   Đánh giá theo tiêu chí: nội dung, cấu trúc, ngữ pháp, sáng tạo
-   Cho điểm chi tiết từng tiêu chí
-   Đưa ra nhận xét cụ thể
-   Chỉ ra điểm mạnh và điểm yếu
-   Gợi ý cải thiện

### 3. Tự động sửa lỗi quiz

-   Kiểm tra tính chính xác của câu hỏi
-   Sửa lỗi ngữ pháp trong câu hỏi
-   Kiểm tra tính logic của đáp án
-   Đưa ra gợi ý cải thiện

### 4. Tạo quiz tự động

-   Tạo quiz từ nội dung bài học
-   Hỗ trợ nhiều loại câu hỏi: trắc nghiệm, điền khuyết, tự luận
-   Tùy chỉnh độ khó: dễ, trung bình, khó
-   Ước tính thời gian làm bài

### 5. Tạo ngân hàng câu hỏi

-   Tạo tối đa 100 câu hỏi
-   Phân loại theo độ khó
-   Hỗ trợ nhiều loại câu hỏi
-   Thống kê chi tiết
-   Gắn tag cho từng câu hỏi

## Lưu ý

1. **API Key**: Cần có API key hợp lệ từ Google Gemini
2. **Rate Limiting**: API có giới hạn số lượng request
3. **Cost**: Sử dụng API có thể phát sinh chi phí
4. **Privacy**: Dữ liệu học sinh được gửi đến Google để xử lý
5. **Accuracy**: Kết quả AI cần được giáo viên kiểm tra lại

## Troubleshooting

### Lỗi API không khả dụng

```php
// Kiểm tra cấu hình
config('services.gemini.api_key')

// Kiểm tra kết nối
$aiHelper = new AIHelper();
$aiHelper->isAIAvailable();
```

### Lỗi migration

```bash
# Rollback migrations
php artisan migrate:rollback

# Chạy lại migrations
php artisan migrate
```

### Lỗi JSON parsing

```php
// Kiểm tra response từ API
Log::error('Gemini API response', ['response' => $response->body()]);
```

## Tương lai

-   Tích hợp thêm các AI model khác
-   Hỗ trợ đa ngôn ngữ
-   Phân tích sentiment của bài viết
-   Tạo bài giảng tự động
-   Chatbot hỗ trợ học tập
