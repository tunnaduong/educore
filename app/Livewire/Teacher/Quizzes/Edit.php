<?php

namespace App\Livewire\Teacher\Quizzes;

use App\Models\Classroom;
use App\Models\Quiz;
use Livewire\Component;

class Edit extends Component
{
    public Quiz $quiz;

    public $title = '';

    public $description = '';

    public $class_id = '';

    public $deadline = '';

    public $time_limit = '';

    public $questions = [];

    public $editingIndex = null; // Index của câu hỏi đang được sửa

    public $currentQuestion = [
        'question' => '',
        'type' => 'multiple_choice',
        'options' => ['', '', '', ''],
        'correct_answer' => '',
        'score' => 1,
        'audio' => null,
    ];

    protected $rules = [
        'title' => 'required|min:3|max:255',
        'description' => 'nullable|max:1000',
        'class_id' => 'required|exists:classrooms,id',
        'deadline' => 'nullable|date',
        'time_limit' => 'nullable|integer|min:1|max:480',
        'questions' => 'required|array|min:1',
        'questions.*.question' => 'required|min:3',
        'questions.*.type' => 'required|in:multiple_choice',
        'questions.*.score' => 'required|integer|min:1|max:10',
    ];

    protected $messages = [
        'title.required' => 'Vui lòng nhập tiêu đề bài kiểm tra.',
        'title.min' => 'Tiêu đề bài kiểm tra phải có ít nhất 3 ký tự.',
        'title.max' => 'Tiêu đề bài kiểm tra không được vượt quá 255 ký tự.',
        'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
        'class_id.required' => 'Vui lòng chọn lớp học.',
        'class_id.exists' => 'Lớp học đã chọn không tồn tại.',
        'deadline.date' => 'Hạn nộp không đúng định dạng ngày tháng.',
        'time_limit.integer' => 'Thời gian làm bài phải là số nguyên.',
        'time_limit.min' => 'Thời gian làm bài tối thiểu là 1 phút.',
        'time_limit.max' => 'Thời gian làm bài tối đa là 480 phút (8 giờ).',
        'questions.required' => 'Vui lòng thêm ít nhất một câu hỏi.',
        'questions.min' => 'Bài kiểm tra phải có ít nhất một câu hỏi.',
        'questions.*.question.required' => 'Vui lòng nhập nội dung câu hỏi.',
        'questions.*.question.min' => 'Nội dung câu hỏi phải có ít nhất 3 ký tự.',
        'questions.*.type.required' => 'Vui lòng chọn loại câu hỏi.',
        'questions.*.type.in' => 'Loại câu hỏi không hợp lệ.',
        'questions.*.score.required' => 'Vui lòng nhập điểm cho câu hỏi.',
        'questions.*.score.integer' => 'Điểm phải là số nguyên.',
        'questions.*.score.min' => 'Điểm tối thiểu là 1.',
        'questions.*.score.max' => 'Điểm tối đa là 10.',
        'currentQuestion.question.required' => 'Vui lòng nhập nội dung câu hỏi.',
        'currentQuestion.question.min' => 'Nội dung câu hỏi phải có ít nhất 3 ký tự.',
        'currentQuestion.type.required' => 'Vui lòng chọn loại câu hỏi.',
        'currentQuestion.type.in' => 'Loại câu hỏi không hợp lệ.',
        'currentQuestion.score.required' => 'Vui lòng nhập điểm cho câu hỏi.',
        'currentQuestion.score.integer' => 'Điểm phải là số nguyên.',
        'currentQuestion.score.min' => 'Điểm tối thiểu là 1.',
        'currentQuestion.score.max' => 'Điểm tối đa là 10.',
        'currentQuestion.options.required' => 'Vui lòng thêm các đáp án cho câu hỏi trắc nghiệm.',
        'currentQuestion.options.min' => 'Câu hỏi trắc nghiệm phải có ít nhất 2 đáp án.',
        'currentQuestion.options.*.required' => 'Vui lòng nhập đáp án.',
        'currentQuestion.options.*.min' => 'Đáp án phải có ít nhất 1 ký tự.',
        'currentQuestion.correct_answer.required' => 'Vui lòng chọn đáp án đúng.',
        'currentQuestion.correct_answer.min' => 'Vui lòng chọn đáp án đúng.',
    ];

    public function mount($quiz)
    {
        $this->quiz = $quiz;
        $this->title = $quiz->title;
        $this->description = $quiz->description;
        $this->class_id = $quiz->class_id;
        $this->deadline = $quiz->deadline ? $quiz->deadline->format('Y-m-d\TH:i') : '';
        $this->time_limit = $quiz->time_limit;
        $this->questions = $quiz->questions ?? [];
        
        // Kiểm tra trạng thái khóa
        $this->checkLockStatus();
    }

    /**
     * Kiểm tra trạng thái khóa của quiz
     */
    private function checkLockStatus(): void
    {
        $lockStatus = $this->quiz->getLockStatus();
        
        if (!$lockStatus['can_edit']) {
            session()->flash('warning', $lockStatus['message'] . ' - Quiz đã bị khóa chỉnh sửa.');
        }
    }

    /**
     * Kiểm tra xem quiz có thể chỉnh sửa được không
     */
    public function canEdit(): bool
    {
        return $this->quiz->isEditable();
    }

    /**
     * Lấy thông tin trạng thái khóa
     */
    public function getLockStatus(): array
    {
        return $this->quiz->getLockStatus();
    }

