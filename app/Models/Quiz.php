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
        return !is_null($this->ai_validated_at);
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
}
