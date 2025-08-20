<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'evaluation_round_id',
        'teacher_ratings',
        'course_ratings',
        'personal_satisfaction',
        'suggestions',
        'submitted_at',
    ];

    protected $casts = [
        'teacher_ratings' => 'array',
        'course_ratings' => 'array',
        'submitted_at' => 'datetime',
    ];

    /**
     * Học viên thực hiện đánh giá
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Đợt đánh giá
     */
    public function evaluationRound(): BelongsTo
    {
        return $this->belongsTo(EvaluationRound::class);
    }

    /**
     * Lấy tổng điểm đánh giá giáo viên
     */
    public function getTeacherAverageRating(): float
    {
        if (! $this->teacher_ratings) {
            return 0;
        }

        $total = array_sum($this->teacher_ratings);
        $count = count($this->teacher_ratings);

        return $count > 0 ? round($total / $count, 1) : 0;
    }

    /**
     * Lấy tổng điểm đánh giá khóa học
     */
    public function getCourseAverageRating(): float
    {
        if (! $this->course_ratings) {
            return 0;
        }

        $total = array_sum($this->course_ratings);
        $count = count($this->course_ratings);

        return $count > 0 ? round($total / $count, 1) : 0;
    }

    /**
     * Lấy tổng điểm đánh giá tổng thể
     */
    public function getOverallRating(): float
    {
        $teacherAvg = $this->getTeacherAverageRating();
        $courseAvg = $this->getCourseAverageRating();
        $personal = $this->personal_satisfaction ?? 0;

        $total = $teacherAvg + $courseAvg + $personal;
        $count = 3;

        return $count > 0 ? round($total / $count, 1) : 0;
    }

    /**
     * Kiểm tra xem đánh giá đã được submit chưa
     */
    public function isSubmitted(): bool
    {
        return ! is_null($this->submitted_at);
    }

    /**
     * Đánh dấu đã submit
     */
    public function markAsSubmitted(): void
    {
        $this->update(['submitted_at' => now()]);
    }

    /**
     * Scope để lọc theo học viên
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Scope để lọc đánh giá đã submit
     */
    public function scopeSubmitted($query)
    {
        return $query->whereNotNull('submitted_at');
    }

    /**
     * Scope để lọc đánh giá chưa submit
     */
    public function scopeNotSubmitted($query)
    {
        return $query->whereNull('submitted_at');
    }
}
