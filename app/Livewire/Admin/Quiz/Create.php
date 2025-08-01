<?php

namespace App\Livewire\Admin\Quiz;

use Livewire\Component;
use App\Models\Quiz;
use App\Models\Classroom;

class Create extends Component
{
    public $title = '';
    public $description = '';
    public $class_id = '';
    public $deadline = '';
    public $questions = [];
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
        'deadline' => 'nullable|date|after:now',
        'questions' => 'required|array|min:1',
        'questions.*.question' => 'required|min:3',
        'questions.*.type' => 'required|in:multiple_choice,fill_blank,drag_drop,essay',
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
        'deadline.after' => 'Hạn nộp phải sau thời gian hiện tại.',
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

    public function addQuestion()
    {
        $this->validate([
            'currentQuestion.question' => 'required|min:3',
            'currentQuestion.type' => 'required|in:multiple_choice,fill_blank,drag_drop,essay',
            'currentQuestion.score' => 'required|integer|min:1|max:10',
        ]);

        if ($this->currentQuestion['type'] === 'multiple_choice') {
            $this->validate([
                'currentQuestion.options' => 'required|array|min:2',
                'currentQuestion.options.*' => 'required|min:1',
                'currentQuestion.correct_answer' => 'required|min:1',
            ]);
        }

        $this->questions[] = $this->currentQuestion;

        $this->resetCurrentQuestion();

        session()->flash('message', 'Câu hỏi đã được thêm thành công.');
    }

    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);

        session()->flash('message', 'Câu hỏi đã được xóa.');
    }

    public function moveQuestionUp($index)
    {
        if ($index > 0) {
            $temp = $this->questions[$index];
            $this->questions[$index] = $this->questions[$index - 1];
            $this->questions[$index - 1] = $temp;
        }
    }

    public function moveQuestionDown($index)
    {
        if ($index < count($this->questions) - 1) {
            $temp = $this->questions[$index];
            $this->questions[$index] = $this->questions[$index + 1];
            $this->questions[$index + 1] = $temp;
        }
    }

    public function addOption()
    {
        $this->currentQuestion['options'][] = '';
    }

    public function removeOption($index)
    {
        // Lưu giá trị của option bị xóa
        $removedOption = $this->currentQuestion['options'][$index] ?? '';

        unset($this->currentQuestion['options'][$index]);
        $this->currentQuestion['options'] = array_values($this->currentQuestion['options']);

        // Nếu option bị xóa là correct_answer, reset correct_answer
        if ($removedOption === $this->currentQuestion['correct_answer']) {
            $this->currentQuestion['correct_answer'] = '';
        }
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
    }

    public function save()
    {
        $this->validate();

        $quiz = Quiz::create([
            'title' => $this->title,
            'description' => $this->description,
            'class_id' => $this->class_id,
            'deadline' => $this->deadline ? now()->parse($this->deadline) : null,
            'questions' => $this->questions,
        ]);

        session()->flash('message', 'Bài kiểm tra đã được tạo thành công.');

        return redirect()->route('quizzes.show', $quiz);
    }

    public function render()
    {
        $classrooms = Classroom::orderBy('name')->get();

        return view('admin.quiz.create', [
            'classrooms' => $classrooms,
        ]);
    }
}
