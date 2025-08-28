<x-layouts.dash-admin active="reports">
    @include('components.language')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">
                                    <i class="bi bi-exclamation-triangle-fill mr-2"></i>
                                    {{ __('general.schedule_conflict_report') }}
                                </h5>
                                <small class="text-light">
                                    @if ($lastChecked)
                                        {{ __('general.last_updated') }}: {{ $lastChecked->format('d/m/Y H:i:s') }}
                                    @endif
                                </small>
                            </div>
                            <div class="d-flex gap-2">
                                <button wire:click="refreshConflicts" class="btn btn-light btn-sm"
                                    @if ($loading) disabled @endif>
                                    <i class="bi bi-arrow-clockwise mr-1"></i>
                                    @if ($loading)
                                        <span
                                            class="spinner-border spinner-border-sm mr-1"></span>{{ __('general.checking') }}
                                    @else
                                        {{ __('general.refresh') }}
                                    @endif
                                </button>
                                <a href="{{ route('admin.reports.schedule-conflict.export') }}"
                                    class="btn btn-success btn-sm">
                                    <i class="bi bi-download mr-1"></i>{{ __('general.export_report') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle mr-2"></i>
                                {{ session('message') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Filters -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('views.search') }}</label>
                                        <input wire:model.live="search" type="text" class="form-control"
                                            placeholder="{{ __('general.search_by_class_or_student') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('views.filter_by_class') }}</label>
                                        <select wire:model.live="filterClassroom" class="form-select">
                                            <option value="">{{ __('views.all_classes') }}</option>
                                            @foreach ($classrooms as $classroom)
                                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('views.filter_by_student') }}</label>
                                        <select wire:model.live="filterStudent" class="form-select">
                                            <option value="">{{ __('views.all_students_filter') }}</option>
                                            @foreach ($students as $student)
                                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tổng quan -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-warning">
                                    <div class="card-body text-center">
                                        <div class="display-6 text-warning mb-2">{{ $totalStudentConflicts }}</div>
                                        <h6 class="text-muted">{{ __('views.student_schedule_conflicts') }}</h6>
                                        <small
                                            class="text-muted">{{ __('views.student_conflicts_description') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-danger">
                                    <div class="card-body text-center">
                                        <div class="display-6 text-danger mb-2">{{ $totalTeacherConflicts }}</div>
                                        <h6 class="text-muted">{{ __('views.teacher_schedule_conflicts') }}</h6>
                                        <small
                                            class="text-muted">{{ __('views.teacher_conflicts_description') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($totalStudentConflicts === 0 && $totalTeacherConflicts === 0)
                            <div class="text-center py-5">
                                <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                                <h4 class="text-success mt-3">{{ __('views.no_conflicts') }}</h4>
                                <p class="text-muted">{{ __('views.no_conflicts_description') }}</p>
                            </div>
                        @else
                            <!-- Results -->
                            <div class="card shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0 text-primary">
                                        <i class="bi bi-list-ul mr-2"></i>{{ __('general.schedule_conflict_list') }}
                                        <span class="badge bg-warning ml-2">{{ $conflicts->total() }}</span>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if ($conflicts->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>{{ __('general.classroom') }}</th>
                                                        <th>{{ __('general.student') }}</th>
                                                        <th>{{ __('general.conflicting_classes_count') }}</th>
                                                        <th>{{ __('general.conflict_details') }}</th>
                                                        <th class="text-center">{{ __('general.actions') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($conflicts as $conflict)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <i class="bi bi-mortarboard text-primary mr-2"></i>
                                                                    <div>
                                                                        <div class="fw-medium">
                                                                            {{ $conflict['classroom']->name }}</div>
                                                                        <small
                                                                            class="text-muted">{{ $conflict['classroom']->level }}</small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <i
                                                                        class="bi bi-person-circle text-success mr-2"></i>
                                                                    <div>
                                                                        <div class="fw-medium">
                                                                            {{ $conflict['student']->name }}</div>
                                                                        <small
                                                                            class="text-muted">{{ $conflict['student']->email }}</small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="badge bg-danger">{{ count($conflict['conflicts']) }}</span>
                                                            </td>
                                                            <td>
                                                                <div class="small text-muted">
                                                                    @foreach ($conflict['conflicts'] as $index => $detail)
                                                                        @if ($index < 2)
                                                                            <div>{{ $detail['message'] }}</div>
                                                                        @endif
                                                                    @endforeach
                                                                    @if (count($conflict['conflicts']) > 2)
                                                                        <div class="text-primary">
                                                                            +{{ count($conflict['conflicts']) - 2 }} {{ __('views.other_classes') }}</div>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            <td class="text-center">
                                                                <button
                                                                    wire:click="showConflictDetails({{ $conflict['classroom']->id }}, {{ $conflict['student']->id }})"
                                                                    class="btn btn-sm btn-outline-primary">
                                                                    <i
                                                                        class="bi bi-eye mr-1"></i>{{ __('general.details') }}
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Pagination -->
                                        @if ($conflicts->hasPages())
                                            <div class="d-flex justify-content-center mt-3">
                                                {{ $conflicts->links('vendor.pagination.bootstrap-5') }}
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-center py-5">
                                            <i class="bi bi-check-circle-fill text-success fs-1 d-block mb-3"></i>
                                            <h5 class="text-success">{{ __('general.no_conflicts') }}</h5>
                                            <p class="text-muted">{{ __('general.all_students_have_valid_schedules') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Chi tiết trùng lịch -->
    @if ($showDetails && $selectedConflict)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">
                            <i class="bi bi-exclamation-triangle-fill mr-2"></i>
                            {{ __('general.conflict_details') }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeDetails"></button>
                    </div>
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-primary">{{ __('general.student_information') }}</h6>
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-person-circle text-primary mr-2"></i>
                                            <div>
                                                <strong>{{ $selectedConflict['student']->name }}</strong><br>
                                                <small
                                                    class="text-muted">{{ $selectedConflict['student']->email }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-success">{{ __('views.current_class') }}</h6>
                                <div class="card border-success">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="bi bi-mortarboard text-success mr-2"></i>
                                            <div>
                                                <strong>{{ $selectedConflict['classroom']->name }}</strong><br>
                                                <small class="text-muted">
                                                    @if ($selectedConflict['classroom']->schedule)
                                                        {{ implode(', ', $selectedConflict['classroom']->schedule['days'] ?? []) }}
                                                        -
                                                        {{ $selectedConflict['classroom']->schedule['time'] ?? '' }}
                                                    @else
                                                        {{ __('views.no_schedule') }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h6 class="text-danger mb-3">{{ __('views.conflicting_classes') }}</h6>
                        @foreach ($selectedConflict['conflicts'] as $conflict)
                            <div class="card border-danger mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-danger">
                                        <i class="bi bi-calendar-event text-danger mr-2"></i>
                                        {{ $conflict['classroom']->name }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>{{ __('general.conflicting_schedule') }}:</strong><br>
                                            <small class="text-muted">{{ $conflict['message'] }}</small>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>{{ __('general.overlap_time') }}:</strong><br>
                                            <small class="text-muted">
                                                @if ($conflict['overlapTime'])
                                                    {{ $conflict['overlapTime'] }}
                                                @else
                                                    {{ __('general.full_time') }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeDetails">
                            <i class="bi bi-x-circle mr-2"></i>
                            {{ __('general.close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <style>
        .avatar-sm {
            width: 32px;
            height: 32px;
            font-size: 14px;
            font-weight: 600;
        }
    </style>
</x-layouts.dash-admin>
