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
            ->get();

        \Log::info('Admin.AttendanceHistory: Loading data', [
            'classroom_id' => $this->classroom->id,
            'selected_month' => $this->selectedMonth,
            'selected_year' => $this->selectedYear,
            'total_students' => $students->count(),
            'total_attendances' => $attendances->count(),
        ]);

        $this->attendanceHistory = [];
        $this->monthlyStats = [];

        // Tạo dữ liệu cho từng học viên
        foreach ($students as $student) {
            // Lấy student record từ bảng students
            $studentRecord = Student::where('user_id', $student->id)->first();

            if ($studentRecord) {
                // Lọc attendance theo student_id
                $studentAttendances = $attendances->where('student_id', $studentRecord->id);

                $totalDays = $studentAttendances->count();
                $presentDays = $studentAttendances->where('present', true)->count();
                $absentDays = $studentAttendances->where('present', false)->count();
                $attendanceRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;

                \Log::info('Admin.AttendanceHistory: Student stats', [
                    'student_name' => $student->name,
                    'student_id' => $studentRecord->id,
                    'total_days' => $totalDays,
                    'present_days' => $presentDays,
                    'absent_days' => $absentDays,
                    'attendance_rate' => $attendanceRate,
                ]);

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

    public function getDayName($date)
    {
        return Carbon::parse($date)->format('D');
    }

    public function render()
    {
        return view('admin.attendance.attendance-history');
    }
}
