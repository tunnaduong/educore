<?php

namespace App\Livewire\Admin\Classrooms;

use App\Helpers\ScheduleConflictHelper;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Edit extends Component
{
    public Classroom $classroom;

    public $name = '';

    public $level = '';

    public $days = [];

    public $startTime = '';

    public $endTime = '';

    public $notes = '';

    public $teacher_ids = [];

    public $status = 'active';

    public $showConflictModal = false;

    public $teacherConflicts = [];

    public $realTimeValidation = false;

    protected $rules = [
        'name' => 'required|min:3|max:255',
        'level' => 'required|max:50',
        'days' => 'required|array|min:1',
        'startTime' => 'required|date_format:H:i',
        'endTime' => 'required|date_format:H:i|after:startTime',
        'notes' => 'nullable|max:1000',
        'teacher_ids' => 'required|array|min:1',
        'teacher_ids.*' => 'exists:users,id',
        'status' => 'required|in:draft,active,inactive,completed',
    ];

    protected $messages = [
        'name.required' => 'Tên lớp học là bắt buộc.',
        'name.min' => 'Tên lớp học phải có ít nhất 3 ký tự.',
        'name.max' => 'Tên lớp học không được vượt quá 255 ký tự.',
        'level.required' => 'Cấp độ là bắt buộc.',
        'level.max' => 'Cấp độ không được vượt quá 50 ký tự.',
        'days.required' => 'Vui lòng chọn ít nhất một ngày trong tuần.',
        'days.min' => 'Vui lòng chọn ít nhất một ngày trong tuần.',
        'startTime.required' => 'Giờ bắt đầu là bắt buộc.',
        'startTime.date_format' => 'Giờ bắt đầu không đúng định dạng.',
        'endTime.required' => 'Giờ kết thúc là bắt buộc.',
        'endTime.date_format' => 'Giờ kết thúc không đúng định dạng.',
        'endTime.after' => 'Giờ kết thúc phải sau giờ bắt đầu.',
        'notes.max' => 'Mô tả không được vượt quá 1000 ký tự.',
        'teacher_ids.required' => 'Vui lòng chọn ít nhất một giáo viên.',
        'teacher_ids.min' => 'Vui lòng chọn ít nhất một giáo viên.',
        'teacher_ids.*.exists' => 'Giáo viên không tồn tại.',
        'status.required' => 'Trạng thái là bắt buộc.',
        'status.in' => 'Trạng thái không hợp lệ.',
    ];

    public function mount(Classroom $classroom)
    {
        $this->classroom = $classroom;
        $this->name = $classroom->name;
        $this->level = $classroom->level;
        $this->notes = $classroom->notes;
        $this->status = $classroom->status;

        // Lấy danh sách giáo viên hiện tại của lớp
        $this->teacher_ids = $classroom->teachers()->pluck('users.id')->toArray();

        // Set schedule data - xử lý cả trường hợp JSON string và array
        $schedule = $classroom->schedule;

        // Nếu schedule là JSON string, decode thành array
        if (is_string($schedule)) {
            $schedule = json_decode($schedule, true);
        }

        // Đảm bảo schedule là array
        if (! is_array($schedule)) {
            $schedule = [];
        }

        $this->days = $schedule['days'] ?? [];

        // Parse time string to startTime and endTime
        if (isset($schedule['time'])) {
            $timeParts = explode(' - ', $schedule['time']);
            if (count($timeParts) === 2) {
                $this->startTime = trim($timeParts[0]);
                $this->endTime = trim($timeParts[1]);
            }
        }

        // Debug: Log để kiểm tra
        Log::info('Edit Classroom Mount', [
            'classroom_id' => $classroom->id,
            'schedule_raw' => $classroom->schedule,
            'schedule_parsed' => $schedule,
            'days' => $this->days,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
        ]);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        // Kiểm tra trùng lịch real-time khi thay đổi lịch học hoặc giáo viên
        if (
            in_array($propertyName, ['days', 'startTime', 'endTime', 'teacher_ids']) &&
            ! empty($this->days) && ! empty($this->startTime) && ! empty($this->endTime) && ! empty($this->teacher_ids)
        ) {
            $this->checkRealTimeConflicts();
        }
    }

    public function checkRealTimeConflicts()
    {
        if (empty($this->days) || empty($this->startTime) || empty($this->endTime) || empty($this->teacher_ids)) {
            return;
        }

        $tempClassroom = new Classroom([
            'schedule' => [
                'days' => $this->days,
                'time' => $this->startTime.' - '.$this->endTime,
            ],
        ]);
        // Gán id lớp hiện tại để helper loại trừ chính lớp này khi kiểm tra trùng lịch
        $tempClassroom->id = $this->classroom->id;

        $conflictCheck = ScheduleConflictHelper::checkMultipleTeachersScheduleConflict(
            $this->teacher_ids,
            $tempClassroom
        );

        if ($conflictCheck['hasConflict']) {
            $this->teacherConflicts = $conflictCheck['conflicts'];
            $this->realTimeValidation = true;
        } else {
            $this->teacherConflicts = [];
            $this->realTimeValidation = false;
        }
    }

    public function save()
    {
        try {
            $this->validate();

            // Kiểm tra trùng lịch giáo viên trước khi cập nhật lớp
            $tempClassroom = new Classroom([
                'schedule' => [
                    'days' => $this->days,
                    'time' => $this->startTime.' - '.$this->endTime,
                ],
            ]);
            // Gán id lớp hiện tại để helper loại trừ chính lớp này khi kiểm tra trùng lịch
            $tempClassroom->id = $this->classroom->id;

            $conflictCheck = ScheduleConflictHelper::checkMultipleTeachersScheduleConflict(
                $this->teacher_ids,
                $tempClassroom
            );

            if ($conflictCheck['hasConflict']) {
                $this->teacherConflicts = $conflictCheck['conflicts'];
                $this->showConflictModal = true;

                return;
            }

            // Nếu không có trùng lịch, tiến hành cập nhật lớp
            $this->performUpdate();
        } catch (\Exception $e) {
            session()->flash('error', 'Không thể cập nhật lớp học. Vui lòng thử lại sau. Lỗi: '.$e->getMessage());
            Log::error('Edit Classroom Error: '.$e->getMessage(), [
                'classroom_id' => $this->classroom->id ?? null,
                'user_id' => Auth::id(),
                'data' => $this->only(['name', 'level', 'status']),
            ]);
        }
    }

    public function performUpdate()
    {
        // Đảm bảo dữ liệu schedule được format đúng
        $scheduleData = [
            'days' => $this->days,
            'time' => $this->startTime.' - '.$this->endTime,
        ];

        $this->classroom->update([
            'name' => $this->name,
            'level' => $this->level,
            'schedule' => $scheduleData,
            'notes' => $this->notes,
            'status' => $this->status,
        ]);

        // Cập nhật lại giáo viên trong class_user
        $this->classroom->users()->wherePivot('role', 'teacher')->detach();
        foreach ($this->teacher_ids as $tid) {
            $this->classroom->users()->attach($tid, ['role' => 'teacher']);
        }

        // Debug: Log để kiểm tra
        Log::info('Edit Classroom Save', [
            'classroom_id' => $this->classroom->id,
            'schedule_data' => $scheduleData,
            'days' => $this->days,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
        ]);

        session()->flash('success', 'Lớp học đã được cập nhật thành công!');

        return $this->redirect(route('classrooms.index'));
    }



    public function closeConflictModal()
    {
        $this->showConflictModal = false;
        $this->teacherConflicts = [];
    }

    public function render()
    {
        $teachers = User::where('role', 'teacher')->get();

        return view('admin.classrooms.edit', [
            'teachers' => $teachers,
        ]);
    }
}
