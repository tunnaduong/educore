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

    public $monthlyTrendData = [];

    public function mount()
    {
        $this->selectedMonth = (int) now()->month;
        $this->selectedYear = (int) now()->year;
        $this->loadOverviewStats();
        $this->loadMonthlyTrendData();
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

        // Lấy dữ liệu tháng trước để so sánh
        $previousMonth = Carbon::create($this->selectedYear, $this->selectedMonth, 1)->subMonth();
        $previousMonthAttendances = Attendance::forMonth($previousMonth->year, $previousMonth->month)->get();
        $previousMonthPresent = $previousMonthAttendances->where('present', true)->count();
        $previousMonthTotal = $previousMonthAttendances->count();
        $previousMonthRate = $previousMonthTotal > 0 ? round(($previousMonthPresent / $previousMonthTotal) * 100, 1) : 0;

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
            'previous_month_present' => $previousMonthPresent,
            'previous_month_rate' => $previousMonthRate,
        ];
    }

    protected $listeners = ['updatedSelectedMonth', 'updatedSelectedYear'];

    public function updatedSelectedMonth($month)
    {
        $this->selectedMonth = (int) $month;
        $this->loadOverviewStats();
    }

    public function updatedSelectedYear($year)
    {
        $this->selectedYear = (int) $year;
        $this->loadOverviewStats();
    }

    public function loadMonthlyTrendData()
    {
        $monthlyData = [];
        $currentDate = Carbon::now();

        // Lấy dữ liệu cho 12 tháng gần nhất
        for ($i = 11; $i >= 0; $i--) {
            $date = $currentDate->copy()->subMonths($i);
            $year = $date->year;
            $month = $date->month;

            // Lấy dữ liệu điểm danh cho tháng này
            $monthlyAttendances = Attendance::forMonth($year, $month)->get();
            $totalPresent = $monthlyAttendances->where('present', true)->count();
            $totalCount = $monthlyAttendances->count();
            $attendanceRate = $totalCount > 0 ? round(($totalPresent / $totalCount) * 100, 1) : 0;

            $monthlyData[] = [
                'month' => $date->format('M Y'),
                'month_name' => $date->format('F'),
                'year' => $year,
                'month_number' => $month,
                'rate' => $attendanceRate,
                'present' => $totalPresent,
                'total' => $totalCount,
            ];
        }

        $this->monthlyTrendData = $monthlyData;
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
