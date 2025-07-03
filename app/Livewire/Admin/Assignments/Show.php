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

    public function mount($assignmentId)
    {
        $this->assignmentId = $assignmentId;
        $this->assignment = Assignment::with('classroom')->findOrFail($assignmentId);
        $this->classroom = $this->assignment->classroom;
        $this->submissions = AssignmentSubmission::where('assignment_id', $assignmentId)->with(['student'])->get();
        $this->students = $this->classroom ? $this->classroom->students : collect();
    }

    public function render()
    {
        return view('admin.assignments.show');
    }
}
