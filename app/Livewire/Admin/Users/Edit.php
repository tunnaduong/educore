<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Edit extends Component
{
    public $user;

    public $name = '';

    public $email = '';

    public $phone = '';

    public $role = '';

    public $is_active = true;

    public $password = '';

    public $password_confirmation = '';

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->role = $user->role;
        $this->is_active = (bool) $user->is_active;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|min:3',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')
                    ->where(function ($query) {
                        return $query->whereNotNull('email');
                    })
                    ->ignore($this->user->id),
            ],
            'phone' => [
                'required',
                'regex:/^\d{10,15}$/',
                Rule::unique('users', 'phone')->ignore($this->user->id),
            ],
            'role' => 'required|in:admin,teacher,student',
            'is_active' => 'boolean',
            'password' => 'nullable|min:6|confirmed',
        ];
    }

    protected $messages = [
        'name.required' => 'Vui lòng nhập họ tên',
        'name.min' => 'Họ tên phải có ít nhất :min ký tự',
        'email.email' => 'Email không hợp lệ',
        'email.unique' => 'Email đã tồn tại trong hệ thống',
        'phone.required' => 'Vui lòng nhập số điện thoại',
        'phone.regex' => 'Số điện thoại chỉ gồm số và có 10-15 chữ số',
        'phone.unique' => 'Số điện thoại đã tồn tại trong hệ thống',
        'role.required' => 'Vui lòng chọn vai trò',
        'role.in' => 'Vai trò không hợp lệ',
        'password.min' => 'Mật khẩu phải có ít nhất :min ký tự',
        'password.confirmed' => 'Xác nhận mật khẩu không khớp',
    ];

    public function updatedPhone()
    {
        $this->validateOnly('phone');
    }

    public function update()
    {
        $this->validate();

        $this->user->name = $this->name;
        $this->user->email = $this->email ?: null; // Convert empty string to null
        $this->user->phone = $this->phone;
        $this->user->role = $this->role;
        $this->user->is_active = (bool) $this->is_active;

        if ($this->password) {
            $this->user->password = Hash::make($this->password);
        }

        $this->user->save();

        session()->flash('success', 'Cập nhật người dùng thành công!');

        return $this->redirect(route('users.index'));
    }

    public function render()
    {
        return view('admin.users.edit');
    }
}
