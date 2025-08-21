<?php

namespace App\Livewire\Admin\Attendance;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Student;
use Carbon\Carbon;
use Livewire\Component;

class AttendanceHistory extends Component
{
    public Classroom $classroom;

    public $selectedMonth;

    public $selectedYear;

    public $attendanceHistory = [];

    public $monthlyStats = [];

    public function mount($classroom)
    {
        $this->classroom = $classroom;
        $this->selectedMonth = (int) now()->month;
        $this->selectedYear = (int) now()->year;
        $this->loadAttendanceHistory();
    }

    public function loadAttendanceHistory()
    {
        // Lấy danh sách học viên trong lớp
        $students = $this->classroom->students()->orderBy('name')->get();

        // Lấy dữ liệu điểm danh trong tháng
        $attendances = Attendance::forClass($this->classroom->id)
            ->forMonth($this->selectedYear, $this->selectedMonth)
            ->with('student.user')
            ->get()
            ->groupBy(['date', 'student_id']);

        $this->attendanceHistory = [];
        $this->monthlyStats = [];

        // Tạo dữ liệu cho từng học viên
        foreach ($students as $student) {
            // Lấy student record từ bảng students
            $studentRecord = Student::where('user_id', $student->id)->first();

            if ($studentRecord) {
                $studentStats = [
                    'total_days' => 0,
                    'present_days' => 0,
                    'absent_days' => 0,
                    'attendance_rate' => 0,
                ];

                $this->attendanceHistory[$studentRecord->id] = [
                    'student' => $student,
                    'student_record' => $studentRecord,
                    'attendance' => [],
                    'stats' => $studentStats,
                ];
            }
        }

        // Điền dữ liệu điểm danh
        foreach ($attendances as $date => $dateAttendances) {
            foreach ($dateAttendances as $studentId => $attendance) {
                if (isset($this->attendanceHistory[$studentId])) {
                    $this->attendanceHistory[$studentId]['attendance'][$date] = $attendance->first();

                    // Cập nhật thống kê
                    $this->attendanceHistory[$studentId]['stats']['total_days']++;
                    if ($attendance->first()->present) {
                        $this->attendanceHistory[$studentId]['stats']['present_days']++;
                    } else {
                        $this->attendanceHistory[$studentId]['stats']['absent_days']++;
                    }
                }
            }
        }

        // Tính tỷ lệ điểm danh
        foreach ($this->attendanceHistory as $studentId => &$data) {
            if ($data['stats']['total_days'] > 0) {
                $data['stats']['attendance_rate'] = round(
                    ($data['stats']['present_days'] / $data['stats']['total_days']) * 100,
                    1
                );
            }
        }

        // Tính thống kê tổng quan
        $this->calculateMonthlyStats();
    }

    public function calculateMonthlyStats()
    {
        $totalStudents = count($this->attendanceHistory);
        $totalDays = 0;
        $totalPresent = 0;
        $totalAbsent = 0;

        foreach ($this->attendanceHistory as $data) {
            $totalDays += $data['stats']['total_days'];
            $totalPresent += $data['stats']['present_days'];
            $totalAbsent += $data['stats']['absent_days'];
        }

        $this->monthlyStats = [
            'total_students' => $totalStudents,
            'total_days' => $totalDays,
            'total_present' => $totalPresent,
            'total_absent' => $totalAbsent,
            'average_rate' => $totalDays > 0 ? round(($totalPresent / $totalDays) * 100, 1) : 0,
        ];
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

    public function getDayName($date)
    {
        return Carbon::parse($date)->format('D');
    }

    public function render()
    {
        return view('admin.attendance.attendance-history');
    }
}
