<?php

namespace App\Livewire\Student\Evaluation;

use App\Models\Evaluation;
use App\Models\EvaluationQuestion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

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

        // Đảm bảo dữ liệu được khởi tạo đúng cách
        if (empty($this->teacher_ratings)) {
            $this->teacher_ratings = [];
        }
        if (empty($this->course_ratings)) {
            $this->course_ratings = [];
        }
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
        if (! $student) {
            return;
        }

        // Lấy đợt đánh giá hiện tại chưa được đánh giá
        $currentRounds = \App\Models\EvaluationRound::current()->get();

        // Debug: Log thông tin đợt đánh giá hiện tại
        Log::info('Current evaluation rounds found: '.$currentRounds->count());
        foreach ($currentRounds as $round) {
            Log::info('Round ID: '.$round->id.', Name: '.$round->name.', Start: '.$round->start_date.', End: '.$round->end_date);
        }

        if ($currentRounds->count() > 0) {
            // Tìm đợt đầu tiên mà student chưa đánh giá
            foreach ($currentRounds as $round) {
                $evaluated = Evaluation::where('student_id', $student->id)
                    ->where('evaluation_round_id', $round->id)
                    ->whereNotNull('submitted_at')
                    ->exists();

                Log::info('Student '.$student->id.' evaluated round '.$round->id.': '.($evaluated ? 'YES' : 'NO'));

                if (! $evaluated) {
                    // Tìm thấy đợt chưa đánh giá
                    $this->currentEvaluation = Evaluation::where('student_id', $student->id)
                        ->where('evaluation_round_id', $round->id)
                        ->first();

                    Log::info('Found current evaluation: '.($this->currentEvaluation ? 'YES' : 'NO'));
                    break;
                }
            }
        } else {
            $this->currentEvaluation = null;
        }

        if ($this->currentEvaluation) {
            $this->teacher_ratings = $this->currentEvaluation->teacher_ratings ?? [];
            $this->course_ratings = $this->currentEvaluation->course_ratings ?? [];
            $this->personal_satisfaction = $this->currentEvaluation->personal_satisfaction;
            $this->suggestions = $this->currentEvaluation->suggestions;
            $this->isSubmitted = $this->currentEvaluation->isSubmitted();

            // Log để debug
            Log::info('Loaded evaluation data:', [
                'teacher_ratings' => $this->teacher_ratings,
                'course_ratings' => $this->course_ratings,
                'personal_satisfaction' => $this->personal_satisfaction,
                'isSubmitted' => $this->isSubmitted
            ]);
        }
    }

    public function saveEvaluation()
    {
        $this->validate();

        $student = Auth::user()->student;
        if (! $student) {
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
                // Lấy đợt đánh giá hiện tại chưa được đánh giá
                $currentRounds = \App\Models\EvaluationRound::current()->get();
                $currentRound = null;

                foreach ($currentRounds as $round) {
                    $evaluated = Evaluation::where('student_id', $student->id)
                        ->where('evaluation_round_id', $round->id)
                        ->whereNotNull('submitted_at')
                        ->exists();

                    if (! $evaluated) {
                        $currentRound = $round;
                        break;
                    }
                }

                if ($currentRound) {
                    // Kiểm tra xem đã có evaluation cho đợt này chưa
                    $existingEvaluation = Evaluation::where('student_id', $student->id)
                        ->where('evaluation_round_id', $currentRound->id)
                        ->first();

                    if ($existingEvaluation) {
                        // Cập nhật evaluation hiện có
                        $existingEvaluation->update([
                            'teacher_ratings' => $this->teacher_ratings,
                            'course_ratings' => $this->course_ratings,
                            'personal_satisfaction' => $this->personal_satisfaction,
                            'suggestions' => $this->suggestions,
                        ]);
                        $this->currentEvaluation = $existingEvaluation;
                    } else {
                        // Tạo evaluation mới
                        $this->currentEvaluation = Evaluation::create([
                            'student_id' => $student->id,
                            'evaluation_round_id' => $currentRound->id,
                            'teacher_ratings' => $this->teacher_ratings,
                            'course_ratings' => $this->course_ratings,
                            'personal_satisfaction' => $this->personal_satisfaction,
                            'suggestions' => $this->suggestions,
                        ]);
                    }
                }
            }

            // Đảm bảo dữ liệu được cập nhật trong component
            $this->teacher_ratings = $this->teacher_ratings;
            $this->course_ratings = $this->course_ratings;
            $this->personal_satisfaction = $this->personal_satisfaction;

                        session()->flash('success', 'Đánh giá đã được lưu thành công!');

            // Dispatch event để JavaScript cập nhật hiển thị sao ngay lập tức
            $this->dispatch('evaluation-saved');

            // Thêm script để cập nhật sao ngay lập tức
            $this->dispatch('update-stars');

            // Thêm script để cập nhật sao ngay lập tức
            $this->dispatch('js', 'updateEvaluationStars()');
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi lưu đánh giá.');
        }
    }

    public function submitEvaluation()
    {
        // Kiểm tra validation trước khi submit
        $this->validate();

        $student = Auth::user()->student;
        if (! $student) {
            session()->flash('error', 'Không tìm thấy thông tin học viên.');

            return;
        }

        // Lấy tất cả đợt đánh giá hiện tại
        $currentRounds = \App\Models\EvaluationRound::current()->get();

        if ($currentRounds->count() == 0) {
            session()->flash('error', 'Không có đợt đánh giá nào đang diễn ra.');

            return;
        }

        // Tìm đợt chưa đánh giá
        $currentRound = null;
        foreach ($currentRounds as $round) {
            $evaluated = Evaluation::where('student_id', $student->id)
                ->where('evaluation_round_id', $round->id)
                ->whereNotNull('submitted_at')
                ->exists();

            if (! $evaluated) {
                $currentRound = $round;
                break;
            }
        }

        if (! $currentRound) {
            session()->flash('error', 'Bạn đã đánh giá tất cả đợt hiện tại rồi.');

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

        if (! $this->personal_satisfaction) {
            session()->flash('error', 'Vui lòng đánh giá mức độ hài lòng cá nhân.');

            return;
        }

        try {
            // Kiểm tra xem đã có evaluation cho đợt này chưa
            $existingEvaluation = Evaluation::where('student_id', $student->id)
                ->where('evaluation_round_id', $currentRound->id)
                ->first();

            Log::info('Current round ID: '.$currentRound->id.', Name: '.$currentRound->name);
            Log::info('Existing evaluation found: '.($existingEvaluation ? 'YES' : 'NO'));

            if ($existingEvaluation) {
                // Cập nhật evaluation hiện có
                $existingEvaluation->update([
                    'teacher_ratings' => $this->teacher_ratings,
                    'course_ratings' => $this->course_ratings,
                    'personal_satisfaction' => $this->personal_satisfaction,
                    'suggestions' => $this->suggestions,
                ]);
                $this->currentEvaluation = $existingEvaluation;
                Log::info('Updated existing evaluation');
            } else {
                // Tạo evaluation mới
                Log::info('Creating new evaluation for student '.$student->id.' and round '.$currentRound->id);
                $this->currentEvaluation = Evaluation::create([
                    'student_id' => $student->id,
                    'evaluation_round_id' => $currentRound->id,
                    'teacher_ratings' => $this->teacher_ratings,
                    'course_ratings' => $this->course_ratings,
                    'personal_satisfaction' => $this->personal_satisfaction,
                    'suggestions' => $this->suggestions,
                ]);
                Log::info('Created new evaluation with ID: '.$this->currentEvaluation->id);
            }

            // Kiểm tra xem currentEvaluation có tồn tại không
            if (! $this->currentEvaluation) {
                session()->flash('error', 'Không thể tạo đánh giá. Vui lòng thử lại.');

                return;
            }

            // Đánh dấu đã submit
            $this->currentEvaluation->markAsSubmitted();
            $this->isSubmitted = true;

            // Kiểm tra xem còn đợt nào chưa đánh giá không
            $remainingRounds = \App\Models\EvaluationRound::current()->get();
            $remainingCount = 0;

            foreach ($remainingRounds as $round) {
                $evaluated = Evaluation::where('student_id', $student->id)
                    ->where('evaluation_round_id', $round->id)
                    ->whereNotNull('submitted_at')
                    ->exists();

                if (! $evaluated) {
                    $remainingCount++;
                }
            }

            if ($remainingCount > 0) {
                session()->flash('success', 'Đánh giá đã được gửi thành công! Còn '.$remainingCount.' đợt đánh giá nữa.');
                // Reload để hiện đợt tiếp theo
                $this->loadCurrentEvaluation();
                $this->reset(['teacher_ratings', 'course_ratings', 'personal_satisfaction', 'suggestions']);
            } else {
                session()->flash('success', 'Đánh giá đã được gửi thành công! Bạn có thể tiếp tục sử dụng hệ thống.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi gửi đánh giá: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('student.evaluation.index');
    }
}
