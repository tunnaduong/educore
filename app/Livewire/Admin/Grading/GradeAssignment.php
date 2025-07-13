<?php

namespace App\Livewire\Admin\Grading;

use Livewire\Component;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Support\Facades\Auth;

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
            if (!is_numeric($score)) {
                session()->flash('error', 'Điểm phải là số!');
                return;
            }

            if ($score < 0) {
                session()->flash('error', 'Điểm không được nhỏ hơn 0!');
                return;
            }

            if ($score > 10) {
                session()->flash('error', 'Điểm không được vượt quá 10!');
                return;
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
            session()->flash('success', 'Đã lưu điểm và nhận xét!');
        }

        // Làm mới submissions để cập nhật giao diện
        $this->submissions = AssignmentSubmission::where('assignment_id', $this->assignmentId)
            ->with(['student.user'])
            ->get();
    }

    public function getSubmissionTypeLabel($type)
    {
        return match ($type) {
            'text' => 'Điền từ',
            'essay' => 'Tự luận',
            'image' => 'Upload ảnh',
            'audio' => 'Ghi âm',
            'video' => 'Video',
            default => $type
        };
    }

    public function render()
    {
        return view('admin.grading.grade-assignment', [
            'assignment' => $this->assignment,
            'submissions' => $this->submissions,
        ]);
    }
}
