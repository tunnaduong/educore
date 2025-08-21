<?php

namespace App\Livewire\Admin\Students;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Create extends Component
{
    public $name = '';

    public $email = '';

    public $phone = '';

    public $password = '';

    public $date_of_birth = '';

    public $joined_at = '';

    public $status = 'active';

    public $level = '';

    public $notes = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => ['required', 'min:6'],
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
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'date_of_birth.before' => 'Ngày sinh phải trước ngày hiện tại.',
            'joined_at.date' => 'Ngày vào học không đúng định dạng.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự',
        ];
    }

    public function save()
    {
        $this->validate();

        // Tạo user
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => Hash::make($this->password),
            'role' => 'student',
            'is_active' => true,
        ]);

        // Tạo student profile
        $user->studentProfile()->create([
            'date_of_birth' => $this->date_of_birth ?: null,
            'joined_at' => $this->joined_at ?: null,
            'status' => $this->status,
            'level' => $this->level,
            'notes' => $this->notes,
        ]);

        session()->flash('message', 'Đã tạo học viên thành công!');

        return $this->redirect(route('students.index'));
    }

    public function render()
    {
        return view('admin.students.create');
    }
}
