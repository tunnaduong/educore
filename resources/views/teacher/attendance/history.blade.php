<x-layouts.dash-teacher active="attendances">
    @include('components.language')
    
    <div class="container-fluid">
        <div class="mb-4">
            <a href="{{ route('teacher.attendance.overview') }}" class="btn btn-light mb-2">
                <i class="bi bi-arrow-left mr-2"></i>{{ __('general.back_to_overview') }}
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-list-ul mr-2"></i>{{ __('general.attendance_history') }}
            </h4>
        </div>

        <!-- Bộ lọc -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input wire:model.live="search" type="text" class="form-control"
                                placeholder="{{ __('general.search_students_classes') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select wire:model.live="selectedMonth" class="form-control">
                            <option value="">{{ __('general.all_months') }}</option>
                            @for ($month = 1; $month <= 12; $month++)
                                <option value="{{ $month }}">{{ __('general.month') }} {{ $month }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select wire:model.live="selectedYear" class="form-control">
                            <option value="">{{ __('general.all_years') }}</option>
                            @for ($year = date('Y') - 2; $year <= date('Y') + 1; $year++)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('general.date') }}</th>
                                <th>{{ __('general.classroom') }}</th>
                                <th>{{ __('general.student') }}</th>
                                <th class="text-center">{{ __('general.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attendances as $attendance)
                                <tr>
                                    <td>
                                        <div class="fw-medium">{{ $attendance->date->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $attendance->date->format('D') }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $attendance->classroom?->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $attendance->classroom->level }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $attendance->student?->user?->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $attendance->student->user->email }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if ($attendance->present)
                                            <span class="badge bg-success">{{ __('general.present') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ __('general.absent') }}</span>
                                            @if ($attendance->reason)
                                                <br><small class="text-muted">{{ $attendance->reason }}</small>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="bi bi-calendar-x fs-1 mb-2"></i>
                                        <div>{{ __('general.no_attendance_data') }}</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $attendances->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-teacher>
