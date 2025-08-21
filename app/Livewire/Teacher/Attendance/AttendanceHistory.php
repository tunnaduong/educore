<?php

namespace App\Livewire\Teacher\Attendance;

use App\Models\Attendance;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AttendanceHistory extends Component
{
    public Classroom $classroom;

    public $selectedMonth;

    public $selectedYear;

    public $monthlyStats = [];

    public $attendanceHistory = [];

    public function mount($classroom)
    {
        $teacher = Auth::user();

        // Kiểm tra xem teacher có quyền xem lịch sử điểm danh lớp này không
        $hasPermission = $classroom->users()
            ->where('user_id', $teacher->id)
            ->where('class_user.role', 'teacher')
            ->exists();

        if (! $hasPermission) {
            abort(403, 'Bạn không có quyền xem lịch sử điểm danh lớp học này.');
        }

        $this->classroom = $classroom;
        $this->selectedMonth = (int) now()->month;
        $this->selectedYear = (int) now()->year;
        $this->loadAttendanceHistory();
    }

    public function loadAttendanceHistory()
    {
        // Lấy danh sách học viên trong lớp
        $students = $this->classroom->students()->orderBy('name')->get();

        // Thống kê điểm danh trong tháng
        $monthlyAttendances = Attendance::forMonth($this->selectedYear, $this->selectedMonth)
            ->forClass($this->classroom->id)
            ->get();

        $totalStudents = $students->count();
        $totalPresent = $monthlyAttendances->where('present', true)->count();
        $totalAbsent = $monthlyAttendances->where('present', false)->count();
        $totalDays = $monthlyAttendances->count();

        // Tính tỷ lệ trung bình
        $averageRate = $totalDays > 0 ? round(($totalPresent / $totalDays) * 100, 1) : 0;

        $this->monthlyStats = [
            'total_students' => $totalStudents,
            'total_present' => $totalPresent,
            'total_absent' => $totalAbsent,
            'average_rate' => $averageRate,
        ];

        // Tạo thống kê chi tiết cho từng học viên
        $this->attendanceHistory = [];

        foreach ($students as $student) {
            $studentAttendances = $monthlyAttendances->where('student_id', $student->id);
            $totalDays = $studentAttendances->count();
            $presentDays = $studentAttendances->where('present', true)->count();
            $absentDays = $studentAttendances->where('present', false)->count();
            $attendanceRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;

            $this->attendanceHistory[] = [
                'student' => $student,
                'stats' => [
                    'total_days' => $totalDays,
                    'present_days' => $presentDays,
                    'absent_days' => $absentDays,
                    'attendance_rate' => $attendanceRate,
                ],
            ];
        }
    }

    public function updatedSelectedMonth()
    {
        $this->selectedMonth = (int) $this->selectedMonth;
        $this->loadAttendanceHistory();
    }

    public function updatedSelectedYear()
    {
        $this->selectedYear = (int) $this->selectedYear;
        $this->loadAttendanceHistory();
    }

    public function getMonthName($month)
    {
        return 'Tháng '.(int) $month;
    }

    public function render()
    {
        return view('teacher.attendance.attendance-history');
    }
}
