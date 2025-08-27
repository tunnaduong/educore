<?php

namespace App\Livewire\Admin\Quiz;

use App\Models\Classroom;
use App\Models\QuestionBank;
use App\Models\Quiz;
use Livewire\Component;

class Create extends Component
{
    public $title = '';

    public $description = '';

    public $class_id = '';

    public $deadline = '';

    public $time_limit = '';

    public $questions = [];

    public $currentQuestion = [
        'question' => '',
        'type' => 'multiple_choice',
        'options' => ['', '', '', ''],
        'correct_answer' => '',
        'score' => 1,
        'audio' => null,
    ];

    // Thêm các thuộc tính cho ngân hàng câu hỏi
    public $showQuestionBank = false;

    public $selectedQuestionBank = '';

    public $questionBankQuestions = [];

    public $selectedQuestions = [];

    public $questionBankFilter = '';

    public $questionTypeFilter = '';

    public $editingIndex = null;

    protected $rules = [
        'title' => 'required|min:3|max:255',
        'description' => 'nullable|max:1000',
        'class_id' => 'required|exists:classrooms,id',
        'deadline' => 'nullable|date|after:now',
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
        'deadline.after' => 'Hạn nộp phải sau thời gian hiện tại.',
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

    public function mount()
    {
        // Kiểm tra xem có dữ liệu ngân hàng câu hỏi tạm thời từ AI không
        if (session()->has('temp_question_bank')) {
            $tempBank = session('temp_question_bank');
            $this->questionBankQuestions = $tempBank['questions'] ?? [];
            $this->showQuestionBank = true;
            session()->forget('temp_question_bank');
        }
    }

    public function addQuestion()
    {
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

        if ($this->editingIndex !== null) {
            // Cập nhật câu hỏi hiện có
            $this->questions[$this->editingIndex] = $this->currentQuestion;
            $this->resetCurrentQuestion();
            session()->flash('message', 'Câu hỏi đã được cập nhật.');
        } else {
            // Thêm câu hỏi mới
            $this->questions[] = $this->currentQuestion;
            $this->resetCurrentQuestion();
            session()->flash('message', 'Câu hỏi đã được thêm thành công.');
        }
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
        $this->editingIndex = null;
    }

    public function editQuestion($index)
    {
        if (! isset($this->questions[$index])) {
            return;
        }

        $question = $this->questions[$index];

        // Đảm bảo cấu trúc đầy đủ trước khi bind vào form
        $this->currentQuestion = [
            'question' => $question['question'] ?? '',
            'type' => $question['type'] ?? 'multiple_choice',
            'options' => $question['options'] ?? ['', '', '', ''],
            'correct_answer' => $question['correct_answer'] ?? '',
            'score' => $question['score'] ?? 1,
            'audio' => $question['audio'] ?? null,
        ];

        $this->editingIndex = $index;
    }

    // Thêm các phương thức cho ngân hàng câu hỏi
    public function loadQuestionBank($bankId)
    {
        $questionBank = QuestionBank::find($bankId);
        if ($questionBank) {
            $this->selectedQuestionBank = $bankId;
            $this->questionBankQuestions = $questionBank->questions ?? [];
            $this->showQuestionBank = true;
            session()->flash('message', 'Đã tải ngân hàng câu hỏi: '.$questionBank->name);
        }
    }

    public function updatedSelectedQuestionBank()
    {
        if ($this->selectedQuestionBank) {
            $this->loadQuestionBank($this->selectedQuestionBank);
        } else {
            $this->questionBankQuestions = [];
        }
    }

    public function toggleQuestionSelection($questionIndex)
    {
        if (in_array($questionIndex, $this->selectedQuestions)) {
            $this->selectedQuestions = array_diff($this->selectedQuestions, [$questionIndex]);
        } else {
            $this->selectedQuestions[] = $questionIndex;
        }
    }

    public function toggleAllQuestions()
    {
        if (count($this->selectedQuestions) == count($this->questionBankQuestions)) {
            $this->selectedQuestions = [];
        } else {
            $this->selectedQuestions = range(0, count($this->questionBankQuestions) - 1);
        }
    }

    public function addSelectedQuestions()
    {
        if (empty($this->selectedQuestions)) {
            session()->flash('error', 'Vui lòng chọn ít nhất một câu hỏi.');

            return;
        }

        foreach ($this->selectedQuestions as $index) {
            if (isset($this->questionBankQuestions[$index])) {
                $question = $this->questionBankQuestions[$index];

                // Đảm bảo câu hỏi có đầy đủ thông tin
                $formattedQuestion = [
                    'question' => $question['question'] ?? '',
                    'type' => $question['type'] ?? 'multiple_choice',
                    'options' => $question['options'] ?? ['', '', '', ''],
                    'correct_answer' => $question['correct_answer'] ?? '',
                    'score' => $question['score'] ?? 1,
                    'audio' => $question['audio'] ?? null,
                ];

                $this->questions[] = $formattedQuestion;
            }
        }

        $addedCount = count($this->selectedQuestions);
        $this->selectedQuestions = [];
        session()->flash('message', 'Đã thêm '.$addedCount.' câu hỏi từ ngân hàng câu hỏi.');
    }

    public function closeQuestionBank()
    {
        $this->showQuestionBank = false;
        $this->selectedQuestionBank = '';
        $this->questionBankQuestions = [];
        $this->selectedQuestions = [];
    }

    public function validateQuizWithAI()
    {
        if (empty($this->questions)) {
            session()->flash('error', 'Vui lòng thêm ít nhất một câu hỏi trước khi kiểm tra.');

            return;
        }

        try {
            $aiHelper = new \App\Helpers\AIHelper;

            if (! $aiHelper->isAIAvailable()) {
                session()->flash('error', 'AI service không khả dụng. Vui lòng kiểm tra cấu hình API key.');

                return;
            }

            $tempQuiz = (object) [
                'questions' => $this->questions,
            ];

            $result = $aiHelper->validateQuizWithAI($tempQuiz);

            if ($result && ! empty($result['fixed_questions'])) {
                $this->questions = $result['fixed_questions'];
                $errorCount = count($result['validation_info']['errors_found'] ?? []);
                $suggestionCount = count($result['validation_info']['suggestions'] ?? []);

                if ($errorCount > 0) {
                    session()->flash('message', 'AI đã sửa '.$errorCount.' lỗi trong quiz.');
                } else {
                    session()->flash('message', 'Quiz đã được kiểm tra và không có lỗi nào.');
                }

                if ($suggestionCount > 0) {
                    session()->flash('info', 'AI đưa ra '.$suggestionCount.' gợi ý cải thiện.');
                }
            } else {
                session()->flash('message', 'Quiz đã được kiểm tra và không có lỗi nào.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi kiểm tra quiz: '.$e->getMessage());
        }
    }

    public function save()
    {
        $this->validate();

        $quiz = Quiz::create([
            'title' => $this->title,
            'description' => $this->description,
            'class_id' => $this->class_id,
            'deadline' => $this->deadline ? now()->parse($this->deadline) : null,
            'time_limit' => $this->time_limit ? (int) $this->time_limit : null,
            'questions' => $this->questions,
        ]);

        session()->flash('message', 'Bài kiểm tra đã được tạo thành công.');

        return redirect()->route('quizzes.show', $quiz);
    }

    public function render()
    {
        $classrooms = Classroom::orderBy('name')->get();
        $questionBanks = QuestionBank::orderBy('name')->get();

        // Lọc câu hỏi từ ngân hàng câu hỏi
        $filteredQuestions = collect($this->questionBankQuestions);

        if ($this->questionBankFilter) {
            $filteredQuestions = $filteredQuestions->filter(function ($question) {
                return stripos($question['question'] ?? '', $this->questionBankFilter) !== false;
            });
        }

        if ($this->questionTypeFilter) {
            $filteredQuestions = $filteredQuestions->filter(function ($question) {
                return ($question['type'] ?? '') === $this->questionTypeFilter;
            });
        }

        return view('admin.quiz.create', [
            'classrooms' => $classrooms,
            'questionBanks' => $questionBanks,
            'filteredQuestions' => $filteredQuestions,
        ]);
    }
}
