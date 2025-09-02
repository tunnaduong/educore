<?php

namespace App\Livewire\Auth;

use App\Mail\ResetOtpMail;
use App\Models\User;
use App\Services\OneSmsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ForgotPassword extends Component
{
    public $phone = '';

    public $email = '';

    public $method = 'phone'; // phone | email

    public $otp = '';

    public $new_password = '';

    public $confirm_password = '';

    public $step = 1; // 1: nhập số điện thoại, 2: nhập OTP, 3: đặt mật khẩu mới

    public $message = '';

    public $messageType = ''; // success, error, warning

    public $countdown = 0;

    public $canResend = true;

    protected $rules = [
        'phone' => 'required_if:method,phone|string|min:10|max:11|regex:/^[0-9]+$/',
        'email' => 'required_if:method,email|email',
        'otp' => 'required|string|size:6|regex:/^[0-9]+$/',
        'new_password' => 'required|string|min:6',
        'confirm_password' => 'required|same:new_password',
    ];

    protected $messages = [
        'phone.required' => 'Vui lòng nhập số điện thoại',
        'phone.min' => 'Số điện thoại phải có ít nhất 10 số',
        'phone.max' => 'Số điện thoại tối đa 11 số',
        'phone.regex' => 'Số điện thoại chỉ được chứa số',
        'email.required' => 'Vui lòng nhập email',
        'email.email' => 'Email không hợp lệ',
        'otp.required' => 'Vui lòng nhập mã OTP',
        'otp.size' => 'Mã OTP phải có 6 số',
        'otp.regex' => 'Mã OTP chỉ được chứa số',
        'new_password.required' => 'Vui lòng nhập mật khẩu mới',
        'new_password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        'confirm_password.required' => 'Vui lòng xác nhận mật khẩu',
        'confirm_password.same' => 'Mật khẩu xác nhận không khớp',
    ];

    public function mount()
    {
        // Kiểm tra nếu có session OTP đang chờ
        if (session()->has('reset_phone') && session()->has('reset_expires')) {
            if (now()->lt(session('reset_expires'))) {
                $this->phone = session('reset_phone');
                $this->step = 2;
                $this->startCountdown();
            } else {
                session()->forget(['reset_phone', 'reset_expires', 'reset_otp']);
            }
        }
    }

    public function sendOTP()
    {
        if ($this->method === 'phone') {
            $this->validate([
                'phone' => 'required|string|min:10|max:11',
            ]);

            $this->phone = $this->formatPhone($this->phone);

            $recentAttempts = DB::table('password_reset_tokens')
                ->where('email', $this->phone)
                ->where('created_at', '>=', now()->subHour())
                ->count();

            if ($recentAttempts >= 3) {
                $this->showMessage(__('auth.otp_limit_exceeded'), 'error');

                return;
            }

            $user = User::where('phone', $this->phone)->first();
            if (! $user) {
                $this->showMessage(__('auth.phone_not_found'), 'error');

                return;
            }

            $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $this->phone],
                [
                    'token' => $otp,
                    'created_at' => now(),
                ]
            );

            $smsService = new OneSmsService;
            $result = $smsService->sendOTP($this->phone, $otp, $user->name);

            if ($result['success']) {
                session([
                    'reset_phone' => $this->phone,
                    'reset_method' => 'phone',
                    'reset_otp' => $otp,
                    'reset_expires' => now()->addMinutes(5),
                ]);
                $this->step = 2;
                $this->startCountdown();
                $this->showMessage(__('auth.otp_sent'), 'success');
            } else {
                $this->showMessage(__('auth.send_otp_error'), 'error');
            }

            return;
        }

        // method email
        $this->validate([
            'email' => 'required|email',
        ]);

        $user = User::whereNotNull('email')->where('email', $this->email)->first();
        if (! $user) {
            $this->showMessage(__('auth.email_not_found') ?? 'Email không tồn tại', 'error');

            return;
        }

        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $this->email],
            [
                'token' => $otp,
                'created_at' => now(),
            ]
        );

        try {
            Mail::to($this->email)->send(new ResetOtpMail($otp, $user->name));
        } catch (\Throwable $e) {
            $this->showMessage('Không thể gửi email: '.$e->getMessage(), 'error');

            return;
        }

        session([
            'reset_email' => $this->email,
            'reset_method' => 'email',
            'reset_otp' => $otp,
            'reset_expires' => now()->addMinutes(5),
        ]);

        $this->step = 2;
        $this->startCountdown();
        $this->showMessage(__('auth.otp_sent'), 'success');
    }

    public function verifyOTP()
    {
        $this->validate([
            'otp' => 'required|string|size:6',
        ]);

        // Kiểm tra OTP có đúng không
        if (! session()->has('reset_otp') || ! session()->has('reset_expires')) {
            $this->showMessage(__('auth.session_expired'), 'error');
            $this->resetForm();

            return;
        }

        if (now()->gt(session('reset_expires'))) {
            $this->showMessage(__('auth.otp_expired'), 'error');
            session()->forget(['reset_phone', 'reset_expires', 'reset_otp']);
            $this->resetForm();

            return;
        }

        if ($this->otp !== session('reset_otp')) {
            $this->showMessage(__('auth.otp_invalid'), 'error');

            return;
        }

        // OTP đúng, chuyển sang bước đặt mật khẩu mới
        $this->step = 3;
        $this->showMessage(__('auth.verification_success'), 'success');
    }

    public function resetPassword()
    {
        $this->validate([
            'new_password' => 'required|string|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        // Kiểm tra lại session
        if (! session()->has('reset_method')) {
            $this->showMessage(__('auth.session_expired'), 'error');
            $this->resetForm();

            return;
        }

        // Cập nhật mật khẩu mới
        if (session('reset_method') === 'phone') {
            $user = User::where('phone', session('reset_phone'))->first();
        } else {
            $user = User::where('email', session('reset_email'))->first();
        }
        if (! $user) {
            $this->showMessage(__('auth.account_not_found'), 'error');
            $this->resetForm();

            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        // Xóa OTP khỏi database
        $key = session('reset_method') === 'phone' ? session('reset_phone') : session('reset_email');
        DB::table('password_reset_tokens')->where('email', $key)->delete();

        // Xóa session
        session()->forget(['reset_phone', 'reset_email', 'reset_method', 'reset_expires', 'reset_otp']);

        $this->showMessage(__('auth.password_reset_success'), 'success');

        // Chuyển về trang đăng nhập sau 2 giây
        $this->dispatch('redirect', route('login'));
    }

    public function resendOTP()
    {
        if (! $this->canResend) {
            return;
        }

        $this->sendOTP();
    }

    public function backToStep1()
    {
        $this->resetForm();
        $this->step = 1;
    }

    public function backToStep2()
    {
        $this->step = 2;
        $this->new_password = '';
        $this->confirm_password = '';
    }

    private function resetForm()
    {
        $this->phone = '';
        $this->email = '';
        $this->method = 'phone';
        $this->otp = '';
        $this->new_password = '';
        $this->confirm_password = '';
        $this->step = 1;
        $this->countdown = 0;
        $this->canResend = true;
        session()->forget(['reset_phone', 'reset_email', 'reset_method', 'reset_expires', 'reset_otp']);
    }

    private function showMessage($message, $type = 'info')
    {
        $this->message = $message;
        $this->messageType = $type;
    }

    private function startCountdown()
    {
        $this->countdown = 60; // 60 giây
        $this->canResend = false;

        $this->dispatch('startCountdown');
    }

    public function updated($propertyName)
    {
        $this->resetErrorBag($propertyName);
    }

    public function render()
    {
        return view('auth.forgot-password');
    }

    /**
     * Format số điện thoại
     */
    private function formatPhone($phone)
    {
        // Loại bỏ tất cả ký tự không phải số
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Đảm bảo số điện thoại bắt đầu bằng 0
        if (strlen($phone) === 10 && substr($phone, 0, 1) === '0') {
            return $phone;
        }

        // Nếu số điện thoại có 11 số và bắt đầu bằng 84, chuyển về 0
        if (strlen($phone) === 11 && substr($phone, 0, 2) === '84') {
            return '0'.substr($phone, 2);
        }

        return $phone;
    }
}
