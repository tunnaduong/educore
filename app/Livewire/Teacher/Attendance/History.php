<?php

namespace App\Livewire\Teacher\Attendance;

use App\Models\Attendance;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;

    public $search = '';

    public $selectedClassroom = null;

    public $selectedMonth;

    public $selectedYear;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->selectedMonth = (int) now()->month;
        $this->selectedYear = (int) now()->year;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedMonth()
    {
        $this->selectedMonth = (int) $this->selectedMonth;
        $this->resetPage();
    }

    public function updatedSelectedYear()
    {
        $this->selectedYear = (int) $this->selectedYear;
        $this->resetPage();
    }

    public function getMonthName($month)
    {
        $monthNumber = (int) $month;
        $locale = app()->getLocale();
        
        $monthNames = [
            'vi' => [
                1 => 'Tháng 1', 2 => 'Tháng 2', 3 => 'Tháng 3', 4 => 'Tháng 4',
                5 => 'Tháng 5', 6 => 'Tháng 6', 7 => 'Tháng 7', 8 => 'Tháng 8',
                9 => 'Tháng 9', 10 => 'Tháng 10', 11 => 'Tháng 11', 12 => 'Tháng 12'
            ],
            'en' => [
                1 => 'Month 1', 2 => 'Month 2', 3 => 'Month 3', 4 => 'Month 4',
                5 => 'Month 5', 6 => 'Month 6', 7 => 'Month 7', 8 => 'Month 8',
                9 => 'Month 9', 10 => 'Month 10', 11 => 'Month 11', 12 => 'Month 12'
            ],
            'zh' => [
                1 => '月1', 2 => '月2', 3 => '月3', 4 => '月4',
                5 => '月5', 6 => '月6', 7 => '月7', 8 => '月8',
                9 => '月9', 10 => '月10', 11 => '月11', 12 => '月12'
            ]
        ];
        
        return $monthNames[$locale][$monthNumber] ?? "Month $monthNumber";
    }

    public function render()
    {
        $teacher = Auth::user();

        // Lấy các lớp học mà teacher đang dạy
        $teacherClassrooms = Classroom::whereHas('users', function ($query) use ($teacher) {
            $query->where('user_id', $teacher->id)
                ->where('class_user.role', 'teacher');
        })->pluck('id');

        // Lấy lịch sử điểm danh của các lớp của teacher
        $attendances = Attendance::whereIn('class_id', $teacherClassrooms)
            ->with(['classroom', 'student.user'])
            ->when($this->selectedMonth && $this->selectedYear, function ($query) {
                $query->whereYear('date', $this->selectedYear)
                    ->whereMonth('date', $this->selectedMonth);
            })
            ->when($this->search, function ($query) {
                $query->whereHas('student.user', function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                })
                    ->orWhereHas('classroom', function ($q) {
                        $q->where('name', 'like', '%'.$this->search.'%');
                    });
            })
            ->latest('date')
            ->paginate(15);

        return view('teacher.attendance.history', [
            'attendances' => $attendances,
        ]);
    }
}