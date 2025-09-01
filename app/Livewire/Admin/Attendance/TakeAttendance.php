<?php

namespace App\Livewire\Admin\Attendance;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Student;
use Livewire\Component;

class TakeAttendance extends Component
{
    public Classroom $classroom;

    public $selectedDate;

    public $attendanceData = [];

    public $showReasonModal = false;

    public $selectedStudentId;

    public $absenceReason = '';

    public $canTakeAttendance = true;

    public $attendanceMessage = '';

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
        
        \Log::info('TakeAttendance component mounted', [
            'classroom_id' => $classroom->id,
            'classroom_name' => $classroom->name,
            'selected_date' => $this->selectedDate,
        ]);
        
        // Kiểm tra dữ liệu attendance hiện tại trước khi load
        $currentAttendance = Attendance::forClass($classroom->id)
            ->forDate($this->selectedDate)
            ->get();
        
        \Log::info('Current attendance before load', [
            'classroom_id' => $classroom->id,
            'selected_date' => $this->selectedDate,
            'total_current_attendance' => $currentAttendance->count(),
            'current_attendance_data' => $currentAttendance->toArray(),
        ]);
        
        $this->loadAttendanceData();
        $this->checkAttendancePermission();
    }

    public function checkAttendancePermission()
    {
        $result = Attendance::canTakeAttendance($this->classroom, $this->selectedDate);
        $this->canTakeAttendance = $result['can'];
        $this->attendanceMessage = $result['message'];
    }

    public function debugAttendanceData()
    {
        // Kiểm tra dữ liệu trong database
        $allAttendance = Attendance::forClass($this->classroom->id)
            ->forDate($this->selectedDate)
            ->with('student.user')
            ->get();
        
        \Log::info('Debug attendance data', [
            'classroom_id' => $this->classroom->id,
            'selected_date' => $this->selectedDate,
            'total_records' => $allAttendance->count(),
            'attendance_records' => $allAttendance->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'student_id' => $attendance->student_id,
                    'student_name' => $attendance->student->user->name ?? 'Unknown',
                    'present' => $attendance->present,
                    'reason' => $attendance->reason,
                    'date' => $attendance->date,
                ];
            })->toArray(),
        ]);
        
        // Kiểm tra thêm bằng cách query trực tiếp với join
        $directQueryWithJoin = \DB::table('attendances')
            ->join('students', 'attendances.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('attendances.class_id', $this->classroom->id)
            ->where('attendances.date', $this->selectedDate)
            ->select('attendances.*', 'users.name as student_name')
            ->get();
        
        \Log::info('Direct query with join results', [
            'total_direct_query_with_join' => $directQueryWithJoin->count(),
            'direct_query_with_join_data' => $directQueryWithJoin->toArray(),
        ]);
        
        return $allAttendance;
    }

    public function loadAttendanceData()
    {
        // Log để debug query
        \Log::info('Loading attendance data', [
            'classroom_id' => $this->classroom->id,
            'selected_date' => $this->selectedDate,
        ]);

        // Lấy danh sách học viên trong lớp
        $students = $this->classroom->students()->orderBy('name')->get();

        // Lấy dữ liệu điểm danh đã có cho ngày này
        $existingAttendance = Attendance::forClass($this->classroom->id)
            ->forDate($this->selectedDate)
            ->get()
            ->keyBy('student_id');

        // Log để debug query results
        \Log::info('Existing attendance query results', [
            'total_existing_attendance' => $existingAttendance->count(),
            'existing_attendance_data' => $existingAttendance->toArray(),
        ]);

        // Debug dữ liệu trong database
        $this->debugAttendanceData();

        // Kiểm tra thêm bằng cách query trực tiếp
        $directQuery = \DB::table('attendances')
            ->where('class_id', $this->classroom->id)
            ->where('date', $this->selectedDate)
            ->get();
        
        \Log::info('Direct query results', [
            'total_direct_query' => $directQuery->count(),
            'direct_query_data' => $directQuery->toArray(),
        ]);

        // Kiểm tra thêm bằng cách query với join để lấy thông tin student
        $directQueryWithStudentInfo = \DB::table('attendances')
            ->join('students', 'attendances.student_id', '=', 'students.id')
            ->join('users', 'students.user_id', '=', 'users.id')
            ->where('attendances.class_id', $this->classroom->id)
            ->where('attendances.date', $this->selectedDate)
            ->select('attendances.*', 'users.name as student_name', 'users.email as student_email')
            ->get();
        
        \Log::info('Direct query with student info', [
            'total_direct_query_with_student_info' => $directQueryWithStudentInfo->count(),
            'direct_query_with_student_info_data' => $directQueryWithStudentInfo->toArray(),
        ]);

        $this->attendanceData = [];

        foreach ($students as $student) {
            // Lấy student record từ bảng students
            $studentRecord = Student::where('user_id', $student->id)->first();

            if ($studentRecord) {
                $existing = $existingAttendance->get($studentRecord->id);
                
                // Log để debug student mapping
                \Log::info('Student mapping debug', [
                    'user_id' => $student->id,
                    'student_record_id' => $studentRecord->id,
                    'student_name' => $student->name,
                    'existing_attendance_found' => $existing ? true : false,
                    'existing_attendance_data' => $existing ? $existing->toArray() : null,
                ]);
                
                // Nếu có dữ liệu điểm danh hiện tại, sử dụng dữ liệu đó
                // Nếu không có, mặc định là có mặt (true) nhưng không lưu vào database
                $this->attendanceData[$studentRecord->id] = [
                    'student' => $student,
                    'student_record' => $studentRecord,
                    'present' => $existing ? (bool) $existing->present : true,
                    'reason' => $existing ? $existing->reason : '',
                    'hasExisting' => $existing ? true : false,
                ];
                
                // Log để debug
                \Log::info('Attendance data loaded', [
                    'student_id' => $studentRecord->id,
                    'student_name' => $student->name,
                    'present' => $this->attendanceData[$studentRecord->id]['present'],
                    'reason' => $this->attendanceData[$studentRecord->id]['reason'],
                    'hasExisting' => $this->attendanceData[$studentRecord->id]['hasExisting'],
                    'existing_attendance' => $existing ? $existing->toArray() : null,
                ]);
                
                // Kiểm tra thêm bằng cách query trực tiếp cho student này
                $directStudentQuery = \DB::table('attendances')
                    ->where('class_id', $this->classroom->id)
                    ->where('student_id', $studentRecord->id)
                    ->where('date', $this->selectedDate)
                    ->first();
                
                \Log::info('Direct student query', [
                    'student_id' => $studentRecord->id,
                    'student_name' => $student->name,
                    'direct_query_result' => $directStudentQuery ? $directStudentQuery : null,
                    'present_from_direct_query' => $directStudentQuery ? (bool) $directStudentQuery->present : null,
                    'reason_from_direct_query' => $directStudentQuery ? $directStudentQuery->reason : null,
                ]);
                
                // Nếu có sự khác biệt giữa Eloquent query và direct query, sử dụng direct query
                if ($directStudentQuery && (!$existing || $existing->present != $directStudentQuery->present || $existing->reason != $directStudentQuery->reason)) {
                    \Log::warning('Data mismatch detected', [
                        'student_id' => $studentRecord->id,
                        'student_name' => $student->name,
                        'eloquent_present' => $existing ? $existing->present : null,
                        'eloquent_reason' => $existing ? $existing->reason : null,
                        'direct_present' => $directStudentQuery->present,
                        'direct_reason' => $directStudentQuery->reason,
                    ]);
                    
                    // Sử dụng dữ liệu từ direct query
                    $this->attendanceData[$studentRecord->id] = [
                        'student' => $student,
                        'student_record' => $studentRecord,
                        'present' => (bool) $directStudentQuery->present,
                        'reason' => $directStudentQuery->reason,
                        'hasExisting' => true,
                    ];
                } else {
                    // Sử dụng dữ liệu từ Eloquent query
                    $this->attendanceData[$studentRecord->id] = [
                        'student' => $student,
                        'student_record' => $studentRecord,
                        'present' => $existing ? (bool) $existing->present : true,
                        'reason' => $existing ? $existing->reason : '',
                        'hasExisting' => $existing ? true : false,
                    ];
                }
            }
        }
    }

    public function updatedSelectedDate()
    {
        $this->loadAttendanceData();
        $this->checkAttendancePermission();
    }

    public function toggleAttendance($studentId)
    {
        if (! $this->canTakeAttendance) {
            session()->flash('error', $this->attendanceMessage);

            return;
        }

        if (isset($this->attendanceData[$studentId])) {
            $oldPresent = $this->attendanceData[$studentId]['present'];
            $oldReason = $this->attendanceData[$studentId]['reason'];
            
            $this->attendanceData[$studentId]['present'] = ! $oldPresent;

            // Nếu chuyển từ vắng sang có mặt, xóa lý do nghỉ
            if ($this->attendanceData[$studentId]['present']) {
                $this->attendanceData[$studentId]['reason'] = '';
            }
            
            // Cập nhật trạng thái hasExisting nếu đã thay đổi
            $this->attendanceData[$studentId]['hasExisting'] = true;
            
            // Log để debug
            \Log::info('Attendance toggled', [
                'student_id' => $studentId,
                'student_name' => $this->attendanceData[$studentId]['student']->name,
                'old_present' => $oldPresent,
                'new_present' => $this->attendanceData[$studentId]['present'],
                'old_reason' => $oldReason,
                'new_reason' => $this->attendanceData[$studentId]['reason'],
            ]);
        }
        $this->dispatch('hide-loading');
    }

    public function openReasonModal($studentId)
    {
        if (! $this->canTakeAttendance) {
            session()->flash('error', $this->attendanceMessage);

            return;
        }

        // Chỉ cho phép mở modal cho học sinh vắng
        if (isset($this->attendanceData[$studentId]) && !$this->attendanceData[$studentId]['present']) {
            $this->selectedStudentId = $studentId;
            $this->absenceReason = $this->attendanceData[$studentId]['reason'] ?? '';
            $this->showReasonModal = true;
        }
    }

    public function saveReason()
    {
        $this->validate([
            'absenceReason' => 'nullable|string|max:255',
        ]);

        if ($this->selectedStudentId && isset($this->attendanceData[$this->selectedStudentId])) {
            $this->attendanceData[$this->selectedStudentId]['reason'] = $this->absenceReason;
            
            // Đảm bảo rằng khi có lý do nghỉ, trạng thái phải là vắng
            if (!empty($this->absenceReason)) {
                $this->attendanceData[$this->selectedStudentId]['present'] = false;
            }
            
            // Cập nhật trạng thái hasExisting
            $this->attendanceData[$this->selectedStudentId]['hasExisting'] = true;
            
            // Log để debug
            \Log::info('Reason saved', [
                'student_id' => $this->selectedStudentId,
                'reason' => $this->absenceReason,
                'present' => $this->attendanceData[$this->selectedStudentId]['present'],
            ]);
        }

        $this->showReasonModal = false;
        $this->selectedStudentId = null;
        $this->absenceReason = '';
    }

    public function saveAttendance()
    {
        if (! $this->canTakeAttendance) {
            session()->flash('error', $this->attendanceMessage);

            return;
        }

        $this->dispatch('show-loading');
        $this->validate();

        try {
            // Log dữ liệu trước khi lưu
            foreach ($this->attendanceData as $studentId => $data) {
                \Log::info('Before saving attendance', [
                    'student_id' => $studentId,
                    'student_name' => $data['student']->name,
                    'present' => $data['present'],
                    'reason' => $data['reason'],
                    'hasExisting' => $data['hasExisting'],
                ]);
            }
            
            // Sử dụng transaction để đảm bảo tính nhất quán
            \DB::transaction(function () {
                foreach ($this->attendanceData as $studentId => $data) {
                    $attendance = Attendance::updateOrCreate(
                        [
                            'class_id' => $this->classroom->id,
                            'student_id' => $studentId,
                            'date' => $this->selectedDate,
                        ],
                        [
                            'present' => (bool) $data['present'],
                            'reason' => $data['present'] ? null : $data['reason'],
                        ]
                    );
                    
                    // Log để debug
                    \Log::info('Attendance saved', [
                        'student_id' => $studentId,
                        'student_name' => $data['student']->name,
                        'present' => $data['present'],
                        'reason' => $data['reason'],
                        'attendance_id' => $attendance->id,
                        'saved_present' => $attendance->present,
                        'saved_reason' => $attendance->reason,
                        'was_created' => $attendance->wasRecentlyCreated,
                        'was_updated' => !$attendance->wasRecentlyCreated,
                    ]);
                }
            });

            session()->flash('message', __('general.attendance_saved_successfully'));
            
            // Kiểm tra xem dữ liệu có được lưu đúng không bằng cách query lại
            $savedAttendance = Attendance::forClass($this->classroom->id)
                ->forDate($this->selectedDate)
                ->get()
                ->keyBy('student_id');
            
            \Log::info('Attendance data after save', [
                'total_saved_attendance' => $savedAttendance->count(),
                'saved_attendance_data' => $savedAttendance->toArray(),
            ]);
            
            // Không reload dữ liệu, chỉ cập nhật trạng thái hasExisting
            // để tránh việc reset trạng thái hiện tại
            foreach ($this->attendanceData as $studentId => $data) {
                $this->attendanceData[$studentId]['hasExisting'] = true;
            }
            
            // Log để debug sau khi reload
            foreach ($this->attendanceData as $studentId => $data) {
                \Log::info('Attendance loaded after save', [
                    'student_id' => $studentId,
                    'student_name' => $data['student']->name,
                    'present' => $data['present'],
                    'reason' => $data['reason'],
                    'hasExisting' => $data['hasExisting'],
                ]);
            }
            
        } catch (\Exception $e) {
            session()->flash('error', __('general.attendance_save_error') . ': ' . $e->getMessage());
            \Log::error('Attendance save error: ' . $e->getMessage(), [
                'classroom_id' => $this->classroom->id,
                'date' => $this->selectedDate,
                'user_id' => auth()->id(),
            ]);
        }
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
