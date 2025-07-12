<?php

namespace App\Livewire\Student\Assignments;

use Livewire\Component;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Support\Facades\Auth;

class Navigation extends Component
{
    public function getTotalAssignmentsProperty()
    {
        $student = Auth::user()->student;
        if (!$student) return 0;

        return Assignment::whereHas('classroom.students', function ($query) use ($student) {
            $query->where('user_id', $student->user_id);
        })->count();
    }

    public function getUpcomingAssignmentsProperty()
    {
        $student = Auth::user()->student;
        if (!$student) return 0;

        return Assignment::whereHas('classroom.students', function ($query) use ($student) {
            $query->where('user_id', $student->user_id);
        })
        ->where('deadline', '>', now())
        ->whereDoesntHave('submissions', function ($query) use ($student) {
            $query->where('student_id', $student->id);
        })
        ->count();
    }

    public function getOverdueAssignmentsProperty()
    {
        $student = Auth::user()->student;
        if (!$student) return 0;

        return Assignment::whereHas('classroom.students', function ($query) use ($student) {
            $query->where('user_id', $student->user_id);
        })
        ->where('deadline', '<', now())
        ->whereDoesntHave('submissions', function ($query) use ($student) {
            $query->where('student_id', $student->id);
        })
        ->count();
    }

    public function getCompletedAssignmentsProperty()
    {
        $student = Auth::user()->student;
        if (!$student) return 0;

        return AssignmentSubmission::where('student_id', $student->id)->count();
    }

    public function render()
    {
        return view('student.assignments.navigation', [
            'totalAssignments' => $this->totalAssignments,
            'upcomingAssignments' => $this->upcomingAssignments,
            'overdueAssignments' => $this->overdueAssignments,
            'completedAssignments' => $this->completedAssignments,
        ]);
    }
}
