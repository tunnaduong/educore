<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Create extends Component
{
    public $name = '';
    public $email = '';
    public $phone = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = '';
    public $is_active = true;

    protected function rules()
    {
        return [
            'name' => 'required|string|min:3',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->where(function ($query) {
                    return $query->whereNotNull('email');
                }),
            ],
            'phone' => 'required|string|min:10|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,teacher,student',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'name.required' => 'Vui lòng nhập họ tên',
        'name.min' => 'Họ tên phải có ít nhất :min ký tự',
        'email.email' => 'Email không hợp lệ',
        'email.unique' => 'Email đã tồn tại trong hệ thống',
        'phone.required' => 'Vui lòng nhập số điện thoại',
        'phone.min' => 'Số điện thoại phải có ít nhất :min số',
        'phone.unique' => 'Số điện thoại đã tồn tại trong hệ thống',
        'password.required' => 'Vui lòng nhập mật khẩu',
        'password.min' => 'Mật khẩu phải có ít nhất :min ký tự',
        'password.confirmed' => 'Xác nhận mật khẩu không khớp',
        'role.required' => 'Vui lòng chọn vai trò',
        'role.in' => 'Vai trò không hợp lệ',
    ];

    public function save()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email ?: null,
            'phone' => $this->phone,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'is_active' => (bool) $this->is_active,
        ]);

        session()->flash('success', 'Tạo người dùng thành công!');
        return $this->redirect(route('users.index'), navigate: true);
    }

    public function render()
    {
        return view('admin.users.create');
    }
}
