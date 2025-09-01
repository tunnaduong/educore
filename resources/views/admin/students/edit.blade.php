<x-layouts.dash-admin>
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('students.index') }}" class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>{{ __('general.back') }}
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-pencil-square mr-2"></i>{{ __('general.edit_student') }}
            </h4>
            <p class="text-muted mb-0">{{ $student->name }}</p>
        </div>

        <!-- Success/Error Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <i class="bi bi-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <i class="bi bi-exclamation-triangle mr-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form Card Centered with Illustration -->
        <div class="card shadow-sm p-0">
            <div class="row g-0 align-items-stretch">
                <div class="col-md-7 p-4">
                    <form wire:submit="save">
                        <!-- {{ __('views.personal_info_comment') }} -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">{{ __('general.personal_info') }}</h5>
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('general.full_name') }} <span
                                        class="text-danger">*</span></label>
                                <input wire:model="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                    placeholder="{{ __('general.enter_student_name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">{{ __('general.email') }}</label>
                                        <input wire:model="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" id="email"
                                            placeholder="{{ __('general.enter_email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">{{ __('general.phone_number') }} <span
                                                class="text-danger">*</span></label>
                                        <input wire:model="phone" type="text"
                                            class="form-control @error('phone') is-invalid @enderror" id="phone"
                                            placeholder="{{ __('general.enter_phone_number') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="date_of_birth"
                                            class="form-label">{{ __('general.date_of_birth') }}</label>
                                        <input wire:model="date_of_birth" type="date"
                                            class="form-control @error('date_of_birth') is-invalid @enderror"
                                            id="date_of_birth">
                                        @error('date_of_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="joined_at"
                                            class="form-label">{{ __('general.enrollment_date') }}</label>
                                        <input wire:model="joined_at" type="date"
                                            class="form-control @error('joined_at') is-invalid @enderror"
                                            id="joined_at">
                                        @error('joined_at')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- {{ __('views.academic_info_comment') }} -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">{{ __('general.academic_info') }}</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">{{ __('general.status') }} <span
                                                class="text-danger">*</span></label>
                                        <select wire:model="status"
                                            class="form-control @error('status') is-invalid @enderror" id="status">
                                            <option value="new">{{ __('general.new_registration') }}</option>
                                            <option value="active">{{ __('general.studying') }}</option>
                                            <option value="paused">{{ __('general.paused') }}</option>
                                            <option value="suspended">{{ __('general.suspended') }}</option>
                                            <option value="dropped">{{ __('general.reserved') }}</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="level" class="form-label">{{ __('general.level') }}</label>
                                        <select wire:model="level"
                                            class="form-control @error('level') is-invalid @enderror" id="level">
                                            <option value="">{{ __('general.choose_level') }}</option>
                                            <option value="HSK1">{{ __('general.hsk1') }}</option>
                                            <option value="HSK2">{{ __('general.hsk2') }}</option>
                                            <option value="HSK3">{{ __('general.hsk3') }}</option>
                                            <option value="HSK4">{{ __('general.hsk4') }}</option>
                                            <option value="HSK5">{{ __('general.hsk5') }}</option>
                                            <option value="HSK6">{{ __('general.hsk6') }}</option>
                                            <option value="Sơ cấp 1">{{ __('general.basic1') }}</option>
                                            <option value="Sơ cấp 2">{{ __('general.basic2') }}</option>
                                            <option value="Trung cấp 1">{{ __('general.intermediate1') }}</option>
                                            <option value="Trung cấp 2">{{ __('general.intermediate2') }}</option>
                                            <option value="Cao cấp 1">{{ __('general.advanced1') }}</option>
                                            <option value="Cao cấp 2">{{ __('general.advanced2') }}</option>
                                        </select>
                                        @error('level')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">{{ __('general.notes') }}</label>
                                <textarea wire:model="notes" class="form-control @error('notes') is-invalid @enderror" id="notes" rows="3"
                                    placeholder="{{ __('general.enter_student_notes') }}"></textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('students.index') }}"
                                class="btn btn-light">{{ __('general.cancel') }}</a>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <i class="bi bi-check-circle mr-2"></i>
                                <span wire:loading.remove>{{ __('general.save_changes') }}</span>
                                <span wire:loading>{{ __('views.updating') }}...</span>
                            </button>
                        </div>
                    </form>
                </div>
                <div
                    class="col-md-5 d-flex flex-column justify-content-center align-items-center bg-light border-start rounded-end p-4">
                    <img src="/educore-logo.png" alt="{{ __('general.edit_student') }}" class="mb-3"
                        style="max-width: 90px;">
                    <div class="text-center">
                        <h6 class="text-primary fw-bold mb-2">{{ __('general.edit_student') }}</h6>
                        <p class="text-muted small mb-0">{{ __('general.update_student_info_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    @if (session()->has('success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1060;">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle mr-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1060;">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle mr-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
</x-layouts.dash-admin>
