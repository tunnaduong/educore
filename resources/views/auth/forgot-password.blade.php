<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    @include('components.language')
    <style>
        .form-control:focus {
            border-color: #667eea !important;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%) !important;
            transform: translateY(-1px);
            transition: all 0.3s ease;
        }

        .card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95) !important;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin: 0 1rem;
            position: relative;
        }

        .step.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .step.completed {
            background: #28a745;
            color: white;
        }

        .step.inactive {
            background: #e9ecef;
            color: #6c757d;
        }

        .step-line {
            position: absolute;
            top: 50%;
            left: 100%;
            width: 2rem;
            height: 2px;
            background: #e9ecef;
            transform: translateY(-50%);
        }

        .step-line.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .otp-input {
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            letter-spacing: 0.5rem;
        }

        .resend-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .alert {
            border-radius: 12px;
            border: none;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: #155724;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #721c24;
        }

        .alert-warning {
            background: rgba(255, 193, 7, 0.1);
            color: #856404;
        }

        .btn-link {
            color: #667eea;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-link:hover {
            color: #5a6fd8;
            text-decoration: underline;
        }

        .btn-link.disabled {
            color: #6c757d;
            cursor: not-allowed;
        }

        .btn-link.disabled:hover {
            text-decoration: none;
        }

        .form-control:focus {
            border-color: #667eea !important;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
        }

        .otp-input:focus {
            letter-spacing: 0.5rem;
        }
    </style>

    <x-auth-language-switcher />

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card shadow-lg border-0 rounded-4" style="border-radius: 20px !important;">
                    <div class="card-body p-sm-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <div class="d-flex justify-content-center">
                                <img src="/educore-logo.png" alt="Logo" style="width: 60px; height: 60px;">
                            </div>
                            <h3 class="fw-bold mb-2" style="font-size: 2rem; font-weight: 700;">
                                <span class="text-primary">Edu</span><span class="text-warning">Core</span>
                            </h3>
                            <p class="text-muted mb-4">{{ __('auth.forgot_password_subtitle') }}</p>
                        </div>

                        <!-- Step Indicator -->
                        <div class="step-indicator">
                            <div class="step {{ $step >= 1 ? ($step == 1 ? 'active' : 'completed') : 'inactive' }}">
                                1
                                @if ($step > 1)
                                    <div class="step-line active"></div>
                                @endif
                            </div>
                            <div class="step {{ $step >= 2 ? ($step == 2 ? 'active' : 'completed') : 'inactive' }}">
                                2
                                @if ($step > 2)
                                    <div class="step-line active"></div>
                                @endif
                            </div>
                            <div class="step {{ $step >= 3 ? 'active' : 'inactive' }}">
                                3
                            </div>
                        </div>

                        <!-- Step Labels -->
                        <div class="text-center mb-4">
                            <small class="text-muted">
                                @if ($step == 1)
                                    <i class="fas fa-mobile-alt mr-1"></i>{{ __('auth.enter_phone') }}
                                @elseif($step == 2)
                                    <i class="fas fa-key mr-1"></i>{{ __('auth.verify_otp') }}
                                @else
                                    <i class="fas fa-lock mr-1"></i>{{ __('auth.set_new_password') }}
                                @endif
                            </small>
                        </div>

                        <!-- Message Alert -->
                        @if ($message)
                            <div
                                class="alert alert-{{ $messageType === 'success' ? 'success' : ($messageType === 'error' ? 'danger' : 'warning') }} mb-4">
                                {{ $message }}
                            </div>
                        @endif

                        <!-- Step 1: Choose method and enter identifier -->
                        @if ($step == 1)
                            <form wire:submit.prevent="sendOTP">
                                <div class="mb-3">
                                    <label
                                        class="form-label fw-semibold text-dark mb-2">{{ __('auth.otp_method_title') }}</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="method_phone"
                                                value="phone" wire:model.live="method">
                                            <label class="form-check-label" for="method_phone">
                                                {{ __('auth.phone_method') }}
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="method_email"
                                                value="email" wire:model.live="method">
                                            <label class="form-check-label" for="method_email">
                                                {{ __('auth.email_method') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    @if ($method === 'phone')
                                        <label for="phone" class="form-label fw-semibold text-dark mb-2">
                                            <i class="fas fa-mobile-alt mr-2 text-primary"></i>{{ __('auth.phone') }}
                                        </label>
                                        <input type="text" id="phone"
                                            class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                            style="border-radius: 12px; border: 2px solid #e9ecef; padding: 15px 20px;"
                                            wire:model.defer="phone" placeholder="{{ __('auth.phone_placeholder') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    @else
                                        <label for="email" class="form-label fw-semibold text-dark mb-2">
                                            <i class="fas fa-envelope mr-2 text-primary"></i>Email
                                        </label>
                                        <input type="email" id="email"
                                            class="form-control form-control-lg @error('email') is-invalid @enderror"
                                            style="border-radius: 12px; border: 2px solid #e9ecef; padding: 15px 20px;"
                                            wire:model.defer="email" placeholder="you@example.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    @endif
                                </div>

                                <div class="d-grid mb-4">
                                    <button type="submit"
                                        class="btn btn-primary btn-lg fw-semibold py-3 rounded-3 w-100"
                                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 12px;">
                                        <i class="fas fa-paper-plane mr-2"></i>
                                        {{ __('auth.send_otp') }}
                                    </button>
                                </div>

                                <div class="text-center">
                                    <a href="{{ route('login') }}" class="text-decoration-none">
                                        <i class="fas fa-arrow-left mr-2"></i>
                                        {{ __('auth.back_to_login') }}
                                    </a>
                                </div>
                            </form>
                        @endif

                        <!-- Step 2: Enter OTP -->
                        @if ($step == 2)
                            <form wire:submit.prevent="verifyOTP">
                                <div class="mb-4">
                                    <label for="otp" class="form-label fw-semibold text-dark mb-2">
                                        <i class="fas fa-key mr-2 text-primary"></i>{{ __('auth.otp') }}
                                    </label>
                                    <input type="text" id="otp"
                                        class="form-control form-control-lg otp-input @error('otp') is-invalid @enderror"
                                        style="border-radius: 12px; border: 2px solid #e9ecef; padding: 15px 20px;"
                                        wire:model.defer="otp" placeholder="000000" maxlength="6">
                                    @error('otp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted mt-2 d-block">
                                        {{ __('auth.otp_sent_to') }} {{ $phone }}<br>
                                        <i class="fas fa-info-circle mr-1"></i>
                                        {{ __('auth.otp_validity') }}
                                    </small>
                                </div>

                                <div class="d-grid mb-4">
                                    <button type="submit"
                                        class="btn btn-primary btn-lg fw-semibold py-3 rounded-3 w-100"
                                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 12px;">
                                        <i class="fas fa-check mr-2"></i>
                                        {{ __('auth.verify_otp') }}
                                    </button>
                                </div>

                                <div class="text-center mb-3">
                                    <button type="button" wire:click="resendOTP"
                                        class="btn btn-link text-decoration-none {{ !$canResend ? 'disabled' : '' }}"
                                        {{ !$canResend ? 'disabled' : '' }}>
                                        @if ($canResend)
                                            <i class="fas fa-redo mr-2"></i>
                                            {{ __('auth.resend_otp') }}
                                        @else
                                            <i class="fas fa-clock mr-2"></i>
                                            {{ __('auth.resend_otp_after') }} <span
                                                id="countdown">{{ $countdown }}</span>s
                                        @endif
                                    </button>
                                </div>

                                <div class="text-center">
                                    <button type="button" wire:click="backToStep1"
                                        class="btn btn-link text-decoration-none">
                                        <i class="fas fa-arrow-left mr-2"></i>
                                        {{ __('auth.change_phone') }}
                                    </button>
                                </div>
                            </form>
                        @endif

                        <!-- Step 3: Set New Password -->
                        @if ($step == 3)
                            <form wire:submit.prevent="resetPassword">
                                <div class="mb-4">
                                    <label for="new_password" class="form-label fw-semibold text-dark mb-2">
                                        <i class="fas fa-lock mr-2 text-primary"></i>{{ __('auth.new_password') }}
                                    </label>
                                    <input type="password" id="new_password"
                                        class="form-control form-control-lg @error('new_password') is-invalid @enderror"
                                        style="border-radius: 12px; border: 2px solid #e9ecef; padding: 15px 20px;"
                                        wire:model.defer="new_password"
                                        placeholder="{{ __('auth.new_password_placeholder') }}">
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="confirm_password" class="form-label fw-semibold text-dark mb-2">
                                        <i class="fas fa-lock mr-2 text-primary"></i>{{ __('auth.confirm_password') }}
                                    </label>
                                    <input type="password" id="confirm_password"
                                        class="form-control form-control-lg @error('confirm_password') is-invalid @enderror"
                                        style="border-radius: 12px; border: 2px solid #e9ecef; padding: 15px 20px;"
                                        wire:model.defer="confirm_password"
                                        placeholder="{{ __('auth.confirm_password_placeholder') }}">
                                    @error('confirm_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-grid mb-4">
                                    <button type="submit"
                                        class="btn btn-primary btn-lg fw-semibold py-3 rounded-3 w-100"
                                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 12px;">
                                        <i class="fas fa-save mr-2"></i>
                                        {{ __('auth.reset_password') }}
                                    </button>
                                </div>

                                <div class="text-center">
                                    <button type="button" wire:click="backToStep2"
                                        class="btn btn-link text-decoration-none">
                                        <i class="fas fa-arrow-left mr-2"></i>
                                        {{ __('auth.back_to_otp') }}
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý countdown
        let countdownInterval;

        Livewire.on('startCountdown', () => {
            let countdown = 60;
            const countdownElement = document.getElementById('countdown');

            if (countdownInterval) {
                clearInterval(countdownInterval);
            }

            countdownInterval = setInterval(() => {
                countdown--;
                if (countdownElement) {
                    countdownElement.textContent = countdown;
                }

                // Cập nhật biến Livewire mỗi giây
                @this.set('countdown', countdown);

                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    @this.set('canResend', true);
                    @this.set('countdown', 0);
                }
            }, 1000);
        });

        // Khởi tạo countdown nếu đã có sẵn
        const countdownElement = document.getElementById('countdown');
        if (countdownElement && countdownElement.textContent > 0) {
            let countdown = parseInt(countdownElement.textContent);
            if (countdown > 0 && countdown <= 60) {
                if (countdownInterval) {
                    clearInterval(countdownInterval);
                }

                countdownInterval = setInterval(() => {
                    countdown--;
                    if (countdownElement) {
                        countdownElement.textContent = countdown;
                    }

                    // Cập nhật biến Livewire mỗi giây
                    @this.set('countdown', countdown);

                    if (countdown <= 0) {
                        clearInterval(countdownInterval);
                        @this.set('canResend', true);
                        @this.set('countdown', 0);
                    }
                }, 1000);
            }
        }



        // Xử lý redirect
        Livewire.on('redirect', (url) => {
            setTimeout(() => {
                window.location.href = url;
            }, 2000);
        });

        // Auto focus OTP input
        if (document.getElementById('otp')) {
            document.getElementById('otp').focus();
        }

        // Auto focus identifier
        setTimeout(() => {
            const el = document.getElementById('phone') || document.getElementById('email');
            if (el) el.focus();
        }, 50);

        // Xử lý input OTP - chỉ cho phép nhập số
        const otpInput = document.getElementById('otp');
        if (otpInput) {
            otpInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length === 6) {
                    // Tự động submit form khi nhập đủ 6 số
                    this.closest('form').dispatchEvent(new Event('submit'));
                }
            });
        }

        // Xử lý input phone - chỉ cho phép nhập số
        const enforcePhoneNumeric = () => {
            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }
        };
        enforcePhoneNumeric();
        document.addEventListener('livewire:navigated', enforcePhoneNumeric);
    });
</script>
