<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionBank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'subject',
        'topic',
        'questions',
        'statistics',
        'ai_generated',
        'ai_generation_params',
        'ai_generated_at',
        'created_by',
    ];

    protected $casts = [
        'questions' => 'array',
        'statistics' => 'array',
        'ai_generated' => 'boolean',
        'ai_generation_params' => 'array',
        'ai_generated_at' => 'datetime',
    ];

    /**
     * Người tạo ngân hàng câu hỏi
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Kiểm tra xem ngân hàng câu hỏi có được AI tạo không
     */
    public function isAIGenerated(): bool
    {
        return $this->ai_generated;
    }

    /**
     * Lấy số câu hỏi trong ngân hàng
     */
    public function getQuestionCount(): int
    {
        return $this->statistics['total_questions'] ?? count($this->questions ?? []);
    }

    /**
     * Lấy câu hỏi theo độ khó
     */
    public function getQuestionsByDifficulty($difficulty): array
    {
        return collect($this->questions ?? [])
            ->filter(function ($question) use ($difficulty) {
                return ($question['difficulty'] ?? 'medium') === $difficulty;
            })
            ->toArray();
    }

    /**
     * Lấy câu hỏi theo loại
     */
    public function getQuestionsByType($type): array
    {
        return collect($this->questions ?? [])
            ->filter(function ($question) use ($type) {
                return ($question['type'] ?? 'multiple_choice') === $type;
            })
            ->toArray();
    }

    /**
     * Lấy câu hỏi theo tag
     */
    public function getQuestionsByTag($tag): array
    {
        return collect($this->questions ?? [])
            ->filter(function ($question) use ($tag) {
                $tags = $question['tags'] ?? [];

                return in_array($tag, $tags);
            })
            ->toArray();
    }

    /**
     * Lấy thống kê theo độ khó
     */
    public function getDifficultyStats(): array
    {
        return [
            'easy' => $this->statistics['easy_count'] ?? 0,
            'medium' => $this->statistics['medium_count'] ?? 0,
            'hard' => $this->statistics['hard_count'] ?? 0,
        ];
    }

    /**
     * Lấy thống kê theo loại câu hỏi
     */
    public function getTypeStats(): array
    {
        return [
            'multiple_choice' => $this->statistics['multiple_choice_count'] ?? 0,
        ];
    }

    /**
     * Tạo quiz từ ngân hàng câu hỏi
     */
    public function createQuiz($classId, $title, $questionCount = 10, $difficulty = 'medium'): Quiz
    {
        $questions = $this->getQuestionsByDifficulty($difficulty);

        // Nếu không đủ câu hỏi theo độ khó, lấy thêm từ các độ khó khác
        if (count($questions) < $questionCount) {
            $remainingQuestions = collect($this->questions ?? [])
                ->whereNotIn('id', collect($questions)->pluck('id'))
                ->take($questionCount - count($questions))
                ->toArray();
            $questions = array_merge($questions, $remainingQuestions);
        }

        // Giới hạn số câu hỏi
        $questions = array_slice($questions, 0, $questionCount);

        return Quiz::create([
            'class_id' => $classId,
            'title' => $title,
            'description' => "Quiz được tạo từ ngân hàng câu hỏi: {$this->name}",
            'questions' => $questions,
            'ai_generated' => true,
            'ai_generation_source' => "question_bank_{$this->id}",
            'ai_generation_params' => [
                'question_count' => $questionCount,
                'difficulty' => $difficulty,
                'source_bank' => $this->name,
            ],
            'ai_generated_at' => now(),
        ]);
    }
}
