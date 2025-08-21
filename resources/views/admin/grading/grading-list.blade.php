<x-layouts.dash-admin active="grading">
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
                            <span class="mb-0">{{ __('general.assignments_list') }}</span>
                        </div>
                        <div class="text-white-50 small">
                            {{ __('general.total_assignments', ['count' => $assignments->total()]) }}
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
                                                    <span class="fw-semibold">
                                                        <i class="bi bi-mortarboard mr-1"></i>
                                                        {{ $assignment->classroom?->name ?? '-' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="fw-medium">{{ $assignment->title }}</div>
                                                    @if ($assignment->description)
                                                        <small
                                                            class="text-muted">{{ Str::limit($assignment->description, 60) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-calendar3"></i>
                                                        {{ $assignment->deadline ? $assignment->deadline->format('d/m/Y H:i') : '-' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-info text-dark">
                                                        {{ $assignment->submissions_count }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($assignment->submissions_count > 0)
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check2-all"></i> {{ __('general.has_submission') }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">
                                                            <i class="bi bi-hourglass-split"></i> {{ __('general.no_submission') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-primary btn-sm px-3"
                                                        wire:click="selectAssignment({{ $assignment->id }})">
                                                        <i class="bi bi-pencil-square"></i> {{ __('general.grade_assignment') }}
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mx-3 mt-4">
                                {{ $assignments->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        @else
                            <div class="alert alert-info text-center m-0 py-4">
                                <i class="bi bi-info-circle fs-3"></i><br>
                                {{ __('general.no_assignments_match_filter') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-3 d-none d-lg-block">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-light">
                        <i class="bi bi-lightbulb mr-2"></i>{{ __('general.guide') }}
                    </div>
                    <div class="card-body small">
                        <ul class="mb-2 ps-3">
                            <li>{{ __('general.guide_only_your_classes') }}</li>
                            <li>{{ __('general.guide_click_grade') }}</li>
                            <li>{{ __('general.guide_submission_count') }}</li>
                            <li>{{ __('general.guide_submissions_first') }}</li>
                        </ul>
                        <div class="alert alert-info p-2 mb-0">
                            <i class="bi bi-info-circle"></i> {{ __('general.only_teachers_can_grade') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
