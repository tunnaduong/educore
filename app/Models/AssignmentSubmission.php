<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'content',
        'submission_type',
        'score',
        'feedback',
        'submitted_at',
        // AI correction fields
        'ai_corrected_content',
        'ai_errors_found',
        'ai_suggestions',
        // AI grading fields
        'ai_score',
        'ai_feedback',
        'ai_criteria_scores',
        'ai_strengths',
        'ai_weaknesses',
        'ai_graded_at',
        // AI analysis fields
        'ai_analysis',
        'ai_score_breakdown',
        'ai_improvement_suggestions',
        'ai_learning_resources',
        'ai_analyzed_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'ai_graded_at' => 'datetime',
        'ai_analyzed_at' => 'datetime',
        'ai_score' => 'decimal:1',
        'ai_errors_found' => 'array',
        'ai_suggestions' => 'array',
        'ai_criteria_scores' => 'array',
        'ai_strengths' => 'array',
        'ai_weaknesses' => 'array',
        'ai_analysis' => 'array',
        'ai_score_breakdown' => 'array',
        'ai_improvement_suggestions' => 'array',
        'ai_learning_resources' => 'array',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Kiểm tra xem bài nộp đã được AI sửa lỗi chưa
     */
    public function hasAICorrection(): bool
    {
        return ! empty($this->ai_corrected_content);
    }

    /**
     * Kiểm tra xem bài nộp đã được AI chấm chưa
     */
    public function hasAIGrading(): bool
    {
        return ! is_null($this->ai_score);
    }

    /**
     * Kiểm tra xem bài nộp đã được AI phân tích chưa
     */
    public function hasAIAnalysis(): bool
    {
        return ! empty($this->ai_analysis);
    }

    /**
     * Lấy điểm cuối cùng (AI hoặc thủ công)
     */
    public function getFinalScore()
    {
        return $this->ai_score ?? $this->score;
    }

    /**
     * Lấy feedback cuối cùng (AI hoặc thủ công)
     */
    public function getFinalFeedback()
    {
        return $this->ai_feedback ?? $this->feedback;
    }
}
