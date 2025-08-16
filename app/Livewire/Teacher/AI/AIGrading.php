<?php

namespace App\Livewire\Teacher\AI;

use Livewire\Component;
use App\Models\AssignmentSubmission;
use App\Models\Assignment;
use App\Helpers\AIHelper;
use Illuminate\Support\Facades\Auth;

class AIGrading extends Component
{
    public $submission;
    public $assignment;
    public $aiResult = null;
    public $showAIFeedback = false;

    public function mount($submissionId)
    {
        $this->submission = AssignmentSubmission::with(['assignment', 'student.user'])
            ->findOrFail($submissionId);
        $this->assignment = $this->submission->assignment;

        // Kiểm tra quyền: chỉ giáo viên dạy lớp này mới được chấm
        $user = Auth::user();
        $userClassIds = $user->teachingClassrooms->pluck('id');
        if (!in_array($this->assignment->class_id, $userClassIds->toArray())) {
            abort(403, 'Bạn không có quyền chấm bài tập này.');
        }

        // Kiểm tra loại bài nộp: không cho phép chấm bài có submission_type = image, audio, video
        if (in_array($this->submission->submission_type, ['image', 'audio', 'video'])) {
            $typeNames = [
                'image' => 'hình ảnh',
                'audio' => 'âm thanh', 
                'video' => 'video'
            ];
            $typeName = $typeNames[$this->submission->submission_type] ?? $this->submission->submission_type;
            abort(403, "Không thể chấm bài tập có loại nộp là {$typeName} bằng AI.");
        }
    }

    public function gradeWithAI()
    {
        $this->aiResult = null;

        try {
            $aiHelper = new AIHelper();

            if (!$aiHelper->isAIAvailable()) {
                session()->flash('error', 'AI service không khả dụng. Vui lòng kiểm tra cấu hình API.');
                return;
            }

            $result = $aiHelper->gradeEssayWithAI($this->submission, $this->assignment);

            if ($result) {
                $this->aiResult = $result;
                $this->showAIFeedback = true;
                session()->flash('success', 'Đã chấm bài bằng AI thành công!');
            } else {
                session()->flash('error', 'Không thể chấm bài bằng AI. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function applyAIScore()
    {
        if ($this->aiResult && isset($this->aiResult['score'])) {
            $this->submission->score = $this->aiResult['score'];
            $this->submission->feedback = $this->aiResult['feedback'];
            $this->submission->save();

            session()->flash('success', 'Đã áp dụng điểm và feedback từ AI!');
            $this->showAIFeedback = false;
        }
    }

    public function correctGrammarWithAI()
    {
        try {
            $aiHelper = new AIHelper();

            if (!$aiHelper->isAIAvailable()) {
                session()->flash('error', 'AI service không khả dụng. Vui lòng kiểm tra cấu hình API.');
                return;
            }

            $result = $aiHelper->correctStudentSubmission($this->submission);

            if ($result) {
                session()->flash('success', 'Đã sửa lỗi ngữ pháp bằng AI!');
                $this->submission->refresh();
            } else {
                session()->flash('error', 'Không thể sửa lỗi ngữ pháp. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function analyzeWithAI()
    {
        try {
            $aiHelper = new AIHelper();

            if (!$aiHelper->isAIAvailable()) {
                session()->flash('error', 'AI service không khả dụng. Vui lòng kiểm tra cấu hình API.');
                return;
            }

            $result = $aiHelper->analyzeAssignmentWithAI($this->submission, $this->assignment);

            if ($result) {
                session()->flash('success', 'Đã phân tích bài tập bằng AI!');
                $this->submission->refresh();
            } else {
                session()->flash('error', 'Không thể phân tích bài tập. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('teacher.ai.ai-grading', [
            'submission' => $this->submission,
            'assignment' => $this->assignment,
            'aiResult' => $this->aiResult,
            'showAIFeedback' => $this->showAIFeedback,
        ]);
    }
}