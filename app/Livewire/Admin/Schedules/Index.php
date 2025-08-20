<?php

namespace App\Livewire\Admin\Schedules;

use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $filterLevel = '';

    public $filterTeacher = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterLevel' => ['except' => ''],
        'filterTeacher' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterLevel()
    {
        $this->resetPage();
    }

    public function updatingFilterTeacher()
    {
        $this->resetPage();
    }

    public function render()
    {
        $classrooms = Classroom::query()
            ->with(['teachers', 'students'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->when($this->filterLevel, function ($query) {
                $query->where('level', $this->filterLevel);
            })
            ->when($this->filterTeacher, function ($query) {
                $query->whereHas('teachers', function ($q) {
                    $q->where('name', 'like', '%'.$this->filterTeacher.'%');
                });
            })
            ->orderBy('name')
            ->paginate(10);

        $levels = Classroom::distinct()->pluck('level')->filter();
        $teachers = \App\Models\User::where('role', 'teacher')->orderBy('name')->get();

        $user = Auth::user();

        return view('admin.schedules.index', [
            'classrooms' => $classrooms,
            'levels' => $levels,
            'teachers' => $teachers,
        ]);
    }

    public function formatSchedule($schedule)
    {
        if (! $schedule || ! is_array($schedule)) {
            return 'Chưa có lịch học';
        }

        $days = $schedule['days'] ?? [];
        $time = $schedule['time'] ?? '';

        if (empty($days) || empty($time)) {
            return 'Chưa có lịch học';
        }

        $dayNames = [
            'Monday' => 'Thứ 2',
            'Tuesday' => 'Thứ 3',
            'Wednesday' => 'Thứ 4',
            'Thursday' => 'Thứ 5',
            'Friday' => 'Thứ 6',
            'Saturday' => 'Thứ 7',
            'Sunday' => 'Chủ nhật',
        ];

        $formattedDays = array_map(function ($day) use ($dayNames) {
            return $dayNames[$day] ?? $day;
        }, $days);

        return implode(', ', $formattedDays).' - '.$time;
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterLevel = '';
        $this->filterTeacher = '';
    }
}
