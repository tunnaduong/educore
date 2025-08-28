<x-layouts.dash-admin active="quizzes">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-journal-check mr-2"></i>{{ __('views.quiz_management_title') }}
                    </h4>
                    <p class="text-muted mb-0">{{ __('views.quiz_management_description') }}</p>
                </div>
                <a href="{{ route('quizzes.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle mr-2"></i>{{ __('views.create_new_quiz') }}
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('views.search') }}</label>
                        <input type="text" class="form-control" wire:model.live="search"
                            placeholder="{{ __('views.search_quiz_placeholder') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('views.classroom') }}</label>
                        <select class="form-control" wire:model.live="filterClass">
                            <option value="">{{ __('views.all_classes') }}</option>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('views.status') }}</label>
                        <select class="form-control" wire:model.live="filterStatus">
                            <option value="">{{ __('views.all') }}</option>
                            <option value="active">{{ __('views.active_status') }}</option>
                            <option value="expired">{{ __('views.overdue_status') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" wire:click="$set('search', '')">
                            <i class="bi bi-arrow-clockwise mr-2"></i>{{ __('views.reset') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quiz List -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-list-ul mr-2"></i>{{ __('views.quiz_list') }}
                </h6>
            </div>
            <div class="card-body">
                @if ($quizzes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('views.title') }}</th>
                                    <th>{{ __('views.classroom') }}</th>
                                    <th>{{ __('views.question_count') }}</th>
                                    <th>{{ __('views.time_limit') }}</th>
                                    <th>{{ __('views.deadline') }}</th>
                                    <th>{{ __('views.status') }}</th>
                                    <th>{{ __('views.created_at') }}</th>
                                    <th>{{ __('views.actions') }}</th>
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
                                            <span class="badge bg-info">{{ $quiz->classroom->name ?? __('views.not_available') }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $quiz->getQuestionCount() }}</span>
                                        </td>
                                        <td>
                                            @if ($quiz->time_limit)
                                                <span class="badge bg-warning text-dark">{{ $quiz->time_limit }}
                                                    {{ __('views.minutes') }}</span>
                                            @else
                                                <span class="text-muted">{{ __('views.unlimited') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($quiz->deadline)
                                                <div class="fw-medium">{{ $quiz->deadline->format('d/m/Y H:i') }}</div>
                                                <small class="text-muted">{{ $quiz->deadline->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">{{ __('views.no_deadline') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($quiz->isExpired())
                                                <span class="badge bg-danger">{{ __('views.overdue_status') }}</span>
                                            @else
                                                <span class="badge bg-success">{{ __('views.active_status') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $quiz->created_at->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $quiz->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('quizzes.show', $quiz) }}"
                                                    class="btn btn-sm btn-outline-primary" title="{{ __('views.view_details') }}">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('quizzes.edit', $quiz) }}"
                                                    class="btn btn-sm btn-outline-warning" title="{{ __('views.edit') }}">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ route('quizzes.results', $quiz) }}"
                                                    class="btn btn-sm btn-outline-info" title="{{ __('views.view_results') }}">
                                                    <i class="bi bi-graph-up"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    title="{{ __('views.delete') }}" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $quiz->id }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Delete Confirmation Modal -->
                                    <div class="modal fade" id="deleteModal{{ $quiz->id }}" tabindex="-1"
                                        aria-labelledby="deleteModalLabel{{ $quiz->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $quiz->id }}">
                                                        {{ __('views.confirm_delete_quiz_title') }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ __('views.confirm_delete_quiz', ['title' => $quiz->title]) }}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">{{ __('views.cancel') }}</button>
                                                    <button type="button" class="btn btn-danger"
                                                        wire:click="deleteQuiz({{ $quiz->id }})"
                                                        data-dismiss="modal">{{ __('views.delete') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    {{ $quizzes->links('vendor.pagination.bootstrap-5') }}
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-journal-x fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('views.no_quizzes') }}</h5>
                        <p class="text-muted">{{ __('views.create_first_quiz') }}</p>
                        <a href="{{ route('quizzes.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle mr-2"></i>{{ __('views.create_quiz') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Flash Message -->
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3"
                role="alert">
                <i class="bi bi-check-circle mr-2"></i>{{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>
</x-layouts.dash-admin>
