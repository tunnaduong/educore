<?php

namespace App\Livewire\Teacher\Quizzes;

use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Quiz $quiz;

    public $title = '';

    public $description = '';

    public $class_id = '';

    public $deadline = '';

    public $time_limit = '';

    public $questions = [];

    public $showQuestionForm = false;

    public $editingQuestionIndex = null;

    public $minAssignedDate;

    public $oldDeadline;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'class_id' => 'required|exists:classrooms,id',
        'deadline' => 'nullable|date',
        'time_limit' => 'nullable|integer|min:1|max:480',
        'questions' => 'required|array|min:1',
        'questions.*.question' => 'required|string|max:500',
        'questions.*.score' => 'required|integer|min:1|max:10',
    ];

    protected $messages = [
        'title.required' => 'Vui lòng nhập tiêu đề bài kiểm tra.',
        'class_id.required' => 'Vui lòng chọn lớp học.',
        'questions.required' => 'Vui lòng thêm ít nhất một câu hỏi.',
        'questions.min' => 'Vui lòng thêm ít nhất một câu hỏi.',
        'time_limit.min' => 'Thời gian làm bài tối thiểu là 1 phút.',
        'time_limit.max' => 'Thời gian làm bài tối đa là 480 phút (8 giờ).',
    ];

    public function mount(Quiz $quiz)
    {
        $this->minAssignedDate = now()->format('Y-m-d\TH:i');
        $this->quiz = $quiz;

        // Kiểm tra quyền chỉnh sửa
        $teacherClassIds = Auth::user()->teachingClassrooms->pluck('id');
        if (! $teacherClassIds->contains($this->quiz->class_id)) {
            session()->flash('error', 'Bạn không có quyền chỉnh sửa bài kiểm tra này.');

            return redirect()->route('teacher.quizzes.index');
        }

        // Load dữ liệu quiz
        $this->title = $this->quiz->title;
        $this->description = $this->quiz->description;
        $this->class_id = $this->quiz->class_id;
        $this->deadline = $this->quiz->deadline ? $this->quiz->deadline->format('Y-m-d\TH:i') : null;
        $this->oldDeadline = $this->quiz->deadline ? $this->quiz->deadline->format('Y-m-d\TH:i') : null;
        $this->assigned_date = $this->quiz->assigned_date ? $this->quiz->assigned_date->format('Y-m-d\TH:i') : null;
        $this->time_limit = $this->quiz->time_limit;
        $this->questions = $this->quiz->questions ?? [];
    }

    public function addQuestion()
    {
        $this->questions[] = [
            'question' => '',
            'score' => 1,
            'options' => ['', '', '', ''],
            'correct_answer' => '',
            'explanation' => '',
        ];
        $this->editingQuestionIndex = count($this->questions) - 1;
        $this->showQuestionForm = true;
    }

    public function editQuestion($index)
    {
        $this->editingQuestionIndex = $index;
        $this->showQuestionForm = true;
    }

    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);

        if (empty($this->questions)) {
            $this->addQuestion();
        }
    }

    public function addOption($questionIndex)
    {
        $this->questions[$questionIndex]['options'][] = '';
    }

    public function removeOption($questionIndex, $optionIndex)
    {
        unset($this->questions[$questionIndex]['options'][$optionIndex]);
        $this->questions[$questionIndex]['options'] = array_values($this->questions[$questionIndex]['options']);
    }

    public function updatedQuestions($value, $key)
    {
        // Reset correct_answer khi thay đổi options
        if (str_contains($key, 'options')) {
            $parts = explode('.', $key);
            $questionIndex = $parts[0];
            $optionIndex = $parts[2] ?? null;
            if ($optionIndex !== null) {
                $this->questions[$questionIndex]['correct_answer'] = '';
            }
        }
    }

    public function saveQuestion()
    {
        $this->validate([
            'questions.*.question' => 'required|string|max:500',
            'questions.*.score' => 'required|integer|min:1|max:10',
        ]);

        $this->showQuestionForm = false;
        $this->editingQuestionIndex = null;
    }

    public function save()
    {
        $this->validate([
            'deadline' => 'required|date|after_or_equal:'.$this->oldDeadline,
        ], [
            'deadline.after_or_equal' => 'Hạn nộp mới phải sau hoặc bằng hạn nộp cũ.',
        ]);

        // Kiểm tra xem lớp học có thuộc giáo viên không
        $teacherClassIds = Auth::user()->teachingClassrooms->pluck('id');
        if (! $teacherClassIds->contains($this->class_id)) {
            session()->flash('error', 'Bạn không có quyền chỉnh sửa bài kiểm tra cho lớp này.');

            return;
        }

        // Lọc bỏ các câu hỏi trống
        $validQuestions = array_filter($this->questions, function ($question) {
            return ! empty($question['question']);
        });

        if (empty($validQuestions)) {
            session()->flash('error', 'Vui lòng thêm ít nhất một câu hỏi hợp lệ.');

            return;
        }

        $this->quiz->update([
            'class_id' => $this->class_id,
            'title' => $this->title,
            'description' => $this->description,
            'questions' => array_values($validQuestions),
            'deadline' => $this->deadline ? now()->parse($this->deadline) : null,
            'time_limit' => $this->time_limit ?: null,
        ]);

        session()->flash('message', 'Bài kiểm tra đã được cập nhật thành công!');

        return redirect()->route('teacher.quizzes.index');
    }

    public function render()
    {
        $classrooms = Auth::user()->teachingClassrooms()->orderBy('name')->get();

        return view('teacher.quizzes.edit', [
            'classrooms' => $classrooms,
        ]);
    }
}
