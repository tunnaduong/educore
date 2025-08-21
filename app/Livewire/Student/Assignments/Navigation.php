<?php

namespace App\Livewire\Student\Assignments;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Navigation extends Component
{
    public function getTotalAssignmentsProperty()
    {
        $student = Auth::user()->student;
        if (! $student) {
            return 0;
        }

        // Bài tập của lớp chưa kết thúc
        $activeCount = Assignment::whereHas('classroom.students', function ($query) use ($student) {
            $query->where('user_id', $student->user_id);
        })->whereHas('classroom', function ($q) {
            $q->where('status', '!=', 'completed');
        })->count();

        // Bài tập đã nộp của lớp đã kết thúc
        $completedClassCount = Assignment::whereHas('classroom.students', function ($query) use ($student) {
            $query->where('user_id', $student->user_id);
        })->whereHas('classroom', function ($q) {
            $q->where('status', 'completed');
        })->whereHas('submissions', function ($q) use ($student) {
            $q->where('student_id', $student->id);
        })->count();

        return $activeCount + $completedClassCount;
    }

    public function getUpcomingAssignmentsProperty()
    {
        $student = Auth::user()->student;
        if (! $student) {
            return 0;
        }

        return Assignment::whereHas('classroom.students', function ($query) use ($student) {
            $query->where('user_id', $student->user_id);
        })
            ->whereHas('classroom', function ($q) {
                $q->where('status', '!=', 'completed');
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
        if (! $student) {
            return 0;
        }

        return Assignment::whereHas('classroom.students', function ($query) use ($student) {
            $query->where('user_id', $student->user_id);
        })
            ->whereHas('classroom', function ($q) {
                $q->where('status', '!=', 'completed');
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
        if (! $student) {
            return 0;
        }

        // Đã nộp của lớp chưa kết thúc
        $active = AssignmentSubmission::where('student_id', $student->id)
            ->whereHas('assignment.classroom', function ($q) {
                $q->where('status', '!=', 'completed');
            })->count();
        // Đã nộp của lớp đã kết thúc
        $completed = AssignmentSubmission::where('student_id', $student->id)
            ->whereHas('assignment.classroom', function ($q) {
                $q->where('status', 'completed');
            })->count();

        return $active + $completed;
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
