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
            // Map từ user_id sang student_id trong bảng students
            $studentRecord = \App\Models\Student::where('user_id', $student->id)->first();

            if (! $studentRecord) {
                \Log::warning('Teacher.AttendanceHistory: Missing student record for user', [
                    'user_id' => $student->id,
                    'user_name' => $student->name,
                ]);
                continue;
            }

            // Lọc attendance theo student_id (theo bảng students)
            $studentAttendances = $monthlyAttendances->where('student_id', $studentRecord->id);

            $totalDays = $studentAttendances->count();
            $presentDays = $studentAttendances->where('present', true)->count();
            $absentDays = $studentAttendances->where('present', false)->count();
            $attendanceRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;

            $this->attendanceHistory[] = [
                'student' => $student,
                'student_record' => $studentRecord,
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
        $monthNumber = (int) $month;
        $locale = app()->getLocale();

        $monthNames = [
            'vi' => [
                1 => 'Tháng 1', 2 => 'Tháng 2', 3 => 'Tháng 3', 4 => 'Tháng 4',
                5 => 'Tháng 5', 6 => 'Tháng 6', 7 => 'Tháng 7', 8 => 'Tháng 8',
                9 => 'Tháng 9', 10 => 'Tháng 10', 11 => 'Tháng 11', 12 => 'Tháng 12',
            ],
            'en' => [
                1 => 'Month 1', 2 => 'Month 2', 3 => 'Month 3', 4 => 'Month 4',
                5 => 'Month 5', 6 => 'Month 6', 7 => 'Month 7', 8 => 'Month 8',
                9 => 'Month 9', 10 => 'Month 10', 11 => 'Month 11', 12 => 'Month 12',
            ],
            'zh' => [
                1 => '月1', 2 => '月2', 3 => '月3', 4 => '月4',
                5 => '月5', 6 => '月6', 7 => '月7', 8 => '月8',
                9 => '月9', 10 => '月10', 11 => '月11', 12 => '月12',
            ],
        ];

        return $monthNames[$locale][$monthNumber] ?? "Month $monthNumber";
    }

    public function render()
    {
        return view('teacher.attendance.attendance-history');
    }
}
