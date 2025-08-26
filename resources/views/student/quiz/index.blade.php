<x-layouts.dash-student active="quizzes">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-clipboard-check-fill mr-2"></i>{{ __('general.quiz_list') }}
                    </h4>
                    <p class="text-muted mb-0">{{ __('general.quizzes_to_complete') }}</p>
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
                    <div class="col-md-2">
                        <label class="form-label">{{ __('general.classroom') }}</label>
                        <select class="form-control" wire:model.live="filterClass">
                            <option value="">{{ __('general.all_classes') }}</option>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">{{ __('general.status') }}</label>
                        <select class="form-control" wire:model.live="filterStatus">
                            <option value="">{{ __('general.all') }}</option>
                            <option value="active">{{ __('general.active') }}</option>
                            <option value="expired">{{ __('general.expired') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">{{ __('general.submission_status') }}</label>
                        <select class="form-control" wire:model.live="filterSubmissionStatus">
                            <option value="">{{ __('general.all') }}</option>
                            <option value="not_started">{{ __('general.not_started') }}</option>
                            <option value="in_progress">{{ __('general.in_progress') }}</option>
                            <option value="submitted">{{ __('general.submitted') }}</option>
                            <option value="completed">{{ __('general.completed') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" wire:click="resetFilters">
                            <i class="bi bi-arrow-clockwise mr-2"></i>{{ __('general.reset') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quiz List -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-list-ul mr-2"></i>{{ __('general.quiz_list') }}
                </h6>
            </div>
            <div class="card-body">
                @if ($quizzes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width: 200px;">{{ __('general.title') }}</th>
                                    <th>{{ __('general.classroom') }}</th>
                                    <th>{{ __('general.question_count') }}</th>
                                    <th>{{ __('general.time_limit') }}</th>
                                    <th>{{ __('general.deadline') }}</th>
                                    <th>{{ __('general.status') }}</th>
                                    <th>{{ __('general.submission_status') }}</th>
                                    <th>{{ __('general.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quizzes as $quiz)
                                    @php
                                        $result = $quizResults[$quiz->id] ?? null;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="fw-medium">{{ $quiz->title }}</div>
                                            @if ($quiz->description)
                                                <small
                                                    class="text-muted">{{ Str::limit($quiz->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $quiz->classroom->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $quiz->getQuestionCount() }}</span>
                                        </td>
                                        <td>
                                            @if ($quiz->time_limit)
                                                <span class="badge bg-warning text-dark">{{ $quiz->time_limit }}
                                                    {{ __('general.minutes') }}</span>
                                            @else
                                                <span class="text-muted">{{ __('general.no_limit') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($quiz->deadline)
                                                <div class="fw-medium">{{ $quiz->deadline->format('d/m/Y H:i') }}</div>
                                                <small class="text-muted">{{ $quiz->deadline->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">{{ __('general.no_deadline') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($quiz->isExpired())
                                                <span class="badge bg-danger">{{ __('general.expired') }}</span>
                                            @else
                                                <span class="badge bg-success">{{ __('general.active') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($result)
                                                <span class="badge bg-primary">{{ __('general.done') }}</span>
                                                @if ($result->submitted_at)
                                                    <span class="badge bg-success">{{ __('general.submitted') }}</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">{{ __('general.not_submitted') }}</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">{{ __('general.not_done') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($result && $result->submitted_at)
                                                <a href="{{ route('student.quizzes.review', ['quizId' => $quiz->id]) }}"
                                                    class="btn btn-sm btn-outline-info" title="{{ __('general.view_review') }}">
                                                    <i class="bi bi-eye"></i> {{ __('general.view_review') }}
                                                </a>
                                            @elseif ($quiz->isExpired())
                                                <span class="text-muted">{{ __('general.expired_status') }}</span>
                                            @else
                                                <a href="{{ route('student.quizzes.do', $quiz) }}"
                                                    class="btn btn-sm btn-outline-primary" title="{{ __('general.do_quiz') }}">
                                                    <i class="bi bi-pencil"></i> {{ __('general.do_quiz') }}
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div>
                        @if ($totalQuizzes > $perPage)
                            <nav aria-label="Quiz pagination">
                                <ul class="pagination">
                                    @if ($currentPage > 1)
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="?page={{ $currentPage - 1 }}{{ $search ? '&search=' . $search : '' }}{{ $filterClass ? '&filterClass=' . $filterClass : '' }}{{ $filterStatus ? '&filterStatus=' . $filterStatus : '' }}{{ $filterSubmissionStatus ? '&filterSubmissionStatus=' . $filterSubmissionStatus : '' }}">
                                                <i class="bi bi-chevron-left"></i>
                                            </a>
                                        </li>
                                    @endif

                                    @php
                                        $totalPages = ceil($totalQuizzes / $perPage);
                                        $startPage = max(1, $currentPage - 2);
                                        $endPage = min($totalPages, $currentPage + 2);
                                    @endphp

                                    @for ($i = $startPage; $i <= $endPage; $i++)
                                        <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                            <a class="page-link"
                                                href="?page={{ $i }}{{ $search ? '&search=' . $search : '' }}{{ $filterClass ? '&filterClass=' . $filterClass : '' }}{{ $filterStatus ? '&filterStatus=' . $filterStatus : '' }}{{ $filterSubmissionStatus ? '&filterSubmissionStatus=' . $filterSubmissionStatus : '' }}">
                                                {{ $i }}
                                            </a>
                                        </li>
                                    @endfor

                                    @if ($currentPage < $totalPages)
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="?page={{ $currentPage + 1 }}{{ $search ? '&search=' . $search : '' }}{{ $filterClass ? '&filterClass=' . $filterClass : '' }}{{ $filterStatus ? '&filterStatus=' . $filterStatus : '' }}{{ $filterSubmissionStatus ? '&filterSubmissionStatus=' . $filterSubmissionStatus : '' }}">
                                                <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        @endif
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-journal-x fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('general.no_quizzes') }}</h5>
                        <p class="text-muted">
                            @if ($search || $filterClass || $filterStatus || $filterSubmissionStatus)
                                {{ __('general.no_quizzes_filter') }}
                            @else
                                {{ __('general.no_quizzes_yet') }}
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @push('styles')
    @endpush
</x-layouts.dash-student>
