<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\EvaluationQuestion;
use App\Models\EvaluationRound;
use App\Models\Classroom;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

class EvaluationManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $classroomId = '';
    public $selectedEvaluation = null;
    public $showQuestionModal = false;
    public $editingQuestion = null;
    public $activeTab = 'evaluations';
    public $questionForm = [
        'category' => '',
        'question' => '',
        'order' => 0,
        'is_active' => true
    ];

    // Quản lý đợt đánh giá
    public $showRoundModal = false;
    public $editingRound = null;
    public $roundForm = [
        'name' => '',
        'description' => '',
        'start_date' => '',
        'end_date' => '',
        'is_active' => true
    ];

    protected $queryString = ['classroomId', 'activeTab'];

    protected $rules = [
        'questionForm.category' => 'required|in:teacher,course,personal',
        'questionForm.question' => 'required|min:10',
        'questionForm.order' => 'required|integer|min:0',
        'questionForm.is_active' => 'boolean',
        'roundForm.name' => 'required|min:3',
        'roundForm.description' => 'nullable|max:500',
        'roundForm.start_date' => 'required|date',
        'roundForm.end_date' => 'required|date|after:start_date',
        'roundForm.is_active' => 'boolean'
    ];

    protected $messages = [
        'questionForm.category.required' => 'Vui lòng chọn danh mục câu hỏi.',
        'questionForm.category.in' => 'Danh mục không hợp lệ.',
        'questionForm.question.required' => 'Câu hỏi không được bỏ trống.',
        'questionForm.question.min' => 'Câu hỏi phải có ít nhất 10 ký tự.',
        'questionForm.order.required' => 'Vui lòng nhập thứ tự hiển thị.',
        'questionForm.order.integer' => 'Thứ tự phải là số nguyên.',
        'questionForm.order.min' => 'Thứ tự phải lớn hơn hoặc bằng 0.',
        'roundForm.name.required' => 'Tên đợt đánh giá không được bỏ trống.',
        'roundForm.name.min' => 'Tên đợt đánh giá phải có ít nhất 3 ký tự.',
        'roundForm.description.max' => 'Mô tả không được quá 500 ký tự.',
        'roundForm.start_date.required' => 'Ngày bắt đầu không được bỏ trống.',
        'roundForm.start_date.date' => 'Ngày bắt đầu không hợp lệ.',
        'roundForm.end_date.required' => 'Ngày kết thúc không được bỏ trống.',
        'roundForm.end_date.date' => 'Ngày kết thúc không hợp lệ.',
        'roundForm.end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
    ];

    public function updatedClassroomId()
    {
        $this->resetPage();

        // Debug: Log giá trị filter
        Log::info('Classroom filter changed to: ' . ($this->classroomId ?: 'null'));
    }

    public function resetFilter()
    {
        $this->classroomId = '';
        $this->resetPage();
    }

    public function updatedActiveTab()
    {
        // Reset page when switching tabs
        $this->resetPage();
    }

    public function showEvaluationDetail(int $evaluationId)
    {
        $this->selectedEvaluation = Evaluation::with('student.user')->find($evaluationId);
    }

    public function closeEvaluationDetail()
    {
        $this->selectedEvaluation = null;
    }

    public function showAddQuestionModal()
    {
        $this->editingQuestion = null;
        $this->questionForm = [
            'category' => 'teacher',
            'question' => '',
            'order' => 0,
            'is_active' => true
        ];
        $this->showQuestionModal = true;
    }

    public function showEditQuestionModal(int $questionId)
    {
        $question = EvaluationQuestion::find($questionId);
        if ($question) {
            $this->editingQuestion = $question;
            $this->questionForm = [
                'category' => $question->category,
                'question' => $question->question,
                'order' => $question->order,
                'is_active' => $question->is_active
            ];
            $this->showQuestionModal = true;
        }
    }

    public function closeQuestionModal()
    {
        $this->showQuestionModal = false;
        $this->editingQuestion = null;
        $this->questionForm = [
            'category' => '',
            'question' => '',
            'order' => 0,
            'is_active' => true
        ];
    }

    public function saveQuestion()
    {
        $this->validate();

        if ($this->editingQuestion) {
            $this->editingQuestion->update($this->questionForm);
            session()->flash('success', 'Câu hỏi đã được cập nhật thành công!');
        } else {
            EvaluationQuestion::create($this->questionForm);
            session()->flash('success', 'Câu hỏi đã được thêm thành công!');
        }

        $this->closeQuestionModal();
    }

    public function deleteQuestion(int $questionId)
    {
        $question = EvaluationQuestion::find($questionId);
        if ($question) {
            $question->delete();
            session()->flash('success', 'Câu hỏi đã được xóa thành công!');
        }
    }

    public function toggleQuestionStatus(int $questionId)
    {
        $question = EvaluationQuestion::find($questionId);
        if ($question) {
            $question->update(['is_active' => !$question->is_active]);
            session()->flash('success', 'Trạng thái câu hỏi đã được cập nhật!');
        }
    }

    // Quản lý đợt đánh giá
    public function showAddRoundModal()
    {
        $this->editingRound = null;
        $this->roundForm = [
            'name' => '',
            'description' => '',
            'start_date' => now()->format('Y-m-d'),
            'end_date' => now()->addDays(30)->format('Y-m-d'),
            'is_active' => true
        ];
        $this->showRoundModal = true;
    }

    public function showEditRoundModal(int $roundId)
    {
        $round = EvaluationRound::find($roundId);
        if ($round) {
            $this->editingRound = $round;
            $this->roundForm = [
                'name' => $round->name,
                'description' => $round->description,
                'start_date' => $round->start_date->format('Y-m-d'),
                'end_date' => $round->end_date->format('Y-m-d'),
                'is_active' => $round->is_active
            ];
            $this->showRoundModal = true;
        }
    }

    public function closeRoundModal()
    {
        $this->showRoundModal = false;
        $this->editingRound = null;
        $this->roundForm = [
            'name' => '',
            'description' => '',
            'start_date' => '',
            'end_date' => '',
            'is_active' => true
        ];
    }

    public function saveRound()
    {
        $this->validate([
            'roundForm.name' => 'required|min:3',
            'roundForm.description' => 'nullable|max:500',
            'roundForm.start_date' => 'required|date',
            'roundForm.end_date' => 'required|date|after:start_date',
            'roundForm.is_active' => 'boolean'
        ], $this->messages);

        if ($this->editingRound) {
            $this->editingRound->update($this->roundForm);
            session()->flash('success', 'Cập nhật đợt đánh giá thành công!');
        } else {
            EvaluationRound::create($this->roundForm);
            session()->flash('success', 'Tạo đợt đánh giá thành công!');
        }

        $this->closeRoundModal();
    }

    public function deleteRound(int $roundId)
    {
        $round = EvaluationRound::find($roundId);
        if ($round) {
            // Kiểm tra xem có đánh giá nào thuộc đợt này không
            if ($round->evaluations()->count() > 0) {
                session()->flash('error', 'Không thể xóa đợt đánh giá đã có học viên đánh giá!');
                return;
            }

            $round->delete();
            session()->flash('success', 'Xóa đợt đánh giá thành công!');
        }
    }

    public function toggleRoundStatus(int $roundId)
    {
        $round = EvaluationRound::find($roundId);
        if ($round) {
            $round->update(['is_active' => !$round->is_active]);
            session()->flash('success', 'Cập nhật trạng thái đợt đánh giá thành công!');
        }
    }

    public function deleteEvaluation(int $evaluationId)
    {
        $evaluation = Evaluation::find($evaluationId);
        if ($evaluation) {
            $evaluation->delete();
            session()->flash('success', 'Xóa đánh giá thành công!');
        }
    }

    public function render()
    {
        $query = Evaluation::with(['student.user', 'student.user.enrolledClassrooms', 'evaluationRound']);
        if ($this->classroomId) {
            $query->whereHas('student.user.enrolledClassrooms', function ($q) {
                $q->where('classrooms.id', $this->classroomId);
            });
        }
        $evaluations = $query->orderBy('created_at', 'desc')->paginate(10);
        $classrooms = Classroom::all();
        $questions = EvaluationQuestion::ordered()->get();
        $evaluationRounds = EvaluationRound::orderBy('created_at', 'desc')->get();

        // Tính điểm trung bình
        $avgTeacher = $evaluations->getCollection()->avg(function ($evaluation) {
            return $evaluation->getTeacherAverageRating();
        });
        $avgCourse = $evaluations->getCollection()->avg(function ($evaluation) {
            return $evaluation->getCourseAverageRating();
        });
        $avgPersonal = $evaluations->getCollection()->avg('personal_satisfaction');

        return view('admin.evaluation-management', [
            'evaluations' => $evaluations,
            'classrooms' => $classrooms,
            'questions' => $questions,
            'evaluationRounds' => $evaluationRounds,
            'avgTeacher' => $avgTeacher ?: 0,
            'avgCourse' => $avgCourse ?: 0,
            'avgPersonal' => $avgPersonal ?: 0,
            'total' => $evaluations->total(),
        ]);
    }
}
