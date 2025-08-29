<?php

namespace App\Livewire\Admin\Classrooms;

use App\Helpers\ScheduleConflictHelper;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Create extends Component
{
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

            // Kiểm tra trùng lịch giáo viên trước khi tạo lớp
            $tempClassroom = new Classroom([
                'schedule' => [
                    'days' => $this->days,
                    'time' => $this->startTime.' - '.$this->endTime,
                ],
            ]);

            $conflictCheck = ScheduleConflictHelper::checkMultipleTeachersScheduleConflict(
                $this->teacher_ids,
                $tempClassroom
            );

            if ($conflictCheck['hasConflict']) {
                $this->teacherConflicts = $conflictCheck['conflicts'];
                $this->showConflictModal = true;

                return;
            }

            // Nếu không có trùng lịch, tiến hành tạo lớp
            $this->performCreate();
        } catch (\Exception $e) {
            session()->flash('error', 'Không thể tạo lớp học. Vui lòng thử lại sau. Lỗi: '.$e->getMessage());
            Log::error('Create Classroom Error: '.$e->getMessage(), [
                'user_id' => Auth::id(),
                'data' => $this->only(['name', 'level', 'status']),
            ]);
        }
    }

    public function performCreate()
    {
        $classroom = Classroom::create([
            'name' => $this->name,
            'level' => $this->level,
            'schedule' => [
                'days' => $this->days,
                'time' => $this->startTime.' - '.$this->endTime,
            ],
            'notes' => $this->notes,
            'status' => $this->status,
        ]);

        // Gán giáo viên vào lớp
        foreach ($this->teacher_ids as $tid) {
            $classroom->users()->attach($tid, ['role' => 'teacher']);
        }

        session()->flash('success', 'Lớp học đã được tạo thành công!');

        return $this->redirect(route('classrooms.index'));
    }

    public function forceCreate()
    {
        // Tạo lớp bất chấp trùng lịch
        $this->performCreate();
    }

    public function closeConflictModal()
    {
        $this->showConflictModal = false;
        $this->teacherConflicts = [];
    }

    public function render()
    {
        $teachers = User::where('role', 'teacher')->get();

        return view('admin.classrooms.create', [
            'teachers' => $teachers,
        ]);
    }
}
