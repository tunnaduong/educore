<?php

namespace App\Livewire\Teacher\Quizzes;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Quiz;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Create extends Component
{
    use WithFileUploads;

    public $title = '';
    public $description = '';
    public $class_id = '';
    public $deadline = '';
    public $time_limit = '';
    public $assigned_date = '';
    public $questions = [];
    public $showQuestionForm = false;
    public $editingQuestionIndex = null;
    public $minAssignedDate;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'class_id' => 'required|exists:classrooms,id',
        'assigned_date' => 'nullable|date|after_or_equal:today',
        'deadline' => 'nullable|date|after:assigned_date',
        'time_limit' => 'nullable|integer|min:1|max:480',
        'questions' => 'required|array|min:1',
        'questions.*.question' => 'required|string|max:500',
        'questions.*.score' => 'required|integer|min:1|max:10',
        'questions.*.options' => 'required|array|min:2',
        'questions.*.correct_answer' => 'required|string',
    ];

    protected $messages = [
        'title.required' => 'Vui lòng nhập tiêu đề bài kiểm tra.',
        'class_id.required' => 'Vui lòng chọn lớp học.',
        'questions.required' => 'Vui lòng thêm ít nhất một câu hỏi.',
        'questions.min' => 'Vui lòng thêm ít nhất một câu hỏi.',
        'assigned_date.after_or_equal' => 'Ngày giao bài phải từ hôm nay trở đi.',
        'deadline.after' => 'Hạn nộp phải sau ngày giao bài.',
        'time_limit.min' => 'Thời gian làm bài tối thiểu là 1 phút.',
        'time_limit.max' => 'Thời gian làm bài tối đa là 480 phút (8 giờ).',
    ];

    public function mount()
    {
        $this->minAssignedDate = now()->format('Y-m-d\TH:i');
        $this->addQuestion();
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
        // Reset correct_answer khi options thay đổi
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
            'questions.*.options' => 'required|array|min:2',
            'questions.*.correct_answer' => 'required|string',
        ]);

        $this->showQuestionForm = false;
        $this->editingQuestionIndex = null;
    }

    public function testCreateQuiz()
    {
        // Test tạo quiz đơn giản
        $this->title = 'Test Quiz';
        $this->class_id = Auth::user()->teachingClassrooms->first()->id ?? 1;
        $this->description = 'Test description';
        $this->questions = [
            [
                'question' => 'Câu hỏi test?',
                'score' => 1,
                'options' => ['Đáp án A', 'Đáp án B', 'Đáp án C', 'Đáp án D'],
                'correct_answer' => 'A',
                'explanation' => 'Test explanation'
            ]
        ];
        
        session()->flash('message', 'Đã load dữ liệu test. Bạn có thể chỉnh sửa và submit.');
    }




    public function save()
    {
        try {
            \Log::info('DATA SUBMIT', [
                'title' => $this->title,
                'description' => $this->description,
                'class_id' => $this->class_id,
                'deadline' => $this->deadline,
                'assigned_date' => $this->assigned_date,
                'time_limit' => $this->time_limit,
                'questions' => $this->questions,
            ]);
            // Validate cơ bản
            $this->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'class_id' => 'required|exists:classrooms,id',
                'assigned_date' => 'nullable|date|after_or_equal:today',
                'deadline' => 'nullable|date|after:assigned_date',
                'time_limit' => 'nullable|integer|min:1|max:480',
                'questions' => 'required|array|min:1',
            ]);
            // Kiểm tra xem lớp học có thuộc giáo viên không
            $teacherClassIds = Auth::user()->teachingClassrooms->pluck('id');
            if (!$teacherClassIds->contains($this->class_id)) {
                session()->flash('error', 'Bạn không có quyền tạo bài kiểm tra cho lớp này.');
                return;
            }
            // Lọc bỏ các câu hỏi trống và xử lý dữ liệu
            $validQuestions = [];
            foreach ($this->questions as $index => $question) {
                if (!empty($question['question'])) {
                    $this->validate([
                        "questions.{$index}.question" => 'required|string|max:500',
                        "questions.{$index}.score" => 'required|integer|min:1|max:10',
                        "questions.{$index}.options" => 'required|array|min:2',
                        "questions.{$index}.correct_answer" => 'required|string',
                    ]);
                    $validOptions = array_filter($question['options'], function($option) {
                        return !empty(trim($option));
                    });
                    if (count($validOptions) < 2) {
                        session()->flash('error', "Câu hỏi " . ($index + 1) . " cần ít nhất 2 lựa chọn có nội dung.");
                        return;
                    }
                    $question['options'] = array_values($validOptions);
                    if (empty($question['correct_answer'])) {
                        session()->flash('error', "Câu hỏi " . ($index + 1) . " cần chọn đáp án đúng.");
                        return;
                    }
                    $validQuestions[] = $question;
                }
            }
            if (empty($validQuestions)) {
                session()->flash('error', 'Vui lòng thêm ít nhất một câu hỏi hợp lệ.');
                return;
            }
            $quiz = Quiz::create([
                'class_id' => $this->class_id,
                'title' => $this->title,
                'description' => $this->description,
                'questions' => array_values($validQuestions),
                'assigned_date' => $this->assigned_date ? now()->parse($this->assigned_date) : null,
                'deadline' => $this->deadline ? now()->parse($this->deadline) : null,
                'time_limit' => $this->time_limit ?: null,
            ]);
            session()->flash('message', 'Bài kiểm tra đã được tạo thành công!');
            return redirect()->route('teacher.quizzes.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = [];
            foreach ($e->errors() as $field => $messages) {
                $errors[] = implode(', ', $messages);
            }
            session()->flash('error', 'Dữ liệu không hợp lệ: ' . implode('; ', $errors));
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi tạo bài kiểm tra: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $classrooms = Auth::user()->teachingClassrooms()->orderBy('name')->get();
        
        return view('teacher.quizzes.create', [
            'classrooms' => $classrooms,
        ]);
    }
}
