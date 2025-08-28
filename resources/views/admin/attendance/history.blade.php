<x-layouts.dash-admin active="attendances">
    @include('components.language')
    <div class="container-fluid">
        <div class="mb-4">
            <a href="{{ route('attendances.overview') }}" class="btn btn-light mb-2">
                <i class="bi bi-arrow-left mr-2"></i>@lang('general.back_to_overview')
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-list-ul mr-2"></i>@lang('general.attendance_history')
            </h4>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>@lang('general.date')</th>
                                <th>@lang('general.classroom')</th>
                                <th>@lang('general.student')</th>
                                <th class="text-center">@lang('general.status')</th>
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
                                        <div class="fw-medium">{{ $attendance->classroom?->name ?? __('general.not_available') }}</div>
                                        <small class="text-muted">{{ $attendance->classroom->level }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $attendance->student?->user?->name ?? __('general.not_available') }}</div>
                                        <small class="text-muted">{{ $attendance->student->user->email }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if ($attendance->present)
                                            <span class="badge bg-success">@lang('general.present')</span>
                                        @else
                                            <span class="badge bg-danger">@lang('general.absent')</span>
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
                                        <div>@lang('general.no_attendance_data')</div>
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
</x-layouts.dash-admin>
