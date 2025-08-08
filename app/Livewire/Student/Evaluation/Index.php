<?php

namespace App\Livewire\Student\Evaluation;

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\EvaluationQuestion;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $teacher_ratings = [];
    public $course_ratings = [];
    public $personal_satisfaction;
    public $suggestions;

    public $currentEvaluation;
    public $isSubmitted = false;

    // Câu hỏi đánh giá sẽ được load từ database
    public $teacherQuestions = [];
    public $courseQuestions = [];
    public $personalQuestions = [];

    protected $rules = [
        'teacher_ratings.*' => 'required|integer|min:1|max:5',
        'course_ratings.*' => 'required|integer|min:1|max:5',
        'personal_satisfaction' => 'required|integer|min:1|max:5',
        'suggestions' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'teacher_ratings.*.required' => 'Vui lòng trả lời câu hỏi này.',
        'teacher_ratings.*.integer' => 'Điểm đánh giá phải là số nguyên.',
        'teacher_ratings.*.min' => 'Điểm đánh giá phải từ 1-5.',
        'teacher_ratings.*.max' => 'Điểm đánh giá phải từ 1-5.',
        'course_ratings.*.required' => 'Vui lòng trả lời câu hỏi này.',
        'course_ratings.*.integer' => 'Điểm đánh giá phải là số nguyên.',
        'course_ratings.*.min' => 'Điểm đánh giá phải từ 1-5.',
        'course_ratings.*.max' => 'Điểm đánh giá phải từ 1-5.',
        'personal_satisfaction.required' => 'Vui lòng đánh giá mức độ hài lòng cá nhân.',
        'personal_satisfaction.integer' => 'Điểm đánh giá phải là số nguyên.',
        'personal_satisfaction.min' => 'Điểm đánh giá phải từ 1-5.',
        'personal_satisfaction.max' => 'Điểm đánh giá phải từ 1-5.',
        'suggestions.max' => 'Đề xuất không được quá 1000 ký tự.',
    ];

    public function mount()
    {
        $this->loadQuestions();
        $this->loadCurrentEvaluation();
    }

    public function loadQuestions()
    {
        $this->teacherQuestions = EvaluationQuestion::active()
            ->byCategory('teacher')
            ->ordered()
            ->pluck('question', 'order')
            ->toArray();

        $this->courseQuestions = EvaluationQuestion::active()
            ->byCategory('course')
            ->ordered()
            ->pluck('question', 'order')
            ->toArray();

        $this->personalQuestions = EvaluationQuestion::active()
            ->byCategory('personal')
            ->ordered()
            ->pluck('question', 'order')
            ->toArray();
    }

    public function loadCurrentEvaluation()
    {
        $student = Auth::user()->student;
        if (!$student) {
            return;
        }

        $this->currentEvaluation = Evaluation::where('student_id', $student->id)->first();

        if ($this->currentEvaluation) {
            $this->teacher_ratings = $this->currentEvaluation->teacher_ratings ?? [];
            $this->course_ratings = $this->currentEvaluation->course_ratings ?? [];
            $this->personal_satisfaction = $this->currentEvaluation->personal_satisfaction;
            $this->suggestions = $this->currentEvaluation->suggestions;
            $this->isSubmitted = $this->currentEvaluation->isSubmitted();
        }
    }

    public function saveEvaluation()
    {
        $this->validate();

        $student = Auth::user()->student;
        if (!$student) {
            session()->flash('error', 'Không tìm thấy thông tin học viên.');
            return;
        }

        try {
            if ($this->currentEvaluation) {
                $this->currentEvaluation->update([
                    'teacher_ratings' => $this->teacher_ratings,
                    'course_ratings' => $this->course_ratings,
                    'personal_satisfaction' => $this->personal_satisfaction,
                    'suggestions' => $this->suggestions,
                ]);
            } else {
                $this->currentEvaluation = Evaluation::create([
                    'student_id' => $student->id,
                    'teacher_ratings' => $this->teacher_ratings,
                    'course_ratings' => $this->course_ratings,
                    'personal_satisfaction' => $this->personal_satisfaction,
                    'suggestions' => $this->suggestions,
                ]);
            }

            session()->flash('success', 'Đánh giá đã được lưu thành công!');
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi lưu đánh giá.');
        }
    }

    public function submitEvaluation()
    {
        // Kiểm tra validation trước khi submit
        $this->validate();

        $student = Auth::user()->student;
        if (!$student) {
            session()->flash('error', 'Không tìm thấy thông tin học viên.');
            return;
        }

        // Kiểm tra xem tất cả câu hỏi đã được trả lời chưa
        $teacherQuestionsCount = count($this->teacherQuestions);
        $courseQuestionsCount = count($this->courseQuestions);

        if (count($this->teacher_ratings) < $teacherQuestionsCount) {
            session()->flash('error', 'Vui lòng trả lời đầy đủ tất cả câu hỏi về giáo viên.');
            return;
        }

        if (count($this->course_ratings) < $courseQuestionsCount) {
            session()->flash('error', 'Vui lòng trả lời đầy đủ tất cả câu hỏi về khóa học.');
            return;
        }

        if (!$this->personal_satisfaction) {
            session()->flash('error', 'Vui lòng đánh giá mức độ hài lòng cá nhân.');
            return;
        }

        try {
            if ($this->currentEvaluation) {
                $this->currentEvaluation->update([
                    'teacher_ratings' => $this->teacher_ratings,
                    'course_ratings' => $this->course_ratings,
                    'personal_satisfaction' => $this->personal_satisfaction,
                    'suggestions' => $this->suggestions,
                ]);
            } else {
                $this->currentEvaluation = Evaluation::create([
                    'student_id' => $student->id,
                    'teacher_ratings' => $this->teacher_ratings,
                    'course_ratings' => $this->course_ratings,
                    'personal_satisfaction' => $this->personal_satisfaction,
                    'suggestions' => $this->suggestions,
                ]);
            }

            // Đánh dấu đã submit
            $this->currentEvaluation->markAsSubmitted();
            $this->isSubmitted = true;

            session()->flash('success', 'Đánh giá đã được gửi thành công! Bạn có thể tiếp tục sử dụng hệ thống.');
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi gửi đánh giá.');
        }
    }

    public function render()
    {
        return view('student.evaluation.index');
    }
}
