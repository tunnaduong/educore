<x-layouts.dash-teacher active="quizzes">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-patch-question mr-2"></i>{{ __('general.manage_quizzes') }}
                    </h4>
                    <p class="text-muted mb-0">{{ __('general.list_quizzes_for_your_classes') }}</p>
                </div>
                <a href="{{ route('teacher.quizzes.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle mr-2"></i>{{ __('general.create_new_quiz') }}
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.search') }}</label>
                        <input type="text" class="form-control" wire:model.live="search"
                            placeholder="{{ __('general.search_by_title_or_description') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.classroom') }}</label>
                        <select class="form-control" wire:model.live="filterClass">
                            <option value="">{{ __('general.all_classes') }}</option>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.status') }}</label>
                        <select class="form-control" wire:model.live="filterStatus">
                            <option value="">{{ __('general.all') }}</option>
                            <option value="active">{{ __('general.active') }}</option>
                            <option value="expired">{{ __('general.expired') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" wire:click="$set('search', '')">
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
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('general.title') }}</th>
                                    <th>{{ __('general.classroom') }}</th>
                                    <th>{{ __('general.question_count') }}</th>
                                    <th>{{ __('general.time_limit') }}</th>
                                    <th>{{ __('general.deadline') }}</th>
                                    <th>{{ __('general.status') }}</th>
                                    <th>{{ __('general.created_at') }}</th>
                                    <th>{{ __('general.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quizzes as $quiz)
                                    <tr>
                                        <td>
                                            <div class="fw-medium">{{ $quiz->title }}</div>
                                            @if ($quiz->description)
                                                <small
                                                    class="text-muted">{{ Str::limit($quiz->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $quiz->classroom->name ?? __('general.not_available') }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $quiz->getQuestionCount() }}</span>
                                        </td>
                                        <td>
                                            @if ($quiz->time_limit)
                                                <span class="badge bg-warning text-dark">{{ $quiz->time_limit }} {{ __('general.minutes') }}</span>
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
                                                <span class="badge bg-danger">{{ __('general.expired_status') }}</span>
                                            @else
                                                <span class="badge bg-success">{{ __('general.active') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $quiz->created_at->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $quiz->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('teacher.quizzes.show', $quiz) }}"
                                                    class="btn btn-sm btn-outline-primary" title="{{ __('general.view_details') }}">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('teacher.quizzes.edit', $quiz) }}"
                                                    class="btn btn-sm btn-outline-warning" title="{{ __('general.edit') }}">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ route('teacher.quizzes.show', $quiz) }}"
                                                    class="btn btn-sm btn-outline-info" title="{{ __('general.view_results') }}">
                                                    <i class="bi bi-graph-up"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    title="{{ __('general.delete') }}" wire:click="deleteQuiz({{ $quiz->id }})"
                                                    wire:confirm="{{ __('general.confirm_delete_quiz') }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div>
                        {{ $quizzes->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-journal-x fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('general.no_quizzes') }}</h5>
                        <a href="{{ route('teacher.quizzes.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle mr-2"></i>{{ __('general.create_quiz') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Flash Message -->
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert">
                <i class="bi bi-check-circle mr-2"></i>{{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert">
                <i class="bi bi-exclamation-triangle mr-2"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>
</x-layouts.dash-teacher>
