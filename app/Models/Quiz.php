<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    protected $fillable = [
        'class_id',
        'title',
        'description',
        'questions',
        'deadline',
        'assigned_date',
        'time_limit',
        // AI validation fields
        'ai_validation_errors',
        'ai_suggestions',
        'ai_validated_at',
        // AI generation fields
        'ai_generated',
        'ai_generation_source',
        'ai_generation_params',
        'ai_generated_at',
    ];

    protected $casts = [
        'questions' => 'array',
        'deadline' => 'datetime',
        'assigned_date' => 'datetime',
        'time_limit' => 'integer',
        'ai_validated_at' => 'datetime',
        'ai_generated_at' => 'datetime',
        'ai_generated' => 'boolean',
        'ai_validation_errors' => 'array',
        'ai_suggestions' => 'array',
        'ai_generation_params' => 'array',
    ];

    /**
     * Lớp học thuộc về quiz này
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }

    /**
     * Các kết quả của quiz này
     */
    public function results(): HasMany
    {
        return $this->hasMany(QuizResult::class);
    }

    /**
     * Kiểm tra xem quiz có còn hạn không
     */
    public function isExpired(): bool
    {
        return $this->deadline && now()->isAfter($this->deadline);
    }

    /**
     * Lấy số câu hỏi trong quiz
     */
    public function getQuestionCount(): int
    {
        return count($this->questions ?? []);
    }

    /**
     * Lấy tổng điểm tối đa của quiz
     */
    public function getMaxScore(): int
    {
        $maxScore = 0;
        foreach ($this->questions ?? [] as $question) {
            $maxScore += $question['score'] ?? 1;
        }

        return $maxScore;
    }

    /**
     * Kiểm tra xem quiz đã được AI validate chưa
     */
    public function hasAIValidation(): bool
    {
        return ! is_null($this->ai_validated_at);
    }

    /**
     * Kiểm tra xem quiz có được AI tạo không
     */
    public function isAIGenerated(): bool
    {
        return $this->ai_generated;
    }

    /**
     * Lấy thông tin lỗi validation của AI
     */
    public function getAIValidationErrors(): array
    {
        return $this->ai_validation_errors ?? [];
    }

    /**
     * Lấy gợi ý cải thiện từ AI
     */
    public function getAISuggestions(): array
    {
        return $this->ai_suggestions ?? [];
    }

    /**
     * Kiểm tra xem quiz có đang được làm bài không
     */
    public function isInProgress(): bool
    {
        return $this->results()->whereNotNull('started_at')->whereNull('submitted_at')->exists();
    }

    /**
     * Kiểm tra xem quiz có học viên nào đã bắt đầu làm chưa
     */
    public function hasStartedStudents(): bool
    {
        return $this->results()->whereNotNull('started_at')->exists();
    }

    /**
     * Kiểm tra xem quiz có học viên nào đã hoàn thành chưa
     */
    public function hasCompletedStudents(): bool
    {
        return $this->results()->whereNotNull('submitted_at')->exists();
    }

    /**
     * Kiểm tra xem quiz có thể chỉnh sửa được không
     */
    public function isEditable(): bool
    {
        // Không thể chỉnh sửa nếu có học viên đang làm bài
        return !$this->isInProgress();
    }

    /**
     * Lấy số học viên đang làm bài
     */
    public function getActiveStudentsCount(): int
    {
        return $this->results()->whereNotNull('started_at')->whereNull('submitted_at')->count();
    }

    /**
     * Lấy số học viên đã hoàn thành
     */
    public function getCompletedStudentsCount(): int
    {
        return $this->results()->whereNotNull('submitted_at')->count();
    }

    /**
     * Lấy tổng số học viên được giao bài
     */
    public function getTotalAssignedStudentsCount(): int
    {
        return $this->results()->count();
    }

    /**
     * Lấy trạng thái khóa của quiz
     */
    public function getLockStatus(): array
    {
        $activeCount = $this->getActiveStudentsCount();
        $completedCount = $this->getCompletedStudentsCount();
        $totalCount = $this->getTotalAssignedStudentsCount();

        if ($activeCount > 0) {
            return [
                'status' => 'locked',
                'message' => "Đang khóa: {$activeCount} học viên đang làm bài",
                'can_edit' => false
            ];
        }

        if ($completedCount > 0) {
            return [
                'status' => 'completed',
                'message' => "Đã hoàn thành: {$completedCount}/{$totalCount} học viên",
                'can_edit' => false
            ];
        }

        return [
            'status' => 'editable',
            'message' => 'Có thể chỉnh sửa',
            'can_edit' => true
        ];
    }
}
