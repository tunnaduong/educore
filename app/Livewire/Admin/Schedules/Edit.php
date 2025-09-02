<?php

namespace App\Livewire\Admin\Schedules;

use App\Models\Classroom;
use App\Helpers\ScheduleConflictHelper;
use Livewire\Component;

class Edit extends Component
{
    public Classroom $classroom;

    public $selectedDays = [];

    public $startTime = '';

    public $endTime = '';

    public $notes = '';

    public $showConflictModal = false;

    public $teacherConflicts = [];

    public $realTimeValidation = false;

    protected $rules = [
        'selectedDays' => 'required|array|min:1',
        'startTime' => 'required|date_format:H:i',
        'endTime' => 'required|date_format:H:i|after:startTime',
        'notes' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'selectedDays.required' => 'Vui lòng chọn ít nhất một ngày trong tuần.',
        'selectedDays.min' => 'Vui lòng chọn ít nhất một ngày trong tuần.',
        'startTime.required' => 'Vui lòng nhập giờ bắt đầu.',
        'startTime.date_format' => 'Giờ bắt đầu không đúng định dạng.',
        'endTime.required' => 'Vui lòng nhập giờ kết thúc.',
        'endTime.date_format' => 'Giờ kết thúc không đúng định dạng.',
        'endTime.after' => 'Giờ kết thúc phải sau giờ bắt đầu.',
        'notes.max' => 'Ghi chú không được vượt quá 500 ký tự.',
    ];

    public function mount(Classroom $classroom)
    {
        $this->classroom = $classroom;

        // Load existing schedule data
        if ($this->classroom->schedule) {
            $this->selectedDays = $this->classroom->schedule['days'] ?? [];

            if (isset($this->classroom->schedule['time'])) {
                $timeParts = explode(' - ', $this->classroom->schedule['time']);
                if (count($timeParts) === 2) {
                    $this->startTime = $timeParts[0];
                    $this->endTime = $timeParts[1];
                }
            }
        }

        $this->notes = $this->classroom->notes ?? '';
    }

    public function save()
    {
        $this->validate();

        // Kiểm tra trùng lịch giáo viên trước khi lưu
        $tempClassroom = new Classroom([
            'schedule' => [
                'days' => $this->selectedDays,
                'time' => $this->startTime . ' - ' . $this->endTime,
            ],
        ]);
        // Gán id lớp hiện tại để helper loại trừ chính lớp này
        $tempClassroom->id = $this->classroom->id;

        $teacherIds = $this->classroom->teachers()->pluck('users.id')->toArray();

        if (! empty($teacherIds)) {
            $conflictCheck = ScheduleConflictHelper::checkMultipleTeachersScheduleConflict(
                $teacherIds,
                $tempClassroom
            );

            if ($conflictCheck['hasConflict']) {
                $this->teacherConflicts = $conflictCheck['conflicts'];
                $this->showConflictModal = true;
                return; // Dừng lưu, hiển thị modal
            }
        }

        $schedule = [
            'days' => $this->selectedDays,
            'time' => $this->startTime . ' - ' . $this->endTime,
        ];

        $this->classroom->update([
            'schedule' => $schedule,
            'notes' => $this->notes,
        ]);

        session()->flash('success_message', 'Lịch học đã được cập nhật thành công!');

        return redirect()->route('schedules.index');
    }

    public function render()
    {
        $availableDays = [
            'Monday' => 'Thứ 2',
            'Tuesday' => 'Thứ 3',
            'Wednesday' => 'Thứ 4',
            'Thursday' => 'Thứ 5',
            'Friday' => 'Thứ 6',
            'Saturday' => 'Thứ 7',
            'Sunday' => 'Chủ nhật',
        ];

        return view('admin.schedules.edit', [
            'availableDays' => $availableDays,
        ]);
    }

    public function updated($propertyName)
    {
        // Validate field thay đổi
        $this->validateOnly($propertyName);

        // Chạy kiểm tra real-time khi thay đổi ngày/giờ
        if (in_array($propertyName, ['selectedDays', 'startTime', 'endTime'])) {
            $this->checkRealTimeConflicts();
        }
    }

    public function checkRealTimeConflicts()
    {
        if (empty($this->selectedDays) || empty($this->startTime) || empty($this->endTime)) {
            $this->teacherConflicts = [];
            $this->realTimeValidation = false;
            return;
        }

        $teacherIds = $this->classroom->teachers()->pluck('users.id')->toArray();
        if (empty($teacherIds)) {
            $this->teacherConflicts = [];
            $this->realTimeValidation = false;
            return;
        }

        $tempClassroom = new Classroom([
            'schedule' => [
                'days' => $this->selectedDays,
                'time' => $this->startTime . ' - ' . $this->endTime,
            ],
        ]);
        $tempClassroom->id = $this->classroom->id;

        $conflictCheck = ScheduleConflictHelper::checkMultipleTeachersScheduleConflict(
            $teacherIds,
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

    public function closeConflictModal()
    {
        $this->showConflictModal = false;
        $this->teacherConflicts = [];
    }
}
