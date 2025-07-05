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

    public function mount($assignment)
    {
        $this->assignmentId = $assignment;
        $this->assignment = Assignment::with('classroom')->findOrFail($assignment);
        $this->submissions = AssignmentSubmission::where('assignment_id', $assignment)->with(['student'])->get();
        foreach ($this->submissions as $submission) {
            $this->grading[$submission->id] = [
                'score' => $submission->score,
                'feedback' => $submission->feedback,
            ];
        }
    }

    public function saveGrade($submissionId)
    {
        $score = $this->grading[$submissionId]['score'] ?? null;
        $feedback = $this->grading[$submissionId]['feedback'] ?? null;
        $submission = AssignmentSubmission::find($submissionId);
        if ($submission) {
            $submission->score = $score;
            $submission->feedback = $feedback;
            $submission->save();
            session()->flash('success', 'Đã lưu điểm và nhận xét!');
        }
        // Làm mới submissions để cập nhật giao diện
        $this->submissions = AssignmentSubmission::where('assignment_id', $this->assignmentId)->with(['student'])->get();
    }

    public function render()
    {
        return view('admin.grading.grade-assignment', [
            'assignment' => $this->assignment,
            'submissions' => $this->submissions,
        ]);
    }
}
