<?php

namespace App\Livewire\Admin\Attendance;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\User;
use Livewire\Component;

class Overview extends Component
{
    public $selectedMonth;

    public $selectedYear;

    public $overviewStats = [];

    public $recentAttendances = [];

    public $topClasses = [];

    public $topStudents = [];

    public function mount()
    {
        $this->selectedMonth = (int) now()->month;
        $this->selectedYear = (int) now()->year;
        $this->loadOverviewData();
    }

    public function loadOverviewData()
    {
        // Thống kê tổng quan
        $totalStudents = User::where('role', 'student')->count();
        $totalClasses = Classroom::where('status', 'active')->count();

        // Thống kê điểm danh trong tháng
        $monthlyAttendances = Attendance::forMonth($this->selectedYear, $this->selectedMonth)->get();
        $totalAttendanceDays = $monthlyAttendances->count();
        $totalPresent = $monthlyAttendances->where('present', true)->count();
        $totalAbsent = $monthlyAttendances->where('present', false)->count();

        // Tính tỷ lệ điểm danh
        $attendanceRate = $totalAttendanceDays > 0 ? round(($totalPresent / $totalAttendanceDays) * 100, 1) : 0;

        // Lấy top 5 lớp có điểm danh nhiều nhất
        $topClasses = Attendance::forMonth($this->selectedYear, $this->selectedMonth)
            ->with('classroom')
            ->get()
            ->groupBy('class_id')
            ->map(function ($attendances) {
                return [
                    'classroom' => $attendances->first()->classroom,
                    'total_days' => $attendances->count(),
                    'present_days' => $attendances->where('present', true)->count(),
                    'attendance_rate' => $attendances->count() > 0 ?
                        round(($attendances->where('present', true)->count() / $attendances->count()) * 100, 1) : 0,
                ];
            })
            ->sortByDesc('total_days')
            ->take(5);

        // Lấy top 5 học viên có điểm danh tốt nhất
        $topStudents = Attendance::forMonth($this->selectedYear, $this->selectedMonth)
            ->with('student.user')
            ->get()
            ->groupBy('student_id')
            ->map(function ($attendances) {
                return [
                    'student' => $attendances->first()->student->user,
                    'total_days' => $attendances->count(),
                    'present_days' => $attendances->where('present', true)->count(),
                    'attendance_rate' => $attendances->count() > 0 ?
                        round(($attendances->where('present', true)->count() / $attendances->count()) * 100, 1) : 0,
                ];
            })
            ->sortByDesc('attendance_rate')
            ->take(5);

        // Lấy điểm danh gần đây
        $recentAttendances = Attendance::with(['classroom', 'student.user'])
            ->latest('date')
            ->take(10)
            ->get();

        $this->overviewStats = [
            'total_students' => $totalStudents,
            'total_classes' => $totalClasses,
            'total_attendance_days' => $totalAttendanceDays,
            'total_present' => $totalPresent,
            'total_absent' => $totalAbsent,
            'attendance_rate' => $attendanceRate,
        ];

        $this->topClasses = $topClasses;
        $this->topStudents = $topStudents;
        $this->recentAttendances = $recentAttendances;
    }

    public function updatedSelectedMonth()
    {
        $this->selectedMonth = (int) $this->selectedMonth;
        $this->loadOverviewData();
    }

    public function updatedSelectedYear()
    {
        $this->selectedYear = (int) $this->selectedYear;
        $this->loadOverviewData();
    }

    public function getMonthName($month)
    {
        return 'Tháng '.(int) $month;
    }

    public function render()
    {
        return view('admin.attendance.overview');
    }
}
