<?php

namespace App\Livewire\Admin\Assignments;

use Livewire\Component;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\User;

class Show extends Component
{
    public $assignment;
    public $classroom;
    public $submissions;
    public $students;
    public $assignmentId;
    public $grading = [];

    public function mount($assignmentId)
    {
        $this->assignmentId = $assignmentId;
        $this->assignment = Assignment::with('classroom')->findOrFail($assignmentId);
        $this->classroom = $this->assignment->classroom;
        $this->submissions = AssignmentSubmission::where('assignment_id', $assignmentId)->with(['student.user'])->get();
        $this->students = $this->classroom ? $this->classroom->students : collect();
    }

    public function updatedGrading($value, $key)
    {
        // Không làm gì, chỉ để Livewire nhận biết thay đổi
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
        return view('admin.assignments.show');
    }
}
