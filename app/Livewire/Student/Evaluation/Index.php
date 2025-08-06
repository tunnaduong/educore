<?php

namespace App\Livewire\Student\Evaluation;

use Livewire\Component;
use App\Models\Evaluation;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $teacher_ratings = [];
    public $course_ratings = [];
    public $personal_satisfaction;
    public $suggestions;

    public $currentEvaluation;
    public $isSubmitted = false;

    // Câu hỏi đánh giá
    public $teacherQuestions = [
        1 => 'Giảng viên truyền đạt nội dung dễ hiểu và có logic',
        2 => 'Giảng viên sẵn sàng giải đáp thắc mắc của học viên',
        3 => 'Giảng viên có sử dụng ví dụ/thực hành giúp học viên dễ hiểu hơn',
        4 => 'Phong thái giảng dạy chuyên nghiệp và thân thiện',
        5 => 'Giảng viên đúng giờ và đảm bảo thời lượng giảng dạy đầy đủ'
    ];

    public $courseQuestions = [
        6 => 'Nội dung bài giảng phù hợp với mục tiêu môn học',
        7 => 'Tài liệu học tập dễ tiếp cận và đầy đủ',
        8 => 'Bài tập và kiểm tra giúp củng cố kiến thức',
        9 => 'Hệ thống học trực tuyến ổn định và dễ sử dụng'
    ];

    protected $rules = [
        'teacher_ratings.*' => 'required|integer|min:1|max:5',
        'course_ratings.*' => 'required|integer|min:1|max:5',
        'personal_satisfaction' => 'required|integer|min:1|max:5',
        'suggestions' => 'nullable|string|max:1000',
    ];

    public function mount()
    {
        $this->loadCurrentEvaluation();
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
