<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\EvaluationQuestion;
use App\Models\Classroom;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

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

    protected $queryString = ['classroomId', 'activeTab'];

    protected $rules = [
        'questionForm.category' => 'required|in:teacher,course,personal',
        'questionForm.question' => 'required|min:10',
        'questionForm.order' => 'required|integer|min:0',
        'questionForm.is_active' => 'boolean'
    ];

    protected $messages = [
        'questionForm.category.required' => 'Vui lòng chọn danh mục câu hỏi.',
        'questionForm.category.in' => 'Danh mục không hợp lệ.',
        'questionForm.question.required' => 'Câu hỏi không được bỏ trống.',
        'questionForm.question.min' => 'Câu hỏi phải có ít nhất 10 ký tự.',
        'questionForm.order.required' => 'Vui lòng nhập thứ tự hiển thị.',
        'questionForm.order.integer' => 'Thứ tự phải là số nguyên.',
        'questionForm.order.min' => 'Thứ tự phải lớn hơn hoặc bằng 0.',
    ];

    public function updatedClassroomId()
    {
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

    public function render()
    {
        $query = Evaluation::with(['student.user']);
        if ($this->classroomId) {
            $query->whereHas('student', function ($q) {
                $q->where('classroom_id', $this->classroomId);
            });
        }
        $evaluations = $query->orderBy('created_at', 'desc')->paginate(10);
        $classrooms = Classroom::all();
        $questions = EvaluationQuestion::ordered()->get();

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
            'avgTeacher' => $avgTeacher ?: 0,
            'avgCourse' => $avgCourse ?: 0,
            'avgPersonal' => $avgPersonal ?: 0,
            'total' => $evaluations->total(),
        ]);
    }
}
