<?php

namespace App\Livewire\Admin\Quiz;

use Livewire\Component;
use App\Models\Quiz;
use App\Models\QuizResult;
use Carbon\Carbon;

class DoQuiz extends Component
{
    public $quizId;
    public $quiz;
    public $questions = [];
    public $currentQuestionIndex = 0;
    public $answers = [];
    public $startedAt;
    public $timeRemaining;
    public $isFinished = false;
    public $result = null;

    protected $listeners = ['timerTick' => 'updateTimer'];

    public function mount($quizId)
    {
        $this->quizId = $quizId;
        $this->loadQuiz();
        $this->startQuiz();
    }

    public function loadQuiz()
    {
        $this->quiz = Quiz::findOrFail($this->quizId);
        $this->questions = json_decode($this->quiz->questions, true) ?? [];
        
        // Khởi tạo mảng answers
        foreach ($this->questions as $index => $question) {
            $this->answers[$index] = '';
        }
    }

    public function startQuiz()
    {
        // Kiểm tra xem đã có kết quả chưa
        $existingResult = QuizResult::where('quiz_id', $this->quizId)
            ->where('student_id', auth()->user()->id)
            ->first();

        if ($existingResult) {
            $this->result = $existingResult;
            $this->isFinished = true;
            return;
        }

        // Tạo kết quả mới
        $this->result = QuizResult::create([
            'quiz_id' => $this->quizId,
            'student_id' => auth()->user()->id,
            'started_at' => now(),
            'score' => 0,
        ]);

        $this->startedAt = now();
        $this->timeRemaining = $this->quiz->duration ? $this->quiz->duration * 60 : null; // Chuyển phút thành giây
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < count($this->questions) - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function goToQuestion($index)
    {
        if ($index >= 0 && $index < count($this->questions)) {
            $this->currentQuestionIndex = $index;
        }
    }

    public function submitQuiz()
    {
        $this->calculateScore();
        $this->saveResult();
        $this->isFinished = true;
    }

    public function calculateScore()
    {
        $totalScore = 0;
        $maxScore = 0;

        foreach ($this->questions as $index => $question) {
            $maxScore += $question['score'] ?? 1;
            
            if (isset($this->answers[$index])) {
                $score = $this->calculateQuestionScore($question, $this->answers[$index]);
                $totalScore += $score;
            }
        }

        $finalScore = $maxScore > 0 ? round(($totalScore / $maxScore) * 100) : 0;
        
        $this->result->update([
            'score' => $finalScore,
            'answers' => $this->answers,
            'submitted_at' => now(),
        ]);
    }

    public function calculateQuestionScore($question, $answer)
    {
        switch ($question['type']) {
            case 'multiple_choice':
                return $answer === $question['correct_answer'] ? ($question['score'] ?? 1) : 0;
            
            case 'fill_blank':
                $correctAnswers = is_array($question['correct_answer']) ? $question['correct_answer'] : [$question['correct_answer']];
                return in_array(strtolower(trim($answer)), array_map('strtolower', $correctAnswers)) ? ($question['score'] ?? 1) : 0;
            
            case 'drag_drop':
                return $answer === $question['correct_answer'] ? ($question['score'] ?? 1) : 0;
            
            case 'essay':
                // Tự luận cần chấm thủ công, tạm thời cho điểm tối đa
                return $question['score'] ?? 1;
            
            default:
                return 0;
        }
    }

    public function saveResult()
    {
        $duration = $this->startedAt ? now()->diffInSeconds($this->startedAt) : null;
        
        $this->result->update([
            'duration' => $duration,
        ]);
    }

    public function updateTimer()
    {
        if ($this->timeRemaining && $this->timeRemaining > 0) {
            $this->timeRemaining--;
            
            if ($this->timeRemaining <= 0) {
                $this->submitQuiz();
            }
        }
    }

    public function render()
    {
        return view('admin.quiz.do-quiz');
    }
}
