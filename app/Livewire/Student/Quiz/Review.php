<?php

namespace App\Livewire\Student\Quiz;

use App\Models\Quiz;
use App\Models\QuizResult;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Review extends Component
{
    public Quiz $quiz;

    public $result = null;

    public $selectedQuestion = 0;

    public $quizId;

    public function mount($quizId = null)
    {
        if (! $quizId) {
            abort(404, 'Không tìm thấy bài kiểm tra.');
        }

        $this->quizId = $quizId;

        $user = Auth::user();

        // Kiểm tra xem user có student profile không
        if (! $user->studentProfile) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        $this->quiz = Quiz::with(['classroom'])->findOrFail($this->quizId);

        // Lấy kết quả quiz của user
        $this->result = QuizResult::with(['student'])->where('quiz_id', $this->quizId)
            ->where('student_id', $user->studentProfile->id)
            ->first();
        // Nếu không có kết quả thì để null, không abort
    }

    public function selectQuestion($index)
    {
        $this->selectedQuestion = $index;
    }

    public function getQuestionStatus($questionIndex)
    {
        if (! $this->result) {
            return 'unknown';
        }
        $question = $this->quiz->questions[$questionIndex];
        $answer = $this->result->answers[$questionIndex] ?? null;

        if (empty($answer)) {
            return 'unanswered';
        }

        // Kiểm tra xem câu hỏi có đáp án đúng không
        if (! isset($question['correct_answer'])) {
            return 'pending'; // Cần chấm thủ công
        }

        if ($question['type'] === 'multiple_choice') {
            return $answer === $question['correct_answer'] ? 'correct' : 'incorrect';
        } elseif ($question['type'] === 'fill_blank') {
            $correctAnswers = is_array($question['correct_answer']) ? $question['correct_answer'] : [$question['correct_answer']];

            return in_array(strtolower(trim($answer)), array_map('strtolower', $correctAnswers)) ? 'correct' : 'incorrect';
        }

        return 'unknown';
    }

    public function getQuestionStatusText($questionIndex)
    {
        if (! $this->result) {
            return 'Không xác định';
        }
        $status = $this->getQuestionStatus($questionIndex);

        switch ($status) {
            case 'correct':
                return 'Đúng';
            case 'incorrect':
                return 'Sai';
            case 'unanswered':
                return 'Chưa trả lời';
            case 'pending':
                return 'Chờ chấm';
            default:
                return 'Không xác định';
        }
    }

    public function getQuestionStatusClass($questionIndex)
    {
        if (! $this->result) {
            return 'warning';
        }
        $status = $this->getQuestionStatus($questionIndex);

        switch ($status) {
            case 'correct':
                return 'success';
            case 'incorrect':
                return 'danger';
            case 'unanswered':
                return 'secondary';
            case 'pending':
                return 'warning';
            default:
                return 'warning';
        }
    }

    public function render()
    {
        return view('student.quiz.review', [
            'quiz' => $this->quiz,
            'result' => $this->result,
            'selectedQuestion' => $this->selectedQuestion,
        ]);
    }
}
