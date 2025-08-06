<?php

namespace App\Livewire\Admin\Classrooms;

use App\Models\Classroom;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

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

    protected $rules = [
        'name' => 'required|min:3|max:255',
        'level' => 'required|max:50',
        'days' => 'required|array|min:1',
        'startTime' => 'required|date_format:H:i',
        'endTime' => 'required|date_format:H:i|after:startTime',
        'notes' => 'nullable|max:1000',
        'teacher_ids' => 'required|array|min:1',
        'teacher_ids.*' => 'exists:users,id',
        'status' => 'required|in:active,completed',
    ];

    protected $messages = [
        'name.required' => 'Vui lòng nhập tên lớp học.',
        'name.min' => 'Tên lớp học phải có ít nhất 3 ký tự.',
        'name.max' => 'Tên lớp học không được vượt quá 255 ký tự.',
        'level.required' => 'Vui lòng chọn trình độ.',
        'level.max' => 'Trình độ không được vượt quá 50 ký tự.',
        'days.required' => 'Vui lòng chọn ít nhất một ngày học.',
        'days.min' => 'Vui lòng chọn ít nhất một ngày học.',
        'startTime.required' => 'Vui lòng nhập giờ bắt đầu.',
        'startTime.date_format' => 'Giờ bắt đầu không đúng định dạng.',
        'endTime.required' => 'Vui lòng nhập giờ kết thúc.',
        'endTime.date_format' => 'Giờ kết thúc không đúng định dạng.',
        'endTime.after' => 'Giờ kết thúc phải sau giờ bắt đầu.',
        'notes.max' => 'Ghi chú không được vượt quá 1000 ký tự.',
        'teacher_ids.required' => 'Vui lòng chọn ít nhất một giảng viên.',
        'teacher_ids.min' => 'Vui lòng chọn ít nhất một giảng viên.',
        'teacher_ids.*.exists' => 'Giảng viên không tồn tại trong hệ thống.',
        'status.required' => 'Vui lòng chọn trạng thái lớp học.',
        'status.in' => 'Trạng thái lớp học không hợp lệ.',
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

        // Set schedule data
        $schedule = $classroom->schedule;
        $this->days = $schedule['days'] ?? [];
        
        // Parse time string to startTime and endTime
        if (isset($schedule['time'])) {
            $timeParts = explode(' - ', $schedule['time']);
            if (count($timeParts) === 2) {
                $this->startTime = $timeParts[0];
                $this->endTime = $timeParts[1];
            }
        }
    }

    public function save()
    {
        $this->validate();

        $this->classroom->update([
            'name' => $this->name,
            'level' => $this->level,
            'schedule' => [
                'days' => $this->days,
                'time' => $this->startTime . ' - ' . $this->endTime,
            ],
            'notes' => $this->notes,
            'status' => $this->status,
        ]);

        // Cập nhật lại giáo viên trong class_user
        $this->classroom->users()->wherePivot('role', 'teacher')->detach();
        foreach ($this->teacher_ids as $tid) {
            $this->classroom->users()->attach($tid, ['role' => 'teacher']);
        }

        session()->flash('success', 'Lớp học đã được cập nhật thành công.');
        return $this->redirect(route('classrooms.index'), navigate: true);
    }

    public function render()
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.classrooms.edit', [
            'teachers' => $teachers,
            'teacher_ids' => $this->teacher_ids,
        ]);
    }
}
