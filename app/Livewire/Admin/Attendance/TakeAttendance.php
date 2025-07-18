<?php

namespace App\Livewire\Admin\Attendance;

use App\Models\Classroom;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;

class TakeAttendance extends Component
{
    public Classroom $classroom;
    public $selectedDate;
    public $attendanceData = [];
    public $showReasonModal = false;
    public $selectedStudentId;
    public $absenceReason = '';

    protected function rules()
    {
        return [
            'selectedDate' => 'required|date',
            'absenceReason' => 'nullable|string|max:255',
        ];
    }

    protected function messages()
    {
        return [
            'selectedDate.required' => 'Vui lòng chọn ngày điểm danh.',
            'selectedDate.date' => 'Ngày không đúng định dạng.',
            'absenceReason.max' => 'Lý do nghỉ không được quá 255 ký tự.',
        ];
    }

    public function mount($classroom)
    {
        $this->classroom = $classroom;
        $this->selectedDate = now()->format('Y-m-d');
        $this->loadAttendanceData();
    }

    public function loadAttendanceData()
    {
        // Lấy danh sách học viên trong lớp
        $students = $this->classroom->students()->orderBy('name')->get();

        // Lấy dữ liệu điểm danh đã có cho ngày này
        $existingAttendance = Attendance::forClass($this->classroom->id)
            ->forDate($this->selectedDate)
            ->get()
            ->keyBy('student_id');

        $this->attendanceData = [];

        foreach ($students as $student) {
            // Lấy student record từ bảng students
            $studentRecord = Student::where('user_id', $student->id)->first();

            if ($studentRecord) {
                $existing = $existingAttendance->get($studentRecord->id);
                $this->attendanceData[$studentRecord->id] = [
                    'student' => $student,
                    'student_record' => $studentRecord,
                    'present' => $existing ? $existing->present : true,
                    'reason' => $existing ? $existing->reason : '',
                    'hasExisting' => $existing ? true : false,
                ];
            }
        }
    }

    public function updatedSelectedDate()
    {
        $this->loadAttendanceData();
    }

    public function toggleAttendance($studentId)
    {
        if (isset($this->attendanceData[$studentId])) {
            $this->attendanceData[$studentId]['present'] = !$this->attendanceData[$studentId]['present'];

            // Nếu chuyển từ vắng sang có mặt, xóa lý do nghỉ
            if ($this->attendanceData[$studentId]['present']) {
                $this->attendanceData[$studentId]['reason'] = '';
            }
        }
        $this->dispatch('hide-loading');
    }

    public function openReasonModal($studentId)
    {
        $this->selectedStudentId = $studentId;
        $this->absenceReason = $this->attendanceData[$studentId]['reason'] ?? '';
        $this->showReasonModal = true;
    }

    public function saveReason()
    {
        $this->validate([
            'absenceReason' => 'nullable|string|max:255',
        ]);

        if ($this->selectedStudentId && isset($this->attendanceData[$this->selectedStudentId])) {
            $this->attendanceData[$this->selectedStudentId]['reason'] = $this->absenceReason;
        }

        $this->showReasonModal = false;
        $this->selectedStudentId = null;
        $this->absenceReason = '';
    }

    public function saveAttendance()
    {
        $this->dispatch('show-loading');
        $this->validate();

        foreach ($this->attendanceData as $studentId => $data) {
            $attendance = Attendance::updateOrCreate(
                [
                    'class_id' => $this->classroom->id,
                    'student_id' => $studentId,
                    'date' => $this->selectedDate,
                ],
                [
                    'present' => $data['present'],
                    'reason' => $data['present'] ? null : $data['reason'],
                ]
            );
        }

        session()->flash('message', 'Đã lưu điểm danh thành công!');
        $this->loadAttendanceData();
    }

    public function getAttendanceStats()
    {
        $totalStudents = count($this->attendanceData);
        $presentCount = collect($this->attendanceData)->where('present', true)->count();
        $absentCount = $totalStudents - $presentCount;

        return [
            'total' => $totalStudents,
            'present' => $presentCount,
            'absent' => $absentCount,
            'presentPercentage' => $totalStudents > 0 ? round(($presentCount / $totalStudents) * 100, 1) : 0,
        ];
    }

    public function render()
    {
        $stats = $this->getAttendanceStats();

        return view('admin.attendance.take-attendance', [
            'stats' => $stats,
        ]);
    }
}
