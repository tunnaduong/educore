<x-layouts.dash-student active="assignments">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('student.assignments.overview') }}"
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>{{ __('general.back') }}
            </a>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-folder-check mr-2"></i>{{ __('general.submitted_assignments') }}
                    </h4>
                    <p class="text-muted mb-0">{{ __('general.submitted_assignments_list') }}</p>
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
                        <label class="form-label">{{ __('general.grading_status') }}</label>
                        <select class="form-control" wire:model.live="filterGraded">
                            <option value="">{{ __('general.all') }}</option>
                            <option value="graded">{{ __('general.graded') }}</option>
                            <option value="ungraded">{{ __('general.ungraded') }}</option>
                        </select>
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
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" wire:click="$set('search', '')">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        @if ($submissions->count() > 0)
            <div class="row my-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="bi bi-file-earmark-check fs-1 mb-2"></i>
                            <h5 class="card-title">{{ $totalSubmissions }}</h5>
                            <p class="card-text">{{ __('general.total_submissions') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="bi bi-check-circle fs-1 mb-2"></i>
                            <h5 class="card-title">{{ $gradedSubmissions }}</h5>
                            <p class="card-text">{{ __('general.graded') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body text-center">
                            <i class="bi bi-hourglass-split fs-1 mb-2"></i>
                            <h5 class="card-title">{{ $ungradedSubmissions }}</h5>
                            <p class="card-text">{{ __('general.pending_grading') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <i class="bi bi-star fs-1 mb-2"></i>
                            <h5 class="card-title">{{ number_format($averageScore, 1) }}</h5>
                            <p class="card-text">{{ __('general.average_score') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Submissions List -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-list-ul mr-2"></i>{{ __('general.submission_list') }}
                </h6>
            </div>
            <div class="card-body">
                @if ($submissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('general.assignment') }}</th>
                                    <th>{{ __('general.classroom') }}</th>
                                    <th>{{ __('general.submission_type') }}</th>
                                    <th>{{ __('general.submission_date') }}</th>
                                    <th>{{ __('general.score') }}</th>
                                    <th>{{ __('general.status') }}</th>
                                    <th>{{ __('general.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($submissions as $submission)
                                    <tr>
                                        <td>
                                            <div class="fw-medium">{{ $submission->assignment->title }}</div>
                                            @if ($submission->assignment->description)
                                                <small
                                                    class="text-muted">{{ Str::limit($submission->assignment->description, 60) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-info">{{ $submission->assignment?->classroom?->name ?? __('general.not_available') }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                @switch($submission->submission_type)
                                                    @case('text')
                                                        {{ __('general.text') }}
                                                    @break

                                                    @case('essay')
                                                        {{ __('general.essay') }}
                                                    @break

                                                    @case('image')
                                                        {{ __('general.image') }}
                                                    @break

                                                    @case('audio')
                                                        {{ __('general.audio') }}
                                                    @break

                                                    @case('video')
                                                        {{ __('general.video') }}
                                                    @break

                                                    @default
                                                        {{ $submission->submission_type }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $submission->submitted_at->format('d/m/Y H:i') }}
                                            </div>
                                            <small
                                                class="text-muted">{{ $submission->submitted_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            @if ($submission->score !== null)
                                                <div class="fw-bold text-primary">{{ $submission->score }}/10</div>
                                                @if ($submission->feedback)
                                                    <small
                                                        class="text-muted">{{ Str::limit($submission->feedback, 30) }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted">{{ __('general.ungraded') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($submission->score !== null)
                                                <span class="badge bg-success">{{ __('general.graded') }}</span>
                                            @else
                                                <span class="badge bg-warning text-dark">{{ __('general.pending_grading') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('student.assignments.show', $submission->assignment->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="{{ __('general.view_details') }}">
                                                <i class="bi bi-eye"></i> {{ __('general.view') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div>
                        {{ $submissions->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-folder-x fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('general.no_submitted_assignments') }}</h5>
                        <p class="text-muted">{{ __('general.no_submitted_assignments_filter') }}</p>
                        <a href="{{ route('student.assignments.overview') }}" class="btn btn-primary">
                            <i class="bi bi-journal-text mr-2"></i>{{ __('general.assignment_list') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.dash-student>
