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
        'category' => 'teacher',
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

    protected function rules()
    {
        return [
            'questionForm.category' => 'required|in:teacher,course,personal',
            'questionForm.question' => 'required|min:10',
            'questionForm.order' => 'required|integer|min:1',
            'questionForm.is_active' => 'boolean',
        ];
    }

    protected function roundRules()
    {
        return [
            'roundForm.name' => 'required|min:3',
            'roundForm.description' => 'nullable|max:500',
            'roundForm.start_date' => 'required|date|after_or_equal:today',
            'roundForm.end_date' => 'required|date|after:roundForm.start_date',
            'roundForm.is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'questionForm.category.required' => 'Vui lòng chọn danh mục câu hỏi.',
        'questionForm.category.in' => 'Danh mục không hợp lệ.',
        'questionForm.question.required' => 'Câu hỏi không được bỏ trống.',
        'questionForm.question.min' => 'Câu hỏi phải có ít nhất 10 ký tự.',
        'questionForm.order.required' => 'Vui lòng nhập thứ tự hiển thị.',
        'questionForm.order.integer' => 'Thứ tự phải là số nguyên.',
        'questionForm.order.min' => 'Thứ tự phải lớn hơn hoặc bằng 1.',
        'roundForm.name.required' => 'Tên đợt đánh giá không được bỏ trống.',
        'roundForm.name.min' => 'Tên đợt đánh giá phải có ít nhất 3 ký tự.',
        'roundForm.description.max' => 'Mô tả không được quá 500 ký tự.',
        'roundForm.start_date.required' => 'Ngày bắt đầu không được bỏ trống.',
        'roundForm.start_date.date' => 'Ngày bắt đầu không hợp lệ.',
        'roundForm.start_date.after_or_equal' => 'Ngày bắt đầu không được trước ngày hôm nay.',
        'roundForm.end_date.required' => 'Ngày kết thúc không được bỏ trống.',
        'roundForm.end_date.date' => 'Ngày kết thúc không hợp lệ.',
        'roundForm.end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
        'roundForm.time_conflict' => 'Thời gian đợt đánh giá này xung đột với đợt đánh giá khác.',
    ];

    public function updatedClassroomId()
    {
        $this->resetPage();
        $this->resetValidation();
        Log::info('Classroom filter changed to: '.($this->classroomId ?: 'null'));
    }

    public function updatedRoundId()
    {
        $this->resetPage();
        $this->resetValidation();
        Log::info('Round filter changed to: '.($this->roundId ?: 'null'));
    }

    public function resetFilter()
    {
        $this->classroomId = '';
        $this->roundId = '';
        $this->resetPage();
        $this->resetValidation();
    }

    public function updatedActiveTab()
    {
        $this->resetPage();
        $this->resetValidation();
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
        $this->resetValidation();
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
            $this->resetValidation();
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
        $this->resetValidation();
        $this->questionForm = [
            'category' => 'teacher',
            'question' => '',
            'order' => 1,
            'is_active' => true,
        ];
    }

    private function validateQuestionLimits(array $form, ?int $excludeId = null): bool
    {
        $category = $form['category'];
        $isActive = (bool) ($form['is_active'] ?? false);
        $order = (int) ($form['order'] ?? 1);

        Log::info('Validating question limits:', [
            'category' => $category,
            'isActive' => $isActive,
            'order' => $order,
            'excludeId' => $excludeId
        ]);

        if (! array_key_exists($category, $this->categoryLimits)) {
            session()->flash('error', __('views.validation_category_invalid'));
            Log::warning('Invalid category:', ['category' => $category]);
            return false;
        }

        // Giới hạn số câu hỏi đang hoạt động theo danh mục
        if ($isActive) {
            $activeCount = EvaluationQuestion::where('category', $category)
                ->where('is_active', true)
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->count();

            Log::info('Active count for category:', [
                'category' => $category,
                'activeCount' => $activeCount,
                'limit' => $this->categoryLimits[$category]
            ]);

            if ($activeCount >= $this->categoryLimits[$category]) {
                session()->flash('error', __('views.validation_question_limit_reached'));
                Log::warning('Question limit reached for category:', ['category' => $category]);
                return false;
            }

            // Không trùng thứ tự trong các câu hỏi đang hoạt động của cùng danh mục
            $dupOrder = EvaluationQuestion::where('category', $category)
                ->where('is_active', true)
                ->where('order', $order)
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->exists();

            Log::info('Duplicate order check:', [
                'category' => $category,
                'order' => $order,
                'hasDuplicate' => $dupOrder
            ]);

            if ($dupOrder) {
                session()->flash('error', __('views.validation_order_duplicate'));
                Log::warning('Duplicate order found for category:', ['category' => $category, 'order' => $order]);
                return false;
            }
        }

        Log::info('Question limits validation passed');
        return true;
    }

    public function saveQuestion()
    {
        try {
            // Đảm bảo dữ liệu được xử lý đúng cách
            $this->questionForm['order'] = (int) $this->questionForm['order'];
            $this->questionForm['is_active'] = (bool) ($this->questionForm['is_active'] ?? false);

            Log::info('Saving question with data:', $this->questionForm);

            $this->validate($this->rules(), $this->messages);

            // Kiểm tra giới hạn & thứ tự để đồng bộ với phần student
            if ($this->editingQuestion) {
                if (! $this->validateQuestionLimits($this->questionForm, $this->editingQuestion->id)) {
                    return;
                }
                $this->editingQuestion->update($this->questionForm);
                session()->flash('success', __('views.question_updated_success'));
                Log::info('Question updated successfully', ['id' => $this->editingQuestion->id]);
            } else {
                if (! $this->validateQuestionLimits($this->questionForm, null)) {
                    return;
                }
                $question = EvaluationQuestion::create($this->questionForm);
                session()->flash('success', __('views.question_saved_success'));
                Log::info('Question created successfully', ['id' => $question->id]);
            }

            $this->closeQuestionModal();
        } catch (\Exception $e) {
            Log::error('Error saving question: ' . $e->getMessage(), [
                'data' => $this->questionForm,
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Có lỗi xảy ra khi lưu câu hỏi: ' . $e->getMessage());
        }
    }

    public function deleteQuestion(int $questionId)
    {
        $question = EvaluationQuestion::find($questionId);
        if ($question) {
            $question->delete();
            session()->flash('success', 'Câu hỏi đã được xóa thành công!');
        }
    }

    public function loadDefaultQuestions()
    {
        try {
            // Kiểm tra xem đã có câu hỏi mặc định chưa
            $existingQuestions = EvaluationQuestion::count();

            if ($existingQuestions > 0) {
                session()->flash('error', 'Đã có câu hỏi trong hệ thống. Vui lòng xóa tất cả câu hỏi hiện tại trước khi tải câu hỏi mặc định.');
                return;
            }

            // Kiểm tra xem có đợt đánh giá nào đang hoạt động không
            $activeRounds = \App\Models\EvaluationRound::current()->get();
            if ($activeRounds->count() > 0) {
                session()->flash('error', 'Không thể tải câu hỏi mặc định khi có đợt đánh giá đang hoạt động. Vui lòng đợi đợt đánh giá kết thúc.');
                return;
            }

            // Tạo câu hỏi mặc định từ seeder
            $questions = [
                // Câu hỏi đánh giá Khóa học - Hoạt động
                [
                    'category' => 'course',
                    'question' => 'Nội dung khóa học có phù hợp với mục tiêu học tập không?',
                    'order' => 1,
                    'is_active' => true,
                ],
                [
                    'category' => 'course',
                    'question' => 'Tài liệu học tập có đầy đủ và chất lượng tốt không?',
                    'order' => 2,
                    'is_active' => true,
                ],
                [
                    'category' => 'course',
                    'question' => 'Thời gian học tập có hợp lý và hiệu quả không?',
                    'order' => 3,
                    'is_active' => true,
                ],
                [
                    'category' => 'course',
                    'question' => 'Cơ sở vật chất và trang thiết bị có đáp ứng nhu cầu học tập không?',
                    'order' => 4,
                    'is_active' => true,
                ],

                // Câu hỏi đánh giá Cá nhân - Hoạt động
                [
                    'category' => 'personal',
                    'question' => 'Bạn có hài lòng với chất lượng học tập tại trung tâm không?',
                    'order' => 1,
                    'is_active' => true,
                ],

                // Câu hỏi đánh giá Giáo viên - Hoạt động
                [
                    'category' => 'teacher',
                    'question' => 'Giáo viên có nhiệt tình và tạo không khí học tập tích cực không?',
                    'order' => 1,
                    'is_active' => true,
                ],
                [
                    'category' => 'teacher',
                    'question' => 'Giáo viên có sẵn sàng giải đáp thắc mắc và hỗ trợ học viên không?',
                    'order' => 2,
                    'is_active' => true,
                ],
                [
                    'category' => 'teacher',
                    'question' => 'Giáo viên có sử dụng phương pháp giảng dạy hiệu quả và phù hợp không?',
                    'order' => 3,
                    'is_active' => true,
                ],
                [
                    'category' => 'teacher',
                    'question' => 'Giáo viên có đánh giá công bằng và khách quan không?',
                    'order' => 4,
                    'is_active' => true,
                ],

                // Câu hỏi đánh giá Giáo viên - Không hoạt động
                [
                    'category' => 'teacher',
                    'question' => 'Giáo viên có nhiệt tình và tạo môi trường học tích cực không?',
                    'order' => 5,
                    'is_active' => false,
                ],
                [
                    'category' => 'teacher',
                    'question' => 'Giáo viên có giải đáp thắc mắc kịp thời và đầy đủ không?',
                    'order' => 6,
                    'is_active' => false,
                ],
                [
                    'category' => 'teacher',
                    'question' => 'Phương pháp giảng dạy của giáo viên có phù hợp và hiệu quả không?',
                    'order' => 7,
                    'is_active' => false,
                ],
                [
                    'category' => 'teacher',
                    'question' => 'Giáo viên đánh giá kết quả học tập công bằng và khách quan chứ?',
                    'order' => 8,
                    'is_active' => false,
                ],

                // Câu hỏi đánh giá Khóa học - Không hoạt động
                [
                    'category' => 'course',
                    'question' => 'Tài liệu và giáo trình có đầy đủ, dễ hiểu, cập nhật không?',
                    'order' => 5,
                    'is_active' => false,
                ],
                [
                    'category' => 'course',
                    'question' => 'Bài tập và kiểm tra có hợp lý, phản ánh đúng kiến thức không?',
                    'order' => 6,
                    'is_active' => false,
                ],
                [
                    'category' => 'course',
                    'question' => 'Cơ sở vật chất và hạ tầng kỹ thuật có đáp ứng nhu cầu học tập không?',
                    'order' => 7,
                    'is_active' => false,
                ],
                [
                    'category' => 'course',
                    'question' => 'Giáo trình và tài liệu học tập có rõ ràng, dễ hiểu không?',
                    'order' => 8,
                    'is_active' => false,
                ],
                [
                    'category' => 'course',
                    'question' => 'Cấu trúc buổi học có hợp lý và dễ theo dõi không?',
                    'order' => 9,
                    'is_active' => false,
                ],
            ];

            foreach ($questions as $questionData) {
                EvaluationQuestion::create($questionData);
            }

            session()->flash('success', 'Đã tải thành công ' . count($questions) . ' câu hỏi mặc định vào hệ thống!');

            // Log để debug
            Log::info('Default questions loaded successfully', ['count' => count($questions)]);

        } catch (\Exception $e) {
            Log::error('Error loading default questions: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Có lỗi xảy ra khi tải câu hỏi mặc định: ' . $e->getMessage());
        }
    }

    public function clearAllQuestions()
    {
        try {
            // Kiểm tra xem có đợt đánh giá nào đang hoạt động không
            $activeRounds = \App\Models\EvaluationRound::current()->get();
            if ($activeRounds->count() > 0) {
                session()->flash('error', 'Không thể xóa câu hỏi khi có đợt đánh giá đang hoạt động. Vui lòng đợi đợt đánh giá kết thúc.');
                return;
            }

            // Kiểm tra xem có đánh giá nào đã được submit không
            $submittedEvaluations = Evaluation::whereNotNull('submitted_at')->count();
            if ($submittedEvaluations > 0) {
                session()->flash('error', 'Không thể xóa câu hỏi khi đã có học viên đánh giá. Vui lòng xóa tất cả đánh giá trước.');
                return;
            }

            $questionCount = EvaluationQuestion::count();
            EvaluationQuestion::truncate();

            session()->flash('success', 'Đã xóa thành công ' . $questionCount . ' câu hỏi khỏi hệ thống!');

            // Log để debug
            Log::info('All questions cleared successfully', ['count' => $questionCount]);

        } catch (\Exception $e) {
            Log::error('Error clearing all questions: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Có lỗi xảy ra khi xóa câu hỏi: ' . $e->getMessage());
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
        $this->validate($this->roundRules(), $this->messages);

        $startDate = Carbon::parse($this->roundForm['start_date'])->startOfDay();
        $endDate = Carbon::parse($this->roundForm['end_date'])->endOfDay();

        // Log để debug
        Log::info('Processing round dates:', [
            'input_start' => $this->roundForm['start_date'],
            'input_end' => $this->roundForm['end_date'],
            'parsed_start' => $startDate->toDateTimeString(),
            'parsed_end' => $endDate->toDateTimeString()
        ]);

        // Kiểm tra xung đột thời gian với các đợt đánh giá khác
        $conflictingRounds = EvaluationRound::where(function($query) use ($startDate, $endDate) {
            // Kiểm tra xem có đợt nào có thời gian chồng chéo không
            // Xung đột xảy ra khi:
            // 1. Đợt mới bắt đầu trong thời gian của đợt cũ
            // 2. Đợt mới kết thúc trong thời gian của đợt cũ
            // 3. Đợt mới bao trọn đợt cũ
            $query->where(function($q) use ($startDate, $endDate) {
                $q->where('start_date', '<=', $endDate)
                  ->where('end_date', '>=', $startDate);
            });
        });

        if ($this->editingRound) {
            $conflictingRounds->where('id', '!=', $this->editingRound->id);
        }

        if ($conflictingRounds->exists()) {
            // Log để debug
            $conflictingRoundsList = $conflictingRounds->get();
            Log::info('Time conflict detected:', [
                'new_start' => $startDate->toDateString(),
                'new_end' => $endDate->toDateString(),
                'conflicting_rounds' => $conflictingRoundsList->pluck('id', 'name')->toArray()
            ]);

            session()->flash('error', 'Thời gian đợt đánh giá này xung đột với đợt đánh giá khác. Vui lòng chọn thời gian khác.');

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
