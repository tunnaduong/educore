<?php

namespace App\Livewire\Admin\Students;

use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public User $student;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $date_of_birth = '';
    public $joined_at = '';
    public $status = 'active';
    public $level = '';
    public $notes = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $this->student->id,
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'joined_at' => 'nullable|date',
            'status' => 'required|in:active,paused,dropped',
            'level' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:500',
        ];
    }

    protected function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập họ tên học viên.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã được sử dụng.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'date_of_birth.before' => 'Ngày sinh phải trước ngày hiện tại.',
            'joined_at.date' => 'Ngày vào học không đúng định dạng.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ];
    }

    public function mount($student)
    {
        $this->student = $student;
        $this->name = $student->name;
        $this->email = $student->email;
        $this->phone = $student->phone;

        if ($student->studentProfile) {
            $this->date_of_birth = $student->studentProfile->date_of_birth;
            $this->joined_at = $student->studentProfile->joined_at;
            $this->status = $student->studentProfile->status;
            $this->level = $student->studentProfile->level;
            $this->notes = $student->studentProfile->notes;
        }
    }

    public function save()
    {
        $this->validate();

        // Cập nhật user
        $this->student->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        // Cập nhật hoặc tạo student profile
        if ($this->student->studentProfile) {
            $this->student->studentProfile->update([
                'date_of_birth' => $this->date_of_birth ?: null,
                'joined_at' => $this->joined_at ?: null,
                'status' => $this->status,
                'level' => $this->level,
                'notes' => $this->notes,
            ]);
        } else {
            $this->student->studentProfile()->create([
                'date_of_birth' => $this->date_of_birth ?: null,
                'joined_at' => $this->joined_at ?: null,
                'status' => $this->status,
                'level' => $this->level,
                'notes' => $this->notes,
            ]);
        }

        session()->flash('message', 'Đã cập nhật thông tin học viên thành công!');
        return $this->redirect(route('students.index'));
    }

    public function render()
    {
        return view('admin.students.edit');
    }
}
