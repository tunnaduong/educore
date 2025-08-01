<?php

namespace App\Livewire\Student\Assignments;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $filterStatus = 'all'; // all, upcoming, overdue, completed
    public $filterClassroom = '';
    public $filterTeacher = '';
    public $filterType = ''; // text, essay, image, audio, video
    public $search = '';

    protected $queryString = [
        'filterStatus' => ['except' => 'all'],
        'filterClassroom' => ['except' => ''],
        'filterTeacher' => ['except' => ''],
        'filterType' => ['except' => ''],
        'search' => ['except' => ''],
    ];

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterClassroom()
    {
        $this->resetPage();
    }

    public function updatedFilterTeacher()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterStatus', 'filterClassroom', 'filterTeacher', 'filterType']);
        $this->resetPage();
    }

    public function getAssignmentsProperty()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return collect();
        }

        $query = Assignment::whereHas('classroom.students', function ($q) use ($student) {
            $q->where('users.id', $student->user_id);
        })
            ->with([
                'classroom.teachers',
                'submissions' => function ($q) use ($student) {
                    $q->where('student_id', $student->id);
                },
                'classroom'
            ]); // Thêm classroom để dùng status

        // Lọc assignment theo trạng thái lớp
        $query->where(function ($q) use ($student) {
            $q->whereHas('classroom', function ($c) {
                $c->where('status', '!=', 'completed');
            })
                // Nếu lớp đã completed thì chỉ lấy bài đã hoàn thành
                ->orWhere(function ($q2) use ($student) {
                    $q2->whereHas('classroom', function ($c2) {
                        $c2->where('status', 'completed');
                    })
                        ->whereHas('submissions', function ($s) use ($student) {
                            $s->where('student_id', $student->id);
                        });
                });
        });

        // Filter by status
        if ($this->filterStatus === 'upcoming') {
            $query->where('deadline', '>', now());
        } elseif ($this->filterStatus === 'overdue') {
            $query->where('deadline', '<', now());
        } elseif ($this->filterStatus === 'completed') {
            $query->whereHas('submissions', function ($q) use ($student) {
                $q->where('student_id', $student->id);
            });
        }

        // Filter by classroom
        if ($this->filterClassroom) {
            $query->where('class_id', $this->filterClassroom);
        }

        // Filter by teacher
        if ($this->filterTeacher) {
            $query->whereHas('classroom', function ($q) {
                $q->whereHas('teachers', function ($t) {
                    $t->where('users.id', $this->filterTeacher);
                });
            });
        }

        // Filter by assignment type
        if ($this->filterType) {
            $query->whereJsonContains('types', $this->filterType);
        }

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('deadline', 'asc')->paginate(10);
    }

    public function getClassroomsProperty()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return collect();
        }

        return $student->user->enrolledClassrooms()->with('teachers')->get();
    }

    public function getTeachersProperty()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return collect();
        }

        return $student->user->enrolledClassrooms()
            ->with('teachers')
            ->get()
            ->pluck('teachers')
            ->flatten()
            ->unique('id');
    }

    public function isOverdue($assignment)
    {
        return $assignment->deadline < now();
    }

    public function isCompleted($assignment)
    {
        return $assignment->submissions->isNotEmpty();
    }

    public function canSubmit($assignment)
    {
        return !$this->isOverdue($assignment) && !$this->isCompleted($assignment);
    }

    public function render()
    {
        return view('student.assignments.index', [
            'assignments' => $this->assignments,
            'classrooms' => $this->classrooms,
            'teachers' => $this->teachers,
        ]);
    }
}
