<?php

namespace App\Livewire\Admin\EvaluationManagement;

use App\Models\Classroom;
use App\Models\Evaluation;
use App\Models\EvaluationQuestion;
use App\Models\EvaluationRound;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithFileUploads, WithPagination;

    public $classroomId = '';

    public $roundId = '';

    public $selectedEvaluation = null;

    public $showQuestionModal = false;

    public $editingQuestion = null;

    public $activeTab = 'evaluations';

    // Giới hạn câu hỏi hiển thị giống student
    protected array $categoryLimits = [
        'teacher' => 5,
        'course' => 4,
        'personal' => 1,
    ];

    public $questionForm = [
        'category' => '',
        'question' => '',
        'order' => 1,
        'is_active' => true,
    ];

    // Quản lý đợt đánh giá
    public $showRoundModal = false;

    public $editingRound = null;

    public $roundForm = [
        'name' => '',
        'description' => '',
        'start_date' => '',
        'end_date' => '',
        'is_active' => true,
    ];

    protected $queryString = ['classroomId', 'roundId', 'activeTab'];

    protected $rules = [
        'questionForm.category' => 'required|in:teacher,course,personal',
        'questionForm.question' => 'required|min:10',
        'questionForm.order' => 'required|integer|min:0',
        'questionForm.is_active' => 'boolean',
        'roundForm.name' => 'required|min:3',
        'roundForm.description' => 'nullable|max:500',
        'roundForm.start_date' => 'required|date',
        'roundForm.end_date' => 'required|date|after:start_date',
        'roundForm.is_active' => 'boolean',
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
        'roundForm.start_date.after_or_equal' => 'Ngày bắt đầu không được trước ngày hôm nay.',
        'roundForm.end_date.required' => 'Ngày kết thúc không được bỏ trống.',
        'roundForm.end_date.date' => 'Ngày kết thúc không hợp lệ.',
        'roundForm.end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
    ];

    public function updatedClassroomId()
    {
        $this->resetPage();
        Log::info('Classroom filter changed to: '.($this->classroomId ?: 'null'));
    }

    public function updatedRoundId()
    {
        $this->resetPage();
        Log::info('Round filter changed to: '.($this->roundId ?: 'null'));
    }

    public function resetFilter()
    {
        $this->classroomId = '';
        $this->roundId = '';
        $this->resetPage();
    }

    public function updatedActiveTab()
    {
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
            'order' => 1,
            'is_active' => true,
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
                'is_active' => $question->is_active,
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
            'order' => 1,
            'is_active' => true,
        ];
    }

    private function validateQuestionLimits(array $form, ?int $excludeId = null): bool
    {
        $category = $form['category'];
        $isActive = (bool) ($form['is_active'] ?? false);
        $order = (int) ($form['order'] ?? 0);

        if (! array_key_exists($category, $this->categoryLimits)) {
            session()->flash('error', 'Danh mục câu hỏi không hợp lệ.');

            return false;
        }

        // Giới hạn số câu hỏi đang hoạt động theo danh mục
        if ($isActive) {
            $activeCount = EvaluationQuestion::where('category', $category)
                ->where('is_active', true)
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->count();
            if ($activeCount >= $this->categoryLimits[$category]) {
                session()->flash('error', 'Đã đạt giới hạn câu hỏi hoạt động cho danh mục này.');

                return false;
            }

            // Không trùng thứ tự trong các câu hỏi đang hoạt động của cùng danh mục
            $dupOrder = EvaluationQuestion::where('category', $category)
                ->where('is_active', true)
                ->where('order', $order)
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->exists();
            if ($dupOrder) {
                session()->flash('error', 'Thứ tự hiển thị đã tồn tại ở danh mục này.');

                return false;
            }
        }

        return true;
    }

    public function saveQuestion()
    {
        $this->validate();

        // Kiểm tra giới hạn & thứ tự để đồng bộ với phần student
        if ($this->editingQuestion) {
            if (! $this->validateQuestionLimits($this->questionForm, $this->editingQuestion->id)) {
                return;
            }
            $this->editingQuestion->update($this->questionForm);
            session()->flash('success', 'Câu hỏi đã được cập nhật thành công!');
        } else {
            if (! $this->validateQuestionLimits($this->questionForm, null)) {
                return;
            }
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
            $targetStatus = ! $question->is_active;
            if ($targetStatus) {
                // Bật hoạt động: kiểm tra giới hạn & trùng thứ tự
                $form = [
                    'category' => $question->category,
                    'order' => $question->order,
                    'is_active' => true,
                ];
                if (! $this->validateQuestionLimits($form, $questionId)) {
                    return;
                }
            }

            $question->update(['is_active' => $targetStatus]);
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
            'is_active' => true,
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
                'is_active' => $round->is_active,
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
            'is_active' => true,
        ];
    }

    public function saveRound()
    {
        // Kiểm tra nhanh để hiển thị thông báo lỗi rõ ràng (trước khi validate chuẩn)
        if (! empty($this->roundForm['start_date'])) {
            $start = Carbon::parse($this->roundForm['start_date'])->startOfDay();
            if ($start->lt(Carbon::today())) {
                session()->flash('error', 'Không thể tạo đợt ở quá khứ. Ngày bắt đầu phải từ hôm nay trở đi.');

                return;
            }
        }

        // Validate cơ bản + cứng ràng buộc ngày bắt đầu >= hôm nay
        $this->validate([
            'roundForm.name' => 'required|min:3',
            'roundForm.description' => 'nullable|max:500',
            'roundForm.start_date' => 'required|date|after_or_equal:today',
            'roundForm.end_date' => 'required|date|after:roundForm.start_date',
            'roundForm.is_active' => 'boolean',
        ], $this->messages);

        $startDate = Carbon::parse($this->roundForm['start_date'])->toDateString();

        // Chỉ chặn trùng lặp NGÀY BẮT ĐẦU với đợt khác (kể cả cùng ngày)
        $duplicateStartQuery = EvaluationRound::whereDate('start_date', $startDate);
        if ($this->editingRound) {
            $duplicateStartQuery->where('id', '!=', $this->editingRound->id);
        }
        if ($duplicateStartQuery->exists()) {
            session()->flash('error', 'Ngày bắt đầu này đã tồn tại ở một đợt đánh giá khác. Vui lòng chọn ngày bắt đầu khác.');

            return;
        }

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
            $round->update(['is_active' => ! $round->is_active]);
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
        if ($this->roundId) {
            $query->where('evaluation_round_id', $this->roundId);
        }
        $evaluations = $query->orderByDesc('evaluation_round_id')->orderBy('created_at', 'desc')->paginate(10, ['*'], 'page');

        $classrooms = Classroom::all();
        $questions = EvaluationQuestion::ordered()->paginate(10, ['*'], 'questionsPage');
        $evaluationRounds = EvaluationRound::orderBy('start_date', 'desc')->paginate(10, ['*'], 'roundsPage');

        // Map thứ tự hiển thị theo student: chỉ tính trên câu hỏi ACTIVE, theo từng category
        $displayOrderMap = [];
        $activeOrdered = EvaluationQuestion::where('is_active', true)->ordered()->get()->groupBy('category');
        foreach ($activeOrdered as $category => $items) {
            foreach ($items as $index => $item) {
                $displayOrderMap[$item->id] = $index + 1; // 1-based order theo student
            }
        }

        // Tính điểm trung bình (theo trang hiện tại)
        $avgTeacher = $evaluations->getCollection()->avg(function ($evaluation) {
            return $evaluation->getTeacherAverageRating();
        });
        $avgCourse = $evaluations->getCollection()->avg(function ($evaluation) {
            return $evaluation->getCourseAverageRating();
        });
        $avgPersonal = $evaluations->getCollection()->avg('personal_satisfaction');

        return view('admin.evaluation-management.index', [
            'evaluations' => $evaluations,
            'classrooms' => $classrooms,
            'questions' => $questions,
            'evaluationRounds' => $evaluationRounds,
            'displayOrderMap' => $displayOrderMap,
            'avgTeacher' => $avgTeacher ?: 0,
            'avgCourse' => $avgCourse ?: 0,
            'avgPersonal' => $avgPersonal ?: 0,
            'total' => $evaluations->total(),
        ]);
    }
}
