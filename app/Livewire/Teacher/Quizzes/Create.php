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
    public $questions = [];
    public $showQuestionForm = false;
    public $editingQuestionIndex = null;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'class_id' => 'required|exists:classrooms,id',
        'deadline' => 'nullable|date|after:now',
        'time_limit' => 'nullable|integer|min:1|max:480',
        'questions' => 'required|array|min:1',
        'questions.*.question' => 'required|string|max:500',
        'questions.*.type' => 'required|in:multiple_choice,true_false,essay',
        'questions.*.score' => 'required|integer|min:1|max:10',
        'questions.*.options' => 'required_if:questions.*.type,multiple_choice|array|min:2',
        'questions.*.correct_answer' => 'required_if:questions.*.type,multiple_choice,true_false|string',
    ];

    protected $messages = [
        'title.required' => 'Vui lòng nhập tiêu đề bài kiểm tra.',
        'class_id.required' => 'Vui lòng chọn lớp học.',
        'questions.required' => 'Vui lòng thêm ít nhất một câu hỏi.',
        'questions.min' => 'Vui lòng thêm ít nhất một câu hỏi.',
        'deadline.after' => 'Hạn nộp phải sau thời gian hiện tại.',
        'time_limit.min' => 'Thời gian làm bài tối thiểu là 1 phút.',
        'time_limit.max' => 'Thời gian làm bài tối đa là 480 phút (8 giờ).',
    ];

    public function mount()
    {
        $this->addQuestion();
    }

    public function addQuestion()
    {
        $this->questions[] = [
            'question' => '',
            'type' => 'multiple_choice',
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
        // Reset correct_answer when question type changes
        if (str_contains($key, 'type')) {
            $parts = explode('.', $key);
            $questionIndex = $parts[0];
            $this->questions[$questionIndex]['correct_answer'] = '';
            
            // Reset options for essay type
            if ($value === 'essay') {
                $this->questions[$questionIndex]['options'] = [];
            } elseif ($value === 'multiple_choice' && empty($this->questions[$questionIndex]['options'])) {
                $this->questions[$questionIndex]['options'] = ['', '', '', ''];
            }
        }
    }

    public function saveQuestion()
    {
        $this->validate([
            'questions.*.question' => 'required|string|max:500',
            'questions.*.type' => 'required|in:multiple_choice,true_false,essay',
            'questions.*.score' => 'required|integer|min:1|max:10',
            'questions.*.options' => 'required_if:questions.*.type,multiple_choice|array|min:2',
            'questions.*.correct_answer' => 'required_if:questions.*.type,multiple_choice,true_false|string',
        ]);

        $this->showQuestionForm = false;
        $this->editingQuestionIndex = null;
    }

    public function debugForm()
    {
        \Log::info('Form data:', [
            'title' => $this->title,
            'class_id' => $this->class_id,
            'questions' => $this->questions
        ]);
        
        session()->flash('message', 'Debug data đã được ghi vào log. Kiểm tra storage/logs/laravel.log');
    }

    public function save()
    {
        try {
            // Validate cơ bản
            $this->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'class_id' => 'required|exists:classrooms,id',
                'deadline' => 'nullable|date|after:now',
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
                    // Validate từng câu hỏi
                    $this->validate([
                        "questions.{$index}.question" => 'required|string|max:500',
                        "questions.{$index}.type" => 'required|in:multiple_choice,true_false,essay',
                        "questions.{$index}.score" => 'required|integer|min:1|max:10',
                    ]);

                    // Xử lý theo loại câu hỏi
                    if ($question['type'] === 'essay') {
                        $question['options'] = [];
                        $question['correct_answer'] = '';
                    } elseif ($question['type'] === 'multiple_choice') {
                        // Kiểm tra options
                        $validOptions = array_filter($question['options'], function($option) {
                            return !empty(trim($option));
                        });
                        if (count($validOptions) < 2) {
                            session()->flash('error', "Câu hỏi " . ($index + 1) . " cần ít nhất 2 lựa chọn có nội dung.");
                            return;
                        }
                        $question['options'] = array_values($validOptions);
                        
                        // Kiểm tra đáp án đúng
                        if (empty($question['correct_answer'])) {
                            session()->flash('error', "Câu hỏi " . ($index + 1) . " cần chọn đáp án đúng.");
                            return;
                        }
                    } elseif ($question['type'] === 'true_false') {
                        if (empty($question['correct_answer'])) {
                            session()->flash('error', "Câu hỏi " . ($index + 1) . " cần chọn đáp án đúng.");
                            return;
                        }
                    }
                    
                    $validQuestions[] = $question;
                }
            }

            if (empty($validQuestions)) {
                session()->flash('error', 'Vui lòng thêm ít nhất một câu hỏi hợp lệ.');
                return;
            }

            // Debug: Log dữ liệu trước khi tạo
            \Log::info('Creating quiz with data:', [
                'class_id' => $this->class_id,
                'title' => $this->title,
                'questions_count' => count($validQuestions),
                'questions' => $validQuestions
            ]);

            $quiz = Quiz::create([
                'class_id' => $this->class_id,
                'title' => $this->title,
                'description' => $this->description,
                'questions' => array_values($validQuestions),
                'deadline' => $this->deadline ? now()->parse($this->deadline) : null,
                'time_limit' => $this->time_limit ?: null,
            ]);

            session()->flash('message', 'Bài kiểm tra đã được tạo thành công!');
            return redirect()->route('teacher.quizzes.index');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('error', 'Dữ liệu không hợp lệ: ' . implode(', ', $e->errors()));
        } catch (\Exception $e) {
            \Log::error('Error creating quiz: ' . $e->getMessage());
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
