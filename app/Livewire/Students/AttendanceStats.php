<?php

namespace App\Livewire\Students;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Student;
use Livewire\Component;
use Carbon\Carbon;

class AttendanceStats extends Component
{
    public User $student;
    public $selectedMonth;
    public $selectedYear;
    public $attendanceStats = [];
    public $totalStats = [];

    public function mount($student)
    {
        $this->student = $student;
        $this->selectedMonth = (int) now()->month;
        $this->selectedYear = (int) now()->year;
        $this->loadAttendanceStats();
    }

    public function loadAttendanceStats()
    {
        // Lấy student record từ bảng students
        $studentRecord = Student::where('user_id', $this->student->id)->first();

        if (!$studentRecord) {
            $this->attendanceStats = [];
            $this->totalStats = [
                'total_days' => 0,
                'present_days' => 0,
                'absent_days' => 0,
                'attendance_rate' => 0,
            ];
            return;
        }

        // Lấy dữ liệu điểm danh của học viên trong tháng
        $attendances = Attendance::forStudent($studentRecord->id)
            ->forMonth($this->selectedYear, $this->selectedMonth)
            ->with('classroom')
            ->get();

        $this->attendanceStats = [];
        $totalPresent = 0;
        $totalAbsent = 0;
        $totalDays = 0;

        // Nhóm theo lớp học
        $groupedByClass = $attendances->groupBy('class_id');

        foreach ($groupedByClass as $classId => $classAttendances) {
            $classroom = $classAttendances->first()->classroom;
            $presentDays = $classAttendances->where('present', true)->count();
            $absentDays = $classAttendances->where('present', false)->count();
            $totalClassDays = $classAttendances->count();

            $totalPresent += $presentDays;
            $totalAbsent += $absentDays;
            $totalDays += $totalClassDays;

            $this->attendanceStats[] = [
                'classroom' => $classroom,
                'total_days' => $totalClassDays,
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'attendance_rate' => $totalClassDays > 0 ? round(($presentDays / $totalClassDays) * 100, 1) : 0,
                'attendances' => $classAttendances,
            ];
        }

        // Tính thống kê tổng quan
        $this->totalStats = [
            'total_days' => $totalDays,
            'present_days' => $totalPresent,
            'absent_days' => $totalAbsent,
            'attendance_rate' => $totalDays > 0 ? round(($totalPresent / $totalDays) * 100, 1) : 0,
        ];
    }

    public function updatedSelectedMonth()
    {
        $this->selectedMonth = (int) $this->selectedMonth;
        $this->loadAttendanceStats();
    }

    public function updatedSelectedYear()
    {
        $this->selectedYear = (int) $this->selectedYear;
        $this->loadAttendanceStats();
    }

    public function getMonthName($month)
    {
        return Carbon::create()->month((int) $month)->format('F');
    }

    public function getStatusBadge($rate)
    {
        if ($rate >= 90) {
            return '<span class="badge bg-success">Tốt</span>';
        } elseif ($rate >= 70) {
            return '<span class="badge bg-warning">Khá</span>';
        } else {
            return '<span class="badge bg-danger">Cần cải thiện</span>';
        }
    }

    public function render()
    {
        return view('livewire.students.attendance-stats');
    }
}
