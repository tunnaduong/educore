<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizResult extends Model
{
    protected $fillable = [
        'quiz_id',
        'student_id',
        'answers',
        'score',
        'started_at',
        'submitted_at',
        'duration',
    ];

    protected $casts = [
        'answers' => 'array',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    /**
     * Quiz thuộc về kết quả này
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Học viên làm bài kiểm tra
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Lấy user của học viên thông qua student
     */
    public function getUserAttribute()
    {
        return $this->student ? $this->student->user : null;
    }

    /**
     * Kiểm tra xem có nộp đúng hạn không
     */
    public function isOnTime(): bool
    {
        if (! $this->submitted_at || ! $this->quiz->deadline) {
            return true;
        }

        return $this->submitted_at->isBefore($this->quiz->deadline);
    }

    /**
     * Lấy thời gian làm bài dạng string
     */
    public function getDurationString(): string
    {
        if (! $this->duration) {
            return '-';
        }

        $hours = floor($this->duration / 3600);
        $minutes = floor(($this->duration % 3600) / 60);
        $seconds = $this->duration % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Lấy số câu trả lời đúng
     */
    public function getCorrectAnswersCount(): int
    {
        $correctCount = 0;
        foreach ($this->answers ?? [] as $index => $answer) {
            if (! empty($answer)) {
                $correctCount++;
            }
        }

        return $correctCount;
    }
}
