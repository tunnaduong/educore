<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class AttendanceOverview extends Component
{
    public $selectedMonth;

    public $selectedYear;

    public $overviewStats = [];

    public function mount()
    {
        $this->selectedMonth = (int) now()->month;
        $this->selectedYear = (int) now()->year;
        $this->loadOverviewStats();
    }

    public function loadOverviewStats()
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

        // Phân bố trạng thái trong tháng
        // Hệ thống hiện mới lưu 'present' (boolean), chưa có cột 'late' -> mặc định 0
        $statusCounts = [
            'present' => $totalPresent,
            'absent' => $totalAbsent,
            'late' => 0,
        ];

        // Xu hướng theo ngày: mỗi ngày gồm present/total/rate
        $dailyTrend = $monthlyAttendances
            ->groupBy(function ($attendance) {
                return Carbon::parse($attendance->date)->toDateString();
            })
            ->map(function ($items, $date) {
                $present = $items->where('present', true)->count();
                $total = $items->count();
                $rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;

                return [
                    'date' => $date,
                    'present' => $present,
                    'total' => $total,
                    'rate' => $rate,
                ];
            })
            ->sortBy('date')
            ->values();

        // Top 5 học viên có điểm danh tốt nhất
        $topStudents = Attendance::forMonth($this->selectedYear, $this->selectedMonth)
            ->with('student.user')
            ->get()
            ->groupBy('student_id')
            ->map(function ($attendances) {
                $totalDays = $attendances->count();
                $presentDays = $attendances->where('present', true)->count();

                return [
                    'student' => $attendances->first()->student->user,
                    'total_days' => $totalDays,
                    'present_days' => $presentDays,
                    'rate' => $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0,
                ];
            })
            ->sortByDesc('rate')
            ->take(5);

        // Top 5 lớp có điểm danh tốt nhất
        $topClasses = Attendance::forMonth($this->selectedYear, $this->selectedMonth)
            ->with('classroom')
            ->get()
            ->groupBy('class_id')
            ->map(function ($attendances) {
                $totalDays = $attendances->count();
                $presentDays = $attendances->where('present', true)->count();

                return [
                    'classroom' => $attendances->first()->classroom,
                    'total_days' => $totalDays,
                    'present_days' => $presentDays,
                    'rate' => $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0,
                ];
            })
            ->sortByDesc('rate')
            ->take(5);

        $this->overviewStats = [
            'total_students' => $totalStudents,
            'total_classes' => $totalClasses,
            'total_attendance_days' => $totalAttendanceDays,
            'total_present' => $totalPresent,
            'total_absent' => $totalAbsent,
            'attendance_rate' => $attendanceRate,
            'status_counts' => $statusCounts,
            'daily_trend' => $dailyTrend,
            'top_students' => $topStudents,
            'top_classes' => $topClasses,
        ];
    }

    public function updatedSelectedMonth()
    {
        $this->selectedMonth = (int) $this->selectedMonth;
        $this->loadOverviewStats();
    }

    public function updatedSelectedYear()
    {
        $this->selectedYear = (int) $this->selectedYear;
        $this->loadOverviewStats();
    }

    public function getMonthName($month)
    {
        return Carbon::create()->month((int) $month)->format('F');
    }

    public function render()
    {
        return view('admin.dashboard.attendance-overview');
    }
}
