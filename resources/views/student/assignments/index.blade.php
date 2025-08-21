<x-layouts.dash-student active="assignments">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-journal-text mr-2"></i>{{ __('general.assignment_list') }}
                    </h4>
                    <p class="text-muted mb-0">{{ __('general.assignments_to_complete') }}</p>
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
                        <label class="form-label">{{ __('general.status') }}</label>
                        <select class="form-control" wire:model.live="filterStatus">
                            <option value="all">{{ __('general.all') }}</option>
                            <option value="upcoming">{{ __('general.upcoming') }}</option>
                            <option value="overdue">{{ __('general.overdue') }}</option>
                            <option value="completed">{{ __('general.completed') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">{{ __('general.classroom') }}</label>
                        <select class="form-control" wire:model.live="filterClassroom">
                            <option value="">{{ __('general.all') }}</option>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">{{ __('general.teacher') }}</label>
                        <select class="form-control" wire:model.live="filterTeacher">
                            <option value="">{{ __('general.all') }}</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">{{ __('general.assignment_type') }}</label>
                        <select class="form-control" wire:model.live="filterType">
                            <option value="">{{ __('general.all') }}</option>
                            <option value="text">{{ __('general.text') }}</option>
                            <option value="essay">{{ __('general.essay') }}</option>
                            <option value="image">{{ __('general.image') }}</option>
                            <option value="audio">{{ __('general.audio') }}</option>
                            <option value="video">{{ __('general.video') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        @livewire('student.assignments.navigation')

        <!-- Assignments List -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-list-ul mr-2"></i>{{ __('general.assignment_list') }}
                </h6>
            </div>
            <div class="card-body">
                @if ($assignments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('general.title') }}</th>
                                    <th>{{ __('general.classroom') }}</th>
                                    <th>{{ __('general.teacher') }}</th>
                                    <th>{{ __('general.assignment_type') }}</th>
                                    <th>{{ __('general.deadline') }}</th>
                                    <th>{{ __('general.status') }}</th>
                                    <th>{{ __('general.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assignments as $assignment)
                                    <tr>
                                        <td>
                                            <div class="fw-medium">
                                                <a href="{{ route('student.assignments.show', $assignment->id) }}"
                                                    class="text-decoration-none text-dark hover:text-primary">
                                                    {{ $assignment->title }}
                                                </a>
                                            </div>
                                            @if ($assignment->description)
                                                <small
                                                    class="text-muted">{{ Str::limit($assignment->description, 80) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-info">{{ $assignment->classroom?->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            @if ($assignment->classroom?->teachers?->count())
                                                @foreach ($assignment->classroom->teachers as $teacher)
                                                    <span class="badge bg-secondary">{{ $teacher->name }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">{{ __('general.no_teacher') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($assignment->types)
                                                @foreach ($assignment->types as $type)
                                                    <span class="badge bg-primary mr-1">
                                                        @switch($type)
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
                                                                {{ $type }}
                                                        @endswitch
                                                    </span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $assignment->deadline->format('d/m/Y H:i') }}
                                            </div>
                                            <small
                                                class="text-muted">{{ $assignment->deadline->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            @php
                                                $status = $this->isCompleted($assignment)
                                                    ? 'completed'
                                                    : ($this->isOverdue($assignment)
                                                        ? 'overdue'
                                                        : 'upcoming');
                                            @endphp
                                            @if ($status === 'completed')
                                                <span class="badge bg-success">{{ __('general.completed') }}</span>
                                            @elseif($status === 'overdue')
                                                <span class="badge bg-danger">{{ __('general.overdue') }}</span>
                                            @else
                                                <span class="badge bg-warning text-dark">{{ __('general.need_to_do') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($this->canSubmit($assignment))
                                                <a href="{{ route('student.assignments.submit', $assignment->id) }}"
                                                    class="btn btn-sm btn-outline-primary" title="{{ __('general.do_assignment') }}">
                                                    <i class="bi bi-pencil"></i> {{ __('general.do_assignment') }}
                                                </a>
                                            @elseif($this->isCompleted($assignment))
                                                <a href="{{ route('student.assignments.show', $assignment->id) }}"
                                                    class="btn btn-sm btn-outline-success" title="{{ __('general.view_submission') }}">
                                                    <i class="bi bi-eye"></i> {{ __('general.view_submission') }}
                                                </a>
                                            @else
                                                <span class="text-muted">{{ __('general.overdue_status') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $assignments->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-journal-x fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('general.no_assignments') }}</h5>
                        <p class="text-muted">{{ __('general.no_assignments_filter') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.dash-student>
