<?php

namespace App\Livewire\Admin\Classrooms;

use App\Models\Classroom;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    public $name = '';
    public $level = '';
    public $days = [];
    public $time = '';
    public $notes = '';
    public $teacher_ids = [];
    public $status = 'active';

    protected $rules = [
        'name' => 'required|min:3|max:255',
        'level' => 'required|max:50',
        'days' => 'required|array|min:1',
        'time' => 'required|max:50',
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
        'time.required' => 'Vui lòng nhập giờ học.',
        'time.max' => 'Giờ học không được vượt quá 50 ký tự.',
        'notes.max' => 'Ghi chú không được vượt quá 1000 ký tự.',
        'teacher_ids.required' => 'Vui lòng chọn ít nhất một giảng viên.',
        'teacher_ids.min' => 'Vui lòng chọn ít nhất một giảng viên.',
        'teacher_ids.*.exists' => 'Giảng viên không tồn tại trong hệ thống.',
        'status.required' => 'Vui lòng chọn trạng thái lớp học.',
        'status.in' => 'Trạng thái lớp học không hợp lệ.',
    ];

    public function mount()
    {
        if (Auth::user()->role === 'teacher') {
            $this->teacher_ids = [Auth::id()];
        }
    }

    public function save()
    {
        $this->validate();

        $classroom = Classroom::create([
            'name' => $this->name,
            'level' => $this->level,
            'schedule' => json_encode([
                'days' => $this->days,
                'time' => $this->time,
            ]),
            'notes' => $this->notes,
            'status' => $this->status,
        ]);

        // Gán nhiều giáo viên vào class_user
        foreach ($this->teacher_ids as $tid) {
            $classroom->users()->attach($tid, ['role' => 'teacher']);
        }

        session()->flash('success', 'Lớp học đã được tạo thành công.');
        return $this->redirect(route('classrooms.index'), navigate: true);
    }

    public function render()
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.classrooms.create', [
            'teachers' => $teachers,
            'teacher_ids' => $this->teacher_ids,
        ]);
    }
}
