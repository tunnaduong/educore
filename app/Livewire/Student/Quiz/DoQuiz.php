<?php

namespace App\Livewire\Student\Quiz;

use Livewire\Component;
use App\Models\QuizResult;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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
    public $accessDenied = null;

    protected $listeners = ['timerTick' => 'updateTimer'];

    public function mount($quiz)
    {
        if (is_string($quiz) || is_numeric($quiz)) {
            $quiz = \App\Models\Quiz::findOrFail($quiz);
        }
        $this->quiz = $quiz;
        $this->quizId = $quiz->id;
        $this->loadQuiz();
        $this->startQuiz();
    }

    public function loadQuiz()
    {
        if (is_array($this->quiz->questions)) {
            $this->questions = $this->quiz->questions;
        } else {
            $this->questions = json_decode($this->quiz->questions, true) ?? [];
        }
        // Khởi tạo mảng answers
        foreach ($this->questions as $index => $question) {
            $this->answers[$index] = '';
        }
    }

    public function startQuiz()
    {
        $student = Auth::user()->studentProfile;
        if (!$student) {
            $this->accessDenied = 'Không tìm thấy hồ sơ học viên cho tài khoản này!';
            return;
        }
        $studentId = $student->id;

        // Kiểm tra quyền truy cập quiz: phải là học viên của lớp và lớp chưa completed
        $classroom = $this->quiz->classroom;
        $user = Auth::user();
        $isStudentInClass = $classroom && $classroom->students()->where('users.id', $user->id)->exists();
        if (!$isStudentInClass) {
            $this->accessDenied = 'Bạn không thuộc lớp học này nên không thể làm bài kiểm tra!';
            return;
        }
        if ($classroom && $classroom->status === 'completed') {
            $this->accessDenied = 'Lớp học đã kết thúc, bạn không thể làm bài kiểm tra này!';
            return;
        }

        // Chặn làm quiz nếu quiz đã hết hạn
        if (method_exists($this->quiz, 'isExpired') && $this->quiz->isExpired()) {
            $this->accessDenied = 'Bài kiểm tra này đã hết hạn, bạn không thể làm nữa!';
            return;
        }

        // Nếu đã có kết quả chưa nộp thì tiếp tục làm bài
        $existingResult = QuizResult::where('quiz_id', $this->quiz->id)
            ->where('student_id', $studentId)
            ->whereNull('submitted_at')
            ->first();

        if ($existingResult) {
            $this->result = $existingResult;
            $this->isFinished = false;
            $this->startedAt = $existingResult->started_at;
            $this->answers = $existingResult->answers ?? [];
            $this->calculateTimeRemaining();
            return;
        }

        // Nếu đã nộp thì chỉ xem kết quả
        $submittedResult = QuizResult::where('quiz_id', $this->quiz->id)
            ->where('student_id', $studentId)
            ->whereNotNull('submitted_at')
            ->first();

        if ($submittedResult) {
            $this->result = $submittedResult;
            $this->isFinished = true;
            return;
        }

        // Nếu chưa có kết quả thì tạo mới
        $this->result = QuizResult::create([
            'quiz_id' => $this->quiz->id,
            'student_id' => $studentId,
            'started_at' => now(),
            'score' => 0,
            'answers' => [],
        ]);

        $this->startedAt = $this->result->started_at;
        $this->answers = [];
        $this->isFinished = false;
        $this->calculateTimeRemaining();
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

    public function saveAnswer()
    {
        if ($this->result && !$this->isFinished) {
            $this->result->update([
                'answers' => $this->answers,
            ]);
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
        $startedAt = $this->startedAt instanceof \Carbon\Carbon
            ? $this->startedAt
            : \Carbon\Carbon::parse($this->startedAt);

        $duration = $startedAt ? $startedAt->diffInSeconds(now(), false) : null;
        if ($duration !== null && $duration < 0) {
            $duration = 0;
        }

        $this->result->update([
            'duration' => $duration,
        ]);
    }

    public function calculateTimeRemaining()
    {
        if (!$this->quiz->time_limit || $this->isFinished) {
            $this->timeRemaining = null;
            return;
        }

        $startedAt = $this->startedAt instanceof \Carbon\Carbon
            ? $this->startedAt
            : \Carbon\Carbon::parse($this->startedAt);

        $timeLimitInSeconds = $this->quiz->time_limit * 60; // Convert minutes to seconds
        $elapsedTime = $startedAt ? $startedAt->diffInSeconds(now(), false) : 0;

        $this->timeRemaining = max(0, $timeLimitInSeconds - $elapsedTime);

        // Nếu hết thời gian thì tự động nộp bài
        if ($this->timeRemaining <= 0) {
            $this->submitQuiz();
        }
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

    /**
     * Format thời gian còn lại thành chuỗi đẹp
     */
    public function getFormattedTimeRemaining()
    {
        if (!$this->timeRemaining || $this->timeRemaining <= 0) {
            return null;
        }

        $hours = floor($this->timeRemaining / 3600);
        $minutes = floor(($this->timeRemaining % 3600) / 60);
        $seconds = $this->timeRemaining % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        } else {
            return sprintf('%02d:%02d', $minutes, $seconds);
        }
    }

    /**
     * Lấy class CSS cho timer dựa trên thời gian còn lại
     */
    public function getTimerClass()
    {
        if (!$this->timeRemaining || $this->timeRemaining <= 0) {
            return 'bg-secondary text-white';
        }

        if ($this->timeRemaining <= 300) { // 5 phút cuối
            return 'bg-danger text-white animate__animated animate__pulse';
        } elseif ($this->timeRemaining <= 600) { // 10 phút cuối
            return 'bg-warning text-dark animate__animated animate__pulse';
        } else {
            return 'bg-info text-white';
        }
    }

    /**
     * Kiểm tra xem có cần cảnh báo không
     */
    public function shouldShowWarning()
    {
        if (!$this->timeRemaining || $this->timeRemaining <= 0) {
            return false;
        }

        return $this->timeRemaining <= 300; // Cảnh báo khi còn 5 phút
    }

    /**
     * Kiểm tra xem có cần cảnh báo khẩn cấp không
     */
    public function shouldShowUrgentWarning()
    {
        if (!$this->timeRemaining || $this->timeRemaining <= 0) {
            return false;
        }

        return $this->timeRemaining <= 60; // Cảnh báo khẩn cấp khi còn 1 phút
    }

    /**
     * Cập nhật timer real-time
     */
    public function refreshTimer()
    {
        if (!$this->isFinished) {
            $this->calculateTimeRemaining();
        }
    }

    /**
     * Lấy thông tin timer để hiển thị
     */
    public function getTimerInfo()
    {
        if (!$this->timeRemaining || $this->timeRemaining <= 0) {
            return [
                'time_remaining' => null,
                'formatted_time' => null,
                'timer_class' => 'bg-secondary text-white',
                'show_warning' => false,
                'show_urgent_warning' => false
            ];
        }

        return [
            'time_remaining' => $this->timeRemaining,
            'formatted_time' => $this->getFormattedTimeRemaining(),
            'timer_class' => $this->getTimerClass(),
            'show_warning' => $this->shouldShowWarning(),
            'show_urgent_warning' => $this->shouldShowUrgentWarning()
        ];
    }

    public function render()
    {
        return view('student.quiz.do-quiz');
    }
}
