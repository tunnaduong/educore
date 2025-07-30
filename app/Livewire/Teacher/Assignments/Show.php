<?php

namespace App\Livewire\Teacher\Assignments;

use Livewire\Component;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;

class Show extends Component
{
    public $assignment;
    public $classroom;
    public $submissions;
    public $students;
    public $assignmentId;
    public $grading = [];

    public function mount($assignment)
    {
        $this->assignmentId = $assignment;
        $this->assignment = Assignment::with('classroom')->findOrFail($assignment);
        $this->classroom = $this->assignment->classroom;
        $this->submissions = AssignmentSubmission::where('assignment_id', $assignment)->with(['student.user'])->get();
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
        return view('teacher.assignments.show');
    }
}
