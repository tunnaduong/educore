<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
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

        .form-check-input:checked {
            background-color: #667eea !important;
            border-color: #667eea !important;
        }
    </style>
    @include('components.language')

    <x-auth-language-switcher />

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card shadow-lg border-0 rounded-4" style="border-radius: 20px !important;">
                    <!-- Header with Logo -->
                    <div class="card-body p-sm-5">
                        <div class="text-center mb-4">
                            <div class="d-flex justify-content-center">
                                <img src="/educore-logo.png" alt="Logo" style="width: 60px; height: 60px;">
                            </div>

                            <h3 class="fw-bold mb-2" style="font-size: 2rem; font-weight: 700;">
                                <span class="text-primary">Edu</span><span class="text-warning">Core</span>
                            </h3>
                            <p class="text-muted mb-4">{{ __('auth.login_subtitle') }}</p>
                        </div>

                        <form wire:submit.prevent="login">
                            <!-- Phone Input -->
                            <div class="mb-4">
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
                            </div>

                            <!-- Password Input -->
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold text-dark mb-2">
                                    <i class="fas fa-lock mr-2 text-primary"></i>{{ __('auth.password_label') }}
                                </label>
                                <input type="password" id="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    style="border-radius: 12px; border: 2px solid #e9ecef; padding: 15px 20px;"
                                    wire:model.defer="password" placeholder="{{ __('auth.password_placeholder') }}">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" wire:model="remember" id="remember"
                                    style="width: 18px; height: 18px;">
                                <label class="form-check-label text-muted ml-2" style="margin-top: 2px" for="remember">
                                    {{ __('auth.remember_me') }}
                                </label>
                            </div>

                            <!-- Login Button -->
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary btn-lg fw-semibold py-3 rounded-3 w-100"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 12px;">
                                    <i class="fas fa-arrow-right mr-2"></i>
                                    {{ __('auth.login_button') }}
                                </button>
                            </div>
                        </form>

                        <!-- Forgot Password Link -->
                        <div class="text-center mb-4">
                            <a href="{{ route('password.request') }}" class="text-decoration-none text-muted">
                                {{ __('auth.forgot_password') }}?
                            </a>
                        </div>

                        <!-- Footer -->
                        <div class="text-center mt-4 pt-3" style="border-top: 1px solid #e9ecef;">
                            <small class="text-muted">
                                {{ __('general.copyright') }}<br>{{ __('general.footer_description') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
