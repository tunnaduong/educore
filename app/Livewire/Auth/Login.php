<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Login extends Component
{
    public $phone, $password;

    protected $messages = [
        'phone.required'    => 'Vui lòng nhập số điện thoại',
        'phone.digits_between' => 'Số điện thoại phải có từ :min đến :max số',
        'phone.unique'      => 'Số điện thoại đã tồn tại trong hệ thống',
        'password.required' => 'Vui lòng nhập mật khẩu',
        'password.min'      => 'Mật khẩu phải có ít nhất :min ký tự',
        'password.string'   => 'Mật khẩu phải là chuỗi ký tự',
    ];

    public function login()
    {
        $this->validate([
            'phone' => 'required|digits_between:9,11',
            'password' => 'required|string|min:6',
        ]);

        // Kiểm tra user có tồn tại theo phone không
        $user = \App\Models\User::where('phone', $this->phone)->first();

        if (! $user) {
            $this->addError('phone', 'Số điện thoại không tồn tại.');
            return;
        }

        // Nếu có user rồi, kiểm tra mật khẩu
        if ($this->password !== $user->password) {
            $this->addError('password', 'Mật khẩu không đúng.');
            return;
        }

        // Đăng nhập nếu đúng
        Auth::login($user);
        session()->regenerate();
        return $this->redirect('dashboard/home', navigate: true);
    }

    public function updated($propertyName)
    {
        $this->resetErrorBag($propertyName);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
