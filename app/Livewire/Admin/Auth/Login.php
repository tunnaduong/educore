<?php

namespace App\Livewire\Admin\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Login extends Component
{
    public $phone, $password, $remember = false;

    protected $messages = [
        'phone.required'    => 'Vui lòng nhập số điện thoại',
        'phone.min'         => 'Tối thiểu :min ký tự',
        'phone.max'         => 'Tối đa :max ký tự',
        'password.required' => 'Vui lòng nhập mật khẩu',
        'password.min'      => 'Mật khẩu phải có ít nhất :min ký tự',
        'password.string'   => 'Mật khẩu phải là chuỗi ký tự',
    ];

    public function login()
    {
        $this->validate([
            'phone' => 'required|string|min:6|max:20',
            'password' => 'required|string|min:6',
        ]);

        // Kiểm tra user có tồn tại theo phone không
        $user = \App\Models\User::where('phone', $this->phone)->first();

        if (!$user) {
            $this->addError('phone', 'Số điện thoại không tồn tại.');
            return;
        }

        // Nếu có user rồi, kiểm tra mật khẩu
        if (!Hash::check($this->password, $user->password)) {
            $this->addError('password', 'Mật khẩu không đúng.');
            return;
        }

        // Đăng nhập nếu đúng
        Auth::login($user, $this->remember);
        session()->regenerate();
        return $this->redirect(route('dashboard'), navigate: true);
    }

    public function updated($propertyName)
    {
        $this->resetErrorBag($propertyName);
    }

    public function render()
    {
        return view('admin.auth.login');
    }
}
