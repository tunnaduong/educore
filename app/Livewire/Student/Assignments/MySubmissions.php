<?php

namespace App\Livewire\Student\Assignments;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AssignmentSubmission;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;

class MySubmissions extends Component
{
    use WithPagination;

    public $filterStatus = 'all'; // all, graded, ungraded
    public $search = '';
    public $filterClassroom = '';
    public $filterGraded = '';

    protected $queryString = [
        'filterStatus' => ['except' => 'all'],
        'search' => ['except' => ''],
        'filterClassroom' => ['except' => ''],
        'filterGraded' => ['except' => ''],
    ];

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterClassroom()
    {
        $this->resetPage();
    }

    public function updatedFilterGraded()
    {
        $this->resetPage();
    }

    public function getSubmissionsProperty()
    {
        $student = Auth::user()->student;
        
        if (!$student) {
            return collect();
        }

        $query = AssignmentSubmission::where('student_id', $student->id)
            ->with(['assignment.classroom.teacher', 'assignment']);

        // Filter by status
        if ($this->filterGraded === 'graded') {
            $query->whereNotNull('score');
        } elseif ($this->filterGraded === 'ungraded') {
            $query->whereNull('score');
        }

        // Filter by classroom
        if ($this->filterClassroom) {
            $query->whereHas('assignment.classroom', function ($q) {
                $q->where('id', $this->filterClassroom);
            });
        }

        // Search
        if ($this->search) {
            $query->whereHas('assignment', function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('submitted_at', 'desc')->paginate(10);
    }

    public function getClassroomsProperty()
    {
        $student = Auth::user()->student;
        
        if (!$student) {
            return collect();
        }

        return Classroom::whereHas('students', function ($query) use ($student) {
            $query->where('user_id', $student->user_id);
        })->get();
    }

    public function getTotalSubmissionsProperty()
    {
        $student = Auth::user()->student;
        if (!$student) return 0;

        return AssignmentSubmission::where('student_id', $student->id)->count();
    }

    public function getGradedSubmissionsProperty()
    {
        $student = Auth::user()->student;
        if (!$student) return 0;

        return AssignmentSubmission::where('student_id', $student->id)
            ->whereNotNull('score')->count();
    }

    public function getUngradedSubmissionsProperty()
    {
        $student = Auth::user()->student;
        if (!$student) return 0;

        return AssignmentSubmission::where('student_id', $student->id)
            ->whereNull('score')->count();
    }

    public function getAverageScoreProperty()
    {
        $student = Auth::user()->student;
        if (!$student) return 0;

        $gradedSubmissions = AssignmentSubmission::where('student_id', $student->id)
            ->whereNotNull('score')->get();

        if ($gradedSubmissions->count() === 0) {
            return 0;
        }

        return $gradedSubmissions->avg('score');
    }

    public function getSubmissionType($submission)
    {
        return $submission->submission_type ?? 'text';
    }

    public function getScoreColor($score)
    {
        if ($score === null) {
            return 'text-gray-500';
        }
        
        if ($score >= 8) {
            return 'text-green-600';
        } elseif ($score >= 6) {
            return 'text-yellow-600';
        } else {
            return 'text-red-600';
        }
    }

    public function render()
    {
        return view('student.assignments.my-submissions', [
            'submissions' => $this->submissions,
            'classrooms' => $this->classrooms,
            'totalSubmissions' => $this->totalSubmissions,
            'gradedSubmissions' => $this->gradedSubmissions,
            'ungradedSubmissions' => $this->ungradedSubmissions,
            'averageScore' => $this->averageScore,
        ]);
    }
}
