<?php

namespace App\Livewire\Student\Assignments;

use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $filterStatus = 'all'; // all, upcoming, overdue, completed

    public $filterClassroom = '';

    public $filterTeacher = '';

    public $filterType = ''; // text, essay, image, audio, video

    public $search = '';

    // Thêm filter thời gian
    public $filterTimeRange = 'all'; // all, today, week, month, custom
    public $filterDateFrom = '';
    public $filterDateTo = '';

    protected $queryString = [
        'filterStatus' => ['except' => 'all'],
        'filterClassroom' => ['except' => ''],
        'filterTeacher' => ['except' => ''],
        'filterType' => ['except' => ''],
        'filterTimeRange' => ['except' => 'all'],
        'filterDateFrom' => ['except' => ''],
        'filterDateTo' => ['except' => ''],
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

    public function updatedFilterTimeRange()
    {
        $this->resetPage();
        // Reset custom dates khi chọn preset
        if ($this->filterTimeRange !== 'custom') {
            $this->filterDateFrom = '';
            $this->filterDateTo = '';
        }
    }

    public function updatedFilterDateFrom()
    {
        $this->resetPage();
    }

    public function updatedFilterDateTo()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterStatus', 'filterClassroom', 'filterTeacher', 'filterType', 'filterTimeRange', 'filterDateFrom', 'filterDateTo']);
        $this->resetPage();
    }

    public function getAssignmentsProperty()
    {
        $student = Auth::user()->student;

        if (! $student) {
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
                'classroom',
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
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        // Filter by time range
        if ($this->filterTimeRange !== 'all') {
            $now = now();

            switch ($this->filterTimeRange) {
                case 'today':
                    $query->whereDate('deadline', $now->toDateString());
                    break;
                case 'week':
                    $query->whereBetween('deadline', [
                        $now->startOfWeek()->toDateTimeString(),
                        $now->endOfWeek()->toDateTimeString()
                    ]);
                    break;
                case 'month':
                    $query->whereBetween('deadline', [
                        $now->startOfMonth()->toDateTimeString(),
                        $now->endOfMonth()->toDateTimeString()
                    ]);
                    break;
                case 'custom':
                    if ($this->filterDateFrom) {
                        $query->where('deadline', '>=', $this->filterDateFrom . ' 00:00:00');
                    }
                    if ($this->filterDateTo) {
                        $query->where('deadline', '<=', $this->filterDateTo . ' 23:59:59');
                    }
                    break;
            }
        }

        return $query->orderBy('deadline', 'asc')->paginate(10);
    }

    public function getClassroomsProperty()
    {
        $student = Auth::user()->student;

        if (! $student) {
            return collect();
        }

        return $student->user->enrolledClassrooms()->with('teachers')->get();
    }

    public function getTeachersProperty()
    {
        $student = Auth::user()->student;

        if (! $student) {
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
        return ! $this->isOverdue($assignment) && ! $this->isCompleted($assignment);
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
