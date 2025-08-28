<?php

namespace App\Livewire\Teacher\Attendance;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
        $teacher = Auth::user();

        // Lấy các lớp học mà teacher đang dạy
        $teacherClassrooms = Classroom::whereHas('users', function ($query) use ($teacher) {
            $query->where('user_id', $teacher->id)
                ->where('class_user.role', 'teacher');
        })->pluck('id');

        // Thống kê tổng quan cho các lớp của teacher
        $totalStudents = User::whereHas('classrooms', function ($query) use ($teacherClassrooms) {
            $query->whereIn('class_id', $teacherClassrooms);
        })->where('role', 'student')->count();

        $totalClasses = $teacherClassrooms->count();

        // Thống kê điểm danh trong tháng cho các lớp của teacher
        $monthlyAttendances = Attendance::forMonth($this->selectedYear, $this->selectedMonth)
            ->whereIn('class_id', $teacherClassrooms)
            ->get();

        $totalAttendanceDays = $monthlyAttendances->count();
        $totalPresent = $monthlyAttendances->where('present', true)->count();
        $totalAbsent = $monthlyAttendances->where('present', false)->count();

        // Tính tỷ lệ điểm danh
        $attendanceRate = $totalAttendanceDays > 0 ? round(($totalPresent / $totalAttendanceDays) * 100, 1) : 0;

        // Lấy top 5 lớp có điểm danh nhiều nhất (chỉ các lớp của teacher)
        $topClasses = Attendance::forMonth($this->selectedYear, $this->selectedMonth)
            ->whereIn('class_id', $teacherClassrooms)
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

        // Lấy top 5 học viên có điểm danh tốt nhất (chỉ học viên trong các lớp của teacher)
        $topStudents = Attendance::forMonth($this->selectedYear, $this->selectedMonth)
            ->whereIn('class_id', $teacherClassrooms)
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

        // Lấy điểm danh gần đây (chỉ các lớp của teacher)
        $recentAttendances = Attendance::whereIn('class_id', $teacherClassrooms)
            ->with(['classroom', 'student.user'])
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
        $month = (int) $month;

        return __('general.month').' '.$month;
    }

    public function render()
    {
        return view('teacher.attendance.overview');
    }
}
