<x-layouts.dash-teacher active="assignments">
    @include('components.language')
    
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0 text-primary fs-4">
                    <i class="bi bi-journal-text mr-2"></i>{{ __('general.assignments_overview') }}
                </h4>
                <p class="text-muted mb-0">{{ __('general.manage_track_assignments') }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('teacher.assignments.create') }}" class="btn btn-outline-primary">
                    <i class="bi bi-plus-circle mr-2"></i>{{ __('general.create_new_assignment') }}
                </a>
            </div>
        </div>

        <!-- Bộ lọc tháng/năm (nếu cần) -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-funnel mr-2"></i>{{ __('general.filter_by_month') }}
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2 justify-content-end">
                            <select wire:model.live="selectedMonth" class="form-control" style="max-width: 150px;">
                                @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{ $month }}">{{ __('general.month') }} {{ $month }}</option>
                                @endfor
                            </select>
                            <select wire:model.live="selectedYear" class="form-control" style="max-width: 120px;">
                                @for ($year = date('Y') - 2; $year <= date('Y') + 1; $year++)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê tổng quan -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0">{{ __('general.total_assignments') }}</h6>
                                <h3 class="mb-0">{{ $overviewStats['total_assignments'] ?? 0 }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-journal-text fs-1"></i>
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
                                <h6 class="card-title mb-0">{{ __('general.classes_with_assignments') }}</h6>
                                <h3 class="mb-0">{{ $overviewStats['total_classes'] ?? 0 }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-mortarboard fs-1"></i>
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
                                <h6 class="card-title mb-0">{{ __('general.total_submissions') }}</h6>
                                <h3 class="mb-0">{{ $overviewStats['total_submissions'] ?? 0 }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-upload fs-1"></i>
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
                                <h6 class="card-title mb-0">{{ __('general.submission_rate') }}</h6>
                                <h3 class="mb-0">{{ $overviewStats['submission_rate'] ?? 0 }}%</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-percent fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Top lớp nhiều bài tập nhất -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-trophy mr-2"></i>{{ __('general.top_5_classes_most_assignments') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($topClasses->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ __('general.classroom') }}</th>
                                            <th class="text-center">{{ __('general.total_assignments') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topClasses as $classData)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm mr-3">
                                                            <i class="bi bi-mortarboard fs-4 text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">
                                                                {{ $classData['classroom']->name ?? '-' }}</div>
                                                            <small
                                                                class="text-muted">{{ $classData['classroom']->level ?? '' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge bg-secondary">{{ $classData['total_assignments'] }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-journal-x fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">{{ __('general.no_assignment_data_yet') }}</h5>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Bài tập gần đây -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-primary">
                                <i class="bi bi-clock-history mr-2"></i>{{ __('general.recent_assignments') }}
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($recentAssignments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ __('general.title') }}</th>
                                            <th>{{ __('general.classroom') }}</th>
                                            <th>{{ __('general.deadline') }}</th>
                                            <th>{{ __('general.assigned_at') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentAssignments as $assignment)
                                            <tr>
                                                <td>{{ $assignment->title }}</td>
                                                <td>{{ $assignment->classroom->name ?? '-' }}</td>
                                                <td>{{ $assignment->deadline ? $assignment->deadline->format('d/m/Y H:i') : '-' }}
                                                </td>
                                                <td>{{ $assignment->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <a href="{{ route('teacher.assignments.show', $assignment->id) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('teacher.assignments.edit', $assignment->id) }}"
                                                        class="btn btn-sm btn-outline-warning">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#deleteAssignmentModal{{ $assignment->id }}"
                                                        class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Delete Confirmation Modal -->
                                            <div class="modal fade" id="deleteAssignmentModal{{ $assignment->id }}"
                                                tabindex="-1"
                                                aria-labelledby="deleteAssignmentModalLabel{{ $assignment->id }}"
                                                aria-hidden="true">
                                                <div
                                                    class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="deleteAssignmentModalLabel{{ $assignment->id }}">
                                                                {{ __('general.confirm_delete_assignment') }}</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            {{ __('general.are_you_sure_delete_assignment') }}
                                                            "{{ $assignment->title }}"? {{ __('general.warning_action_irreversible') }}
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">{{ __('general.cancel') }}</button>
                                                            <button type="button" class="btn btn-danger"
                                                                wire:click="deleteAssignment({{ $assignment->id }})"
                                                                data-bs-dismiss="modal">{{ __('general.delete') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-journal-x fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">{{ __('general.no_assignments') }}</h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Top học viên nộp bài đúng hạn -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-star mr-2"></i>{{ __('general.top_5_on_time_students') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($topStudents->count() > 0)
                            @foreach ($topStudents as $index => $studentData)
                                <div
                                    class="d-flex align-items-center mb-3 {{ $index < 3 ? 'p-2 bg-light rounded' : '' }}">
                                    <div class="mr-3">
                                        @if ($index < 3)
                                            <span class="badge bg-warning">{{ $index + 1 }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-medium">{{ $studentData['student']->name ?? '-' }}</div>
                                        <small class="text-muted">{{ $studentData['student']->email ?? '' }}</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-success">{{ $studentData['on_time_rate'] }}%</div>
                                        <small
                                            class="text-muted">{{ $studentData['on_time'] }}/{{ $studentData['total_submissions'] }}</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-3">
                                <i class="bi bi-people fs-1 text-muted mb-2"></i>
                                <p class="text-muted mb-0">{{ __('general.no_student_data') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-teacher>
