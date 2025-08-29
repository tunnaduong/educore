<x-layouts.dash-teacher active="grading">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-journal-check mr-2"></i>{{ __('general.assignments_to_grade') }}
                    </h4>
                    <p class="text-muted mb-0">{{ __('general.manage_grade_assignments') }}</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.search') }}</label>
                        <input type="text" class="form-control" wire:model.live="search"
                            placeholder="{{ __('general.search_by_name_description') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.classroom') }}</label>
                        <select class="form-control" wire:model.live="filterClassroom">
                            <option value="">{{ __('general.all_classes') }}</option>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.status') }}</label>
                        <select class="form-control" wire:model.live="filterStatus">
                            <option value="all">{{ __('general.all') }}</option>
                            <option value="has_submissions">{{ __('general.has_submissions') }}</option>
                            <option value="no_submissions">{{ __('general.no_submissions') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">{{ __('general.sort') }}</label>
                        <select class="form-control" wire:model.live="sortBy">
                            <option value="submissions_count">{{ __('general.submission_count') }}</option>
                            <option value="created_at">{{ __('general.created_date') }}</option>
                            <option value="deadline">{{ __('general.deadline') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-9">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                        <div>
                            <i class="bi bi-journal-check mr-2"></i>
                            <span class="mb-0">{{ __('general.assignments_to_grade') }}</span>
                        </div>
                        <div class="text-white-50 small">
                            {{ $assignments->total() }} {{ __('general.total_assignments') }}
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if ($assignments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>
                                                {{ __('general.classroom') }}
                                                @if ($sortBy === 'created_at')
                                                    <i
                                                        class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </th>
                                            <th>{{ __('general.title') }}</th>
                                            <th>
                                                {{ __('general.deadline') }}
                                                @if ($sortBy === 'deadline')
                                                    <i
                                                        class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </th>
                                            <th class="text-center">
                                                {{ __('general.submission_count_table') }}
                                                @if ($sortBy === 'submissions_count')
                                                    <i
                                                        class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </th>
                                            <th class="text-center">{{ __('general.status') }}</th>
                                            <th class="text-center">{{ __('general.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($assignments as $assignment)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm mr-3">
                                                            <i class="bi bi-mortarboard fs-4 text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">
                                                                {{ $assignment->classroom->name ?? '-' }}</div>
                                                            <small
                                                                class="text-muted">{{ $assignment->classroom->level ?? '' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-medium">{{ $assignment->title }}</div>
                                                    @if ($assignment->description)
                                                        <small
                                                            class="text-muted">{{ Str::limit($assignment->description, 50) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($assignment->deadline)
                                                        <div class="fw-medium">
                                                            {{ $assignment->deadline->format('d/m/Y H:i') }}</div>
                                                        <small
                                                            class="text-muted">{{ $assignment->deadline->diffForHumans() }}</small>
                                                    @else
                                                        <span class="text-muted">{{ __('general.no_deadline') }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge bg-info">{{ $assignment->submissions_count }}</span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($assignment->submissions_count > 0)
                                                        <span class="badge bg-success">{{ __('general.has_submission') }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ __('general.no_submission') }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <button wire:click="selectAssignment({{ $assignment->id }})"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="bi bi-check-circle mr-1"></i>{{ __('general.grade_assignment') }}
                                                    </button>
                                                </td>
                                            </tr>
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

                <!-- Pagination -->
                @if ($assignments->hasPages())
                    <div>
                        {{ $assignments->links() }}
                    </div>
                @endif
            </div>

            <div class="col-lg-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-info-circle mr-2"></i>{{ __('general.info') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>{{ __('general.total_assignments_info') }}</span>
                                <strong>{{ $assignments->total() }}</strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>{{ __('general.has_submissions') }}:</span>
                                <strong>{{ $assignments->where('submissions_count', '>', 0)->count() }}</strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>{{ __('general.no_submissions') }}:</span>
                                <strong>{{ $assignments->where('submissions_count', 0)->count() }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-teacher>