    public function addQuestion()
    {
        // Kiểm tra trạng thái khóa trước khi cho phép thêm/sửa
        if (!$this->canEdit()) {
            session()->flash('error', 'Không thể chỉnh sửa quiz khi có học viên đang làm bài.');
            return;
        }

        $this->validate([
            'currentQuestion.question' => 'required|min:3',
            'currentQuestion.type' => 'required|in:multiple_choice',
            'currentQuestion.score' => 'required|integer|min:1|max:10',
        ]);

        if ($this->currentQuestion['type'] === 'multiple_choice') {
            $this->validate([
                'currentQuestion.options' => 'required|array|min:2',
                'currentQuestion.options.*' => 'required|min:1',
                'currentQuestion.correct_answer' => 'required|min:1',
            ]);
        }

        // Nếu đang sửa câu hỏi, thay thế câu hỏi cũ
        if ($this->editingIndex !== null) {
            $this->questions[$this->editingIndex] = $this->currentQuestion;
            $this->editingIndex = null;
            session()->flash('message', 'Câu hỏi đã được cập nhật thành công.');
            $this->persistQuestions();
        } else {
            // Nếu thêm mới
            $this->questions[] = $this->currentQuestion;
            session()->flash('message', 'Câu hỏi đã được thêm thành công.');
            $this->persistQuestions();
        }

        $this->resetCurrentQuestion();
    }

    public function editQuestion($index)
    {
        // Kiểm tra trạng thái khóa
        if (!$this->canEdit()) {
            session()->flash('error', 'Không thể chỉnh sửa quiz khi có học viên đang làm bài.');
            return;
        }

        $this->currentQuestion = $this->questions[$index];
        $this->editingIndex = $index; // Đánh dấu câu hỏi đang được sửa
    }

    public function removeQuestion($index)
    {
        // Kiểm tra trạng thái khóa
        if (!$this->canEdit()) {
            session()->flash('error', 'Không thể xóa câu hỏi khi có học viên đang làm bài.');
            return;
        }

        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);

        session()->flash('message', 'Câu hỏi đã được xóa.');
        $this->persistQuestions();
    }

    public function moveQuestionUp($index)
    {
        // Kiểm tra trạng thái khóa
        if (!$this->canEdit()) {
            session()->flash('error', 'Không thể di chuyển câu hỏi khi có học viên đang làm bài.');
            return;
        }

        if ($index > 0) {
            $temp = $this->questions[$index];
            $this->questions[$index] = $this->questions[$index - 1];
            $this->questions[$index - 1] = $temp;
            $this->persistQuestions();
        }
    }

    public function moveQuestionDown($index)
    {
        // Kiểm tra trạng thái khóa
        if (!$this->canEdit()) {
            session()->flash('error', 'Không thể di chuyển câu hỏi khi có học viên đang làm bài.');
            return;
        }

        if ($index < count($this->questions) - 1) {
            $temp = $this->questions[$index];
            $this->questions[$index] = $this->questions[$index + 1];
            $this->questions[$index + 1] = $temp;
            $this->persistQuestions();
        }
    }

    public function addOption()
    {
        // Kiểm tra trạng thái khóa
        if (!$this->canEdit()) {
            session()->flash('error', 'Không thể thêm đáp án khi có học viên đang làm bài.');
            return;
        }

        $this->currentQuestion['options'][] = '';
    }

    public function removeOption($index)
    {
        // Kiểm tra trạng thái khóa
        if (!$this->canEdit()) {
            session()->flash('error', 'Không thể xóa đáp án khi có học viên đang làm bài.');
            return;
        }

        unset($this->currentQuestion['options'][$index]);
        $this->currentQuestion['options'] = array_values($this->currentQuestion['options']);
    }

    public function resetCurrentQuestion()
    {
        $this->currentQuestion = [
            'question' => '',
            'type' => 'multiple_choice',
            'options' => ['', '', '', ''],
            'correct_answer' => '',
            'score' => 1,
            'audio' => null,
        ];
        $this->editingIndex = null; // Reset editing index
    }

    public function save()
    {
        // Kiểm tra trạng thái khóa
        if (!$this->canEdit()) {
            session()->flash('error', 'Không thể lưu thay đổi khi có học viên đang làm bài.');
            return;
        }

        $this->validate();

        $this->quiz->update([
            'title' => $this->title,
            'description' => $this->description,
            'class_id' => $this->class_id,
            'deadline' => $this->deadline ? now()->parse($this->deadline) : null,
            'time_limit' => $this->time_limit ? (int) $this->time_limit : null,
            'questions' => $this->questions,
        ]);

        session()->flash('message', 'Bài kiểm tra đã được cập nhật thành công.');

        return redirect()->route('quizzes.show', $this->quiz);
    }

    /**
     * Lưu nhanh danh sách câu hỏi vào cơ sở dữ liệu sau khi thêm/sửa.
     */
    private function persistQuestions(): void
    {
        // Chỉ cập nhật trường questions để tránh ghi đè các trường khác khi người dùng đang chỉnh sửa
        $this->quiz->update([
            'questions' => $this->questions,
        ]);
    }

    public function render()
    {
        $classrooms = Classroom::orderBy('name')->get();
        $lockStatus = $this->getLockStatus();

        return view('teacher.quizzes.edit', [
            'classrooms' => $classrooms,
            'lockStatus' => $lockStatus,
        ]);
    }
}
