<?php

namespace App\Helpers;

use App\Services\GeminiService;
use Illuminate\Support\Facades\Log;

class AIHelper
{
    protected $geminiService;

    public function __construct()
    {
        $this->geminiService = new GeminiService;
    }

    /**
     * Sửa lỗi ngữ pháp cho bài nộp của học sinh
     */
    public function correctStudentSubmission($submission)
    {
        try {
            if (empty($submission->content)) {
                return null;
            }

            $result = $this->geminiService->correctGrammarAndPronunciation($submission->content);

            if ($result) {
                // Cập nhật nội dung đã sửa vào database
                $submission->ai_corrected_content = $result['corrected_text'] ?? null;
                $submission->ai_errors_found = $result['errors_found'] ?? [];
                $submission->ai_suggestions = $result['suggestions'] ?? [];
                $submission->save();

                return $result;
            }
        } catch (\Exception $e) {
            Log::error('AI correction error', [
                'submission_id' => $submission->id,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Chấm bài tự luận bằng AI
     */
    public function gradeEssayWithAI($submission, $assignment)
    {
        try {
            if (empty($submission->content)) {
                return null;
            }

            // Tạo tiêu chí chấm từ assignment
            $criteria = $this->buildGradingCriteria($assignment);

            $result = $this->geminiService->gradeEssay(
                $submission->content,
                $criteria,
                $assignment->max_score ?? 10
            );

            if ($result) {
                // Cập nhật kết quả chấm AI
                $submission->ai_score = $result['score'] ?? null;
                $submission->ai_feedback = $result['feedback'] ?? null;
                $submission->ai_criteria_scores = $result['criteria_scores'] ?? [];
                $submission->ai_strengths = $result['strengths'] ?? [];
                $submission->ai_weaknesses = $result['weaknesses'] ?? [];
                $submission->ai_suggestions = $result['suggestions'] ?? [];
                $submission->ai_graded_at = now();
                $submission->save();

                return $result;
            }
        } catch (\Exception $e) {
            Log::error('AI grading error', [
                'submission_id' => $submission->id,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Kiểm tra và sửa lỗi quiz đã được lưu trong database
     */
    public function validateSavedQuizWithAI($quiz)
    {
        try {
            if (empty($quiz->questions)) {
                return null;
            }

            $result = $this->geminiService->validateAndFixQuiz($quiz->questions);

            if ($result && ! empty($result['fixed_questions'])) {
                // Cập nhật quiz với câu hỏi đã sửa và lưu vào database
                $quiz->questions = $result['fixed_questions'];
                $quiz->ai_validation_errors = $result['errors_found'] ?? [];
                $quiz->ai_suggestions = $result['suggestions'] ?? [];
                $quiz->ai_validated_at = now();
                $quiz->save();

                return $result;
            }
        } catch (\Exception $e) {
            Log::error('AI saved quiz validation error', [
                'quiz_id' => $quiz->id ?? 'unknown',
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Kiểm tra và sửa lỗi quiz (cho temporary objects)
     */
    public function validateQuizWithAI($quiz)
    {
        try {
            if (empty($quiz->questions)) {
                return null;
            }

            $result = $this->geminiService->validateAndFixQuiz($quiz->questions);

            if ($result && ! empty($result['fixed_questions'])) {
                // Chỉ cập nhật questions trong object, không save
                $quiz->questions = $result['fixed_questions'];

                // Thêm thông tin validation vào result
                $result['validation_info'] = [
                    'errors_found' => $result['errors_found'] ?? [],
                    'suggestions' => $result['suggestions'] ?? [],
                    'validated_at' => now()->toDateTimeString(),
                ];

                return $result;
            }
        } catch (\Exception $e) {
            Log::error('AI quiz validation error', [
                'quiz_type' => get_class($quiz),
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Tạo quiz tự động từ bài học
     */
    public function generateQuizFromLesson($lesson, $questionCount = 10, $difficulty = 'medium')
    {
        try {
            if (empty($lesson->content)) {
                return null;
            }

            $result = $this->geminiService->generateQuiz(
                $lesson->content,
                $lesson->title,
                $difficulty,
                $questionCount
            );

            if ($result && ! empty($result['questions'])) {
                return $result;
            }
        } catch (\Exception $e) {
            Log::error('AI quiz generation error', [
                'lesson_id' => $lesson->id,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Tạo ngân hàng câu hỏi
     */
    public function generateQuestionBank($topic, $subject, $maxQuestions = 100)
    {
        try {
            Log::info('Starting question bank generation', [
                'topic' => $topic,
                'subject' => $subject,
                'maxQuestions' => $maxQuestions,
            ]);

            // Kiểm tra API key
            if (! $this->isAIAvailable()) {
                Log::error('AI not available - missing API key');

                return null;
            }

            $result = $this->geminiService->generateQuestionBank($topic, $subject, $maxQuestions);

            Log::info('Question bank generation result', [
                'has_result' => ! empty($result),
                'has_questions' => ! empty($result['questions']),
                'question_count' => count($result['questions'] ?? []),
            ]);

            if ($result && ! empty($result['questions'])) {
                return $result;
            }

            Log::error('Question bank generation failed - no questions returned');

            return null;
        } catch (\Exception $e) {
            Log::error('AI question bank generation error', [
                'topic' => $topic,
                'subject' => $subject,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return null;
    }

    /**
     * Phân tích bài tập và đưa ra gợi ý
     */
    public function analyzeAssignmentWithAI($submission, $assignment)
    {
        try {
            if (empty($submission->content) || empty($assignment->description)) {
                return null;
            }

            $result = $this->geminiService->analyzeAssignment(
                $assignment->description,
                $submission->content
            );

            if ($result) {
                // Cập nhật kết quả phân tích
                $submission->ai_analysis = $result['analysis'] ?? [];
                $submission->ai_score_breakdown = $result['score_breakdown'] ?? [];
                $submission->ai_improvement_suggestions = $result['improvement_suggestions'] ?? [];
                $submission->ai_learning_resources = $result['learning_resources'] ?? [];
                $submission->ai_analyzed_at = now();
                $submission->save();

                return $result;
            }
        } catch (\Exception $e) {
            Log::error('AI assignment analysis error', [
                'submission_id' => $submission->id,
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Xây dựng tiêu chí chấm từ assignment
     */
    protected function buildGradingCriteria($assignment)
    {
        $criteria = [
            'Nội dung (40%): Độ chính xác, đầy đủ và sâu sắc của nội dung',
            'Cấu trúc (25%): Tính logic, mạch lạc và tổ chức của bài viết',
            'Ngữ pháp (20%): Độ chính xác về ngữ pháp và chính tả',
            'Sáng tạo (15%): Tính độc đáo và sáng tạo trong cách trình bày',
        ];

        // Nếu assignment có tiêu chí riêng thì sử dụng
        if (! empty($assignment->grading_criteria)) {
            $criteria = json_decode($assignment->grading_criteria, true) ?: $criteria;
        }

        return implode("\n", $criteria);
    }

    /**
     * Kiểm tra xem AI có sẵn sàng không
     */
    public function isAIAvailable()
    {
        return ! empty(config('services.gemini.api_key'));
    }

    /**
     * Lấy thống kê sử dụng AI
     */
    public function getAIUsageStats()
    {
        // Có thể mở rộng để lấy thống kê từ database
        return [
            'total_corrections' => 0,
            'total_gradings' => 0,
            'total_quiz_validations' => 0,
            'total_quiz_generations' => 0,
            'total_question_banks' => 0,
            'total_analyses' => 0,
        ];
    }
}
