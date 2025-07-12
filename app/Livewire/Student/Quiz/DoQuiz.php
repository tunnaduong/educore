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
            abort(403, 'Không tìm thấy hồ sơ học viên cho tài khoản này!');
        }
        $studentId = $student->id;

        // Kiểm tra quyền truy cập quiz: phải là học viên của lớp và lớp chưa completed
        $classroom = $this->quiz->classroom;
        $user = Auth::user();
        $isStudentInClass = $classroom && $classroom->students()->where('users.id', $user->id)->exists();
        if (!$isStudentInClass) {
            abort(403, 'Bạn không thuộc lớp học này nên không thể làm bài kiểm tra!');
        }
        if ($classroom && $classroom->status === 'completed') {
            abort(403, 'Lớp học đã kết thúc, bạn không thể làm bài kiểm tra này!');
        }

        // Chặn làm quiz nếu quiz đã hết hạn
        if (method_exists($this->quiz, 'isExpired') && $this->quiz->isExpired()) {
            abort(403, 'Bài kiểm tra này đã hết hạn, bạn không thể làm nữa!');
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
        return view('student.quiz.do-quiz');
    }
}
