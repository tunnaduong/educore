<?php

namespace App\Livewire\Classrooms;

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
    public $teacher_id = '';
    public $status = 'active';

    protected $rules = [
        'name' => 'required|min:3|max:255',
        'level' => 'required|max:50',
        'days' => 'required|array|min:1',
        'time' => 'required|max:50',
        'notes' => 'nullable|max:1000',
        'teacher_id' => 'required|exists:users,id',
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
        'teacher_id.required' => 'Vui lòng chọn giảng viên.',
        'teacher_id.exists' => 'Giảng viên không tồn tại trong hệ thống.',
        'status.required' => 'Vui lòng chọn trạng thái lớp học.',
        'status.in' => 'Trạng thái lớp học không hợp lệ.',
    ];

    public function mount()
    {
        // Set current user as default teacher if they are a teacher
        if (Auth::user()->role === 'teacher') {
            $this->teacher_id = Auth::id();
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
            'teacher_id' => $this->teacher_id,
            'status' => $this->status,
        ]);

        // Add teacher to class_user table
        $classroom->users()->attach($this->teacher_id, ['role' => 'teacher']);

        session()->flash('success', 'Lớp học đã được tạo thành công.');
        return $this->redirect(route('classrooms.index'), navigate: true);
    }

    public function render()
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('livewire.classrooms.create', compact('teachers'));
    }
}
