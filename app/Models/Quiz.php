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
    ];

    protected $casts = [
        'questions' => 'array',
        'deadline' => 'datetime',
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
}
