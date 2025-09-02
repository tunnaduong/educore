<?php

namespace App\Livewire\Teacher\Grading;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GradeAssignment extends Component
{
    public $assignment;

    public $submissions;

    public $grading = [];

    public $assignmentId;

    public $selectedSubmission = null;

    public $showModal = false;

    public function mount($assignment)
    {
        $this->assignmentId = $assignment;
        $this->assignment = Assignment::with('classroom')->findOrFail($assignment);

        // Kiểm tra quyền: chỉ giáo viên dạy lớp này mới được chấm
        $user = Auth::user();
        $userClassIds = $user->teachingClassrooms->pluck('id');
        if (! in_array($this->assignment->class_id, $userClassIds->toArray())) {
            abort(403, 'Bạn không có quyền chấm bài tập này.');
        }

        $this->submissions = AssignmentSubmission::where('assignment_id', $assignment)
            ->with(['student.user'])
            ->get();
        foreach ($this->submissions as $submission) {
            $this->grading[$submission->id] = [
                'score' => $submission->score,
                'feedback' => $submission->feedback,
            ];
        }
    }

    public function viewSubmission($submissionId)
    {
        $this->selectedSubmission = AssignmentSubmission::with(['student.user', 'assignment'])
            ->find($submissionId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedSubmission = null;
    }

    public function saveGrade($submissionId)
    {
        $score = $this->grading[$submissionId]['score'] ?? null;
        $feedback = $this->grading[$submissionId]['feedback'] ?? null;

        // Validation cho điểm
        if ($score !== null && $score !== '') {
            // Kiểm tra xem có phải là số hợp lệ không
            if (! is_numeric($score) || ! is_finite($score)) {
                return redirect()->route('teacher.grading.grade-assignment', $this->assignmentId)
                    ->with('error', 'Điểm phải là số hợp lệ!');
            }

            // Chuyển đổi thành float để so sánh chính xác
            $score = (float) $score;

            if ($score < 0) {
                return redirect()->route('teacher.grading.grade-assignment', $this->assignmentId)
                    ->with('error', 'Điểm không được nhỏ hơn 0!');
            }

            if ($score > 10) {
                return redirect()->route('teacher.grading.grade-assignment', $this->assignmentId)
                    ->with('error', 'Điểm không được vượt quá 10!');
            }

            // Kiểm tra số thập phân (chỉ cho phép tối đa 1 chữ số thập phân)
            if (strpos((string) $score, '.') !== false) {
                $decimalPlaces = strlen(substr(strrchr((string) $score, '.'), 1));
                if ($decimalPlaces > 1) {
                    return redirect()->route('teacher.grading.grade-assignment', $this->assignmentId)
                        ->with('error', 'Điểm chỉ được có tối đa 1 chữ số thập phân!');
                }
            }
        }

        // Nếu điểm là rỗng (empty string), set null
        if ($score === '' || $score === null) {
            $score = null;
        }

        $submission = AssignmentSubmission::find($submissionId);
        if ($submission) {
            $submission->score = $score;
            $submission->feedback = $feedback;
            $submission->save();
            // Lưu thành công: quay về trang danh sách chấm điểm
            return redirect()->route('teacher.grading.index')
                ->with('success', 'Đã lưu điểm và nhận xét!');
        } else {
            return redirect()->route('teacher.grading.grade-assignment', $this->assignmentId)
                ->with('error', 'Không tìm thấy bài nộp để lưu!');
        }
    }

    public function getSubmissionTypeLabel($type)
    {
        return match ($type) {
            'text' => __('general.text_type'),
            'essay' => __('general.essay_type'),
            'image' => __('general.image_type'),
            'audio' => __('general.audio_type'),
            'video' => __('general.video_type'),
            default => $type
        };
    }

    public function render()
    {
        return view('teacher.grading.grade-assignment', [
            'assignment' => $this->assignment,
            'submissions' => $this->submissions,
        ]);
    }
}
