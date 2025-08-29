<x-layouts.dash-teacher active="attendances">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('teacher.my-class.index') }}"
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>{{ __('general.back_to_classes') }}
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-calendar-check mr-2"></i>{{ __('general.attendance') }} - {{ $classroom->name }}
            </h4>
        </div>

        <!-- Thống kê nhanh -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0">{{ __('general.total_students') }}</h6>
                                <h3 class="mb-0">{{ $stats['total'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-people fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0">{{ __('general.present') }}</h6>
                                <h3 class="mb-0">{{ $stats['present'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-check-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0">{{ __('general.absent') }}</h6>
                                <h3 class="mb-0">{{ $stats['absent'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-x-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0">{{ __('general.attendance_rate') }}</h6>
                                <h3 class="mb-0">{{ $stats['presentPercentage'] }}%</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-percent fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form điểm danh -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-calendar-event mr-2"></i>{{ __('general.attendance_for_date') }}
                            {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                        </h5>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-end">
                            <button wire:click="saveAttendance" class="btn btn-primary"
                                {{ !$canTakeAttendance ? 'disabled' : '' }}>
                                <i class="bi bi-save mr-2"></i>{{ __('general.save_attendance') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (!$canTakeAttendance && $attendanceMessage)
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle mr-2"></i>
                        <strong>{{ __('general.note') }}:</strong> {{ $attendanceMessage }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (empty($attendanceData))
                    <div class="text-center py-5">
                        <i class="bi bi-people fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('general.no_students_in_class') }}</h5>
                        <p class="text-muted">{{ __('general.please_add_students_before_attendance') }}</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">#</th>
                                    <th>{{ __('general.student') }}</th>
                                    <th width="120">{{ __('general.status') }}</th>
                                    <th>{{ __('general.absence_reason') }}</th>
                                    <th width="100">{{ __('general.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendanceData as $index => $data)
                                    <tr wire:key="attendance-{{ $data['student_record']->id }}">
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm mr-3">
                                                    <i class="bi bi-person-circle fs-4 text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $data['student']->name }}</div>
                                                    <small class="text-muted">{{ $data['student']->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                    @click="$dispatch('hide-loading')"
                                                    wire:click="toggleAttendance({{ $data['student_record']->id }})"
                                                    {{ $data['present'] ? 'checked' : '' }}
                                                    {{ !$canTakeAttendance ? 'disabled' : '' }}
                                                    id="attendance_{{ $data['student_record']->id }}">
                                                <label class="form-check-label"
                                                    for="attendance_{{ $data['student_record']->id }}">
                                                    @if ($data['present'])
                                                                                                            <span class="badge bg-success">{{ __('general.present') }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ __('general.absent') }}</span>
                                                    @endif
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            @if (!$data['present'])
                                                @if ($data['reason'])
                                                    <span
                                                        class="text-muted">{{ Str::limit($data['reason'], 30) }}</span>
                                                @else
                                                    <span class="text-muted">{{ __('general.no_reason_yet') }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (!$data['present'])
                                                <button
                                                    wire:click="openReasonModal({{ $data['student_record']->id }})"
                                                    class="btn btn-sm btn-outline-primary"
                                                    {{ !$canTakeAttendance ? 'disabled' : '' }}>
                                                    <i class="bi bi-pencil mr-1"></i>{{ __('general.reason') }}
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal nhập lý do nghỉ -->
    @if ($showReasonModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-exclamation-triangle mr-2"></i>{{ __('general.absence_reason') }}
                        </h5>
                        <button type="button" class="close" wire:click="$set('showReasonModal', false)"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="absenceReason" class="form-label">{{ __('general.absence_reason') }}</label>
                            <textarea wire:model="absenceReason" class="form-control" id="absenceReason" rows="3"
                                placeholder="{{ __('general.enter_absence_reason') }}"></textarea>
                            @error('absenceReason')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showReasonModal', false)">
                            {{ __('general.cancel') }}
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="saveReason">
                            <i class="bi bi-check-circle mr-2"></i>{{ __('general.save_reason') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</x-layouts.dash-teacher>
