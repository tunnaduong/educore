<x-layouts.dash-admin>
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('users.index') }}" class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>{{ __('general.back') }}
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-person-plus-fill mr-2"></i>{{ __('general.add_new_user') }}
            </h4>
        </div>

        <!-- Form Card Centered with Illustration -->
        <div class="card shadow-sm p-0">
            <div class="row g-0 align-items-stretch">
                <div class="col-md-7 p-4">
                    <form wire:submit="save" novalidate>
                        <!-- Thông tin cơ bản -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">{{ __('general.personal_info') }}</h5>
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('general.full_name') }} <span
                                        class="text-danger">*</span></label>
                                <input wire:model="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" id="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">{{ __('general.phone_number') }} <span
                                        class="text-danger">*</span></label>
                                <input wire:model.live="phone" type="text" inputmode="numeric"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                    class="form-control @error('phone') is-invalid @enderror" id="phone">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('general.email') }}</label>
                                <input wire:model="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" id="email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Thông tin tài khoản -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">{{ __('general.account_info') }}</h5>
                            <div class="mb-3">
                                <label for="role" class="form-label">{{ __('general.role') }} <span
                                        class="text-danger">*</span></label>
                                <select wire:model="role" class="form-control @error('role') is-invalid @enderror"
                                    id="role">
                                    <option value="">{{ __('general.choose_role') }}</option>
                                    <option value="admin">{{ __('general.administrator') }}</option>
                                    <option value="teacher">{{ __('general.instructor') }}</option>
                                    <option value="student">{{ __('general.learner') }}</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('general.password') }} <span
                                        class="text-danger">*</span></label>
                                <input wire:model="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" id="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation"
                                    class="form-label">{{ __('general.confirm_password') }} <span
                                        class="text-danger">*</span></label>
                                <input wire:model="password_confirmation" type="password" class="form-control"
                                    id="password_confirmation">
                            </div>
                            <div class="form-check mb-3">
                                <input wire:model.live="is_active" class="form-check-input" type="checkbox"
                                    id="is_active">
                                <label class="form-check-label" for="is_active">
                                    {{ __('general.activate_account') }}
                                </label>
                            </div>
                        </div>
                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('users.index') }}" class="btn btn-light">{{ __('general.cancel') }}</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-person-plus-fill mr-2"></i>{{ __('general.create_user') }}
                            </button>
                        </div>
                    </form>
                </div>
                <div
                    class="col-md-5 d-flex flex-column justify-content-center align-items-center bg-light border-start rounded-end p-4">
                    <img src="/educore-logo.png" alt="{{ __('general.add_new_user') }}" class="mb-3"
                        style="max-width: 90px;">
                    <div class="text-center">
                        <h6 class="text-primary fw-bold mb-2">{{ __('general.create_new_account') }}</h6>
                        <p class="text-muted small mb-0">{{ __('general.add_user_to_system') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
