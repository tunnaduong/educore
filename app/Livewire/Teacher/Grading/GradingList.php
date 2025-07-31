<?php

namespace App\Livewire\Teacher\Grading;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;

class GradingList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterClassroom = '';
    public $filterStatus = 'all'; // all, has_submissions, no_submissions
    public $sortBy = 'submissions_count'; // submissions_count, created_at, deadline
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterClassroom' => ['except' => ''],
        'filterStatus' => ['except' => 'all'],
        'sortBy' => ['except' => 'submissions_count'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterClassroom()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'desc';
        }
        $this->resetPage();
    }

    public function getAssignmentsProperty()
    {
        $user = Auth::user();
        $classIds = $user->teachingClassrooms->pluck('id');
        $query = Assignment::withCount('submissions')
            ->with([
                'classroom.teachers',
                'submissions.student.user'
            ])
            ->whereIn('class_id', $classIds);

        // Filter by search
        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%');
        }

        // Filter by classroom
        if ($this->filterClassroom) {
            $query->where('class_id', $this->filterClassroom);
        }

        // Filter by status
        if ($this->filterStatus === 'has_submissions') {
            $query->whereHas('submissions');
        } elseif ($this->filterStatus === 'no_submissions') {
            $query->whereDoesntHave('submissions');
        }

        // Sort by
        switch ($this->sortBy) {
            case 'submissions_count':
                $query->orderBy('submissions_count', $this->sortDirection);
                break;
            case 'created_at':
                $query->orderBy('created_at', $this->sortDirection);
                break;
            case 'deadline':
                $query->orderBy('deadline', $this->sortDirection);
                break;
            default:
                $query->orderBy('submissions_count', 'desc');
        }

        return $query->paginate(15);
    }

    public function getClassroomsProperty()
    {
        $user = Auth::user();
        return $user->teachingClassrooms;
    }

    public function selectAssignment($assignmentId)
    {
        return $this->redirect(route('teacher.grading.grade-assignment', ['assignment' => $assignmentId]), true);
    }

    public function render()
    {
        return view('teacher.grading.grading-list', [
            'assignments' => $this->assignments,
            'classrooms' => $this->classrooms,
        ]);
    }
}
