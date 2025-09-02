<x-layouts.dash-admin active="schedules">
    @include('components.language')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 fs-4">{{ __('general.edit_schedule') }}</h4>
                            <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left mr-2"></i>{{ __('general.back') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Thông báo thành công -->
                        @if (session()->has('success_message'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ session('success_message') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="mb-4">
                            <h5 class="text-primary">{{ $classroom->name }}</h5>
                            <p class="text-muted mb-0">
                                <i class="bi bi-person-circle mr-2"></i>
                                {{ __('general.teacher') }}:
                                @if ($classroom->teachers->count())
                                    {{ $classroom->teachers->pluck('name')->join(', ') }}
                                @else
                                    {{ __('general.not_assigned') }}
                                @endif
                            </p>
                        </div>

                        <form wire:submit="save">
                            <!-- Chọn ngày trong tuần -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">{{ __('general.select_study_days') }} <span
                                        class="text-danger">*</span></label>
                                <div class="row g-2">
                                    @foreach ($availableDays as $dayKey => $dayName)
                                        <div class="col-md-3 col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    wire:model="selectedDays" value="{{ $dayKey }}"
                                                    id="day_{{ $dayKey }}">
                                                <label class="form-check-label" for="day_{{ $dayKey }}">
                                                    {{ $dayName }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('selectedDays')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Thời gian học -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="startTime" class="form-label fw-bold">{{ __('general.start_time') }}
                                        <span class="text-danger">*</span></label>
                                    <input type="time" wire:model="startTime"
                                        class="form-control @error('startTime') is-invalid @enderror" id="startTime">
                                    @error('startTime')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="endTime" class="form-label fw-bold">{{ __('general.end_time') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="time" wire:model="endTime"
                                        class="form-control @error('endTime') is-invalid @enderror" id="endTime">
                                    @error('endTime')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Ghi chú -->
                            <div class="mb-4">
                                <label for="notes" class="form-label fw-bold">{{ __('general.notes') }}</label>
                                <textarea wire:model="notes" class="form-control @error('notes') is-invalid @enderror" id="notes" rows="3"
                                    placeholder="{{ __('general.enter_schedule_notes') }}"></textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>



                            <!-- Nút thao tác -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('schedules.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle mr-2"></i>{{ __('general.cancel') }}
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle mr-2"></i>{{ __('general.save_changes') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Cảnh báo trùng lịch giáo viên (theo logic tương tự trang tạo lớp) --}}
    @if ($showConflictModal)
        <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-danger text-white border-0">
                        <h5 class="modal-title fw-bold">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Cảnh báo xung đột lịch học
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                            wire:click="closeConflictModal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="conflicts-list" style="max-height: 400px; overflow-y: auto;">
                            @foreach ($teacherConflicts as $teacherId => $conflictData)
                                <div class="card border-warning mb-4 shadow-sm">
                                    <div class="card-header bg-light border-warning">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-person-circle text-primary"></i>
                                            <span class="text-dark">Giáo viên:
                                                <strong>{{ $conflictData['teacher']->name }}</strong></span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <i class="bi bi-calendar-x me-4 text-warning"></i>
                                            <span class="text-dark">Các lớp học xung đột:</span>
                                            @foreach ($conflictData['conflicts'] as $conflict)
                                                <span class="text-dark">{{ $conflict['classroom']->name }}</span>
                                                @if (!$loop->last)
                                                    ,
                                                @endif
                                            @endforeach
                                        </div>
                                        @foreach ($conflictData['conflicts'] as $conflict)
                                            @if ($conflict['overlapTime'])
                                                <div class="mb-3">
                                                    <i class="bi bi-clock me-3 text-success"></i>
                                                    <span class="text-success fw-semibold">Thời gian trùng:
                                                        {{ $conflict['overlapTime'] }}</span>
                                                </div>
                                            @endif
                                            <div class="mb-3">
                                                <i class="bi bi-exclamation-triangle me-3 text-danger"></i>
                                                <span class="text-danger">{{ $conflict['message'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle mr-2"></i>
                            <strong>{{ __('general.note') }}</strong> Vui lòng điều chỉnh lịch học hoặc chọn giáo viên
                            khác để tránh trùng lịch.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeConflictModal">
                            <i class="bi bi-x-circle mr-2"></i>{{ __('general.cancel') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</x-layouts.dash-admin>
