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
