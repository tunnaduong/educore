<?php

namespace App\Livewire\Schedules;

use App\Models\Classroom;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

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
            ->with(['teacher', 'students'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterLevel, function ($query) {
                $query->where('level', $this->filterLevel);
            })
            ->when($this->filterTeacher, function ($query) {
                $query->whereHas('teacher', function ($q) {
                    $q->where('name', 'like', '%' . $this->filterTeacher . '%');
                });
            })
            ->orderBy('name')
            ->paginate(10);

        $levels = Classroom::distinct()->pluck('level')->filter();
        $teachers = \App\Models\User::where('role', 'teacher')->orderBy('name')->get();


        $user = Auth::user();
        // Lấy danh sách lịch dạy của giáo viên
        // $schedules = $user->schedules()->with('classroom')->get();

        // Chuyển đổi sang dạng FullCalendar
        // $events = $schedules->map(function ($schedule) {
        //     return [
        //         'title' => $schedule->classroom->name,
        //         'start' => $schedule->start_time,
        //         'end'   => $schedule->end_time,
        //         'color' => '#4e73df',
        //     ];
        // });

        return view('livewire.schedules.index', [
            'classrooms' => $classrooms,
            'levels' => $levels,
            'teachers' => $teachers,
            // 'events' => $events,
        ]);
    }

    public function formatSchedule($schedule)
    {
        if (!$schedule || !is_array($schedule)) {
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
            'Sunday' => 'Chủ nhật'
        ];

        $formattedDays = array_map(function ($day) use ($dayNames) {
            return $dayNames[$day] ?? $day;
        }, $days);

        return implode(', ', $formattedDays) . ' - ' . $time;
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterLevel = '';
        $this->filterTeacher = '';
    }
}
