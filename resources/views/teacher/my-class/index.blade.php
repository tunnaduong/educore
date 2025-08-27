<x-layouts.dash-teacher active="my-class">
    @include('components.language')
    
    <div class="container">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0 fs-4 text-primary">
                    <i class="bi bi-diagram-3-fill text-primary mr-2"></i>
                    {{ __('general.my_classes') }}
                </h4>
                <p class="text-muted mb-0">{{ __('general.manage_your_teaching_classes') }}</p>
            </div>
            <div class="col-md-6 text-end">
                <div class="d-flex justify-content-end gap-2">
                    <div class="input-group" style="max-width: 300px;">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" wire:model.live="search" class="form-control"
                            placeholder="{{ __('general.search_classes_placeholder') }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-diagram-3-fill text-primary" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-1">{{ $classrooms->total() }}</h4>
                        <p class="text-muted mb-0">{{ __('general.total_classes') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-people-fill text-success" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-1">
                            {{ $classrooms->sum(function ($classroom) {return $classroom->students->count();}) }}</h4>
                        <p class="text-muted mb-0">{{ __('general.total_students') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-book text-info" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-1">
                            {{ $classrooms->sum(function ($classroom) {return $classroom->lessons->count();}) }}</h4>
                        <p class="text-muted mb-0">{{ __('general.total_lessons') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-journal-text text-warning" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-1">
                            {{ $classrooms->sum(function ($classroom) {return $classroom->assignments->count();}) }}
                        </h4>
                        <p class="text-muted mb-0">{{ __('general.total_assignments') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Classrooms List -->
        <div class="row">
            @forelse($classrooms as $classroom)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-primary text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">{{ $classroom->name }}</h6>
                                <span class="badge bg-light text-dark">{{ $classroom->students->count() }} {{ __('general.students') }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-3">{{ Str::limit($classroom->description, 100) }}</p>

                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <div class="border-end">
                                        <h6 class="mb-1 text-primary">{{ $classroom->lessons->count() }}</h6>
                                        <small class="text-muted">{{ __('general.lessons_label') }}</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-end">
                                        <h6 class="mb-1 text-success">{{ $classroom->assignments->count() }}</h6>
                                        <small class="text-muted">{{ __('general.assignments') }}</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <h6 class="mb-1 text-info">{{ $classroom->students->count() }}</h6>
                                    <small class="text-muted">{{ __('general.students') }}</small>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('teacher.my-class.show', $classroom->id) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye mr-1"></i>
                                    {{ __('general.view_details') }}
                                </a>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <small class="text-muted">
                                <i class="bi bi-calendar mr-1"></i>
                                {{ __('general.created_date') }}: {{ $classroom->created_at->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bi bi-diagram-3 text-muted" style="font-size: 4rem;"></i>
                        <h5 class="mt-3 text-muted">{{ __('general.no_classes_yet') }}</h5>
                        <p class="text-muted">{{ __('general.no_assigned_classes') }}</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($classrooms->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $classrooms->links() }}
            </div>
        @endif
    </div>

    <!-- Classroom Details Modal -->
    @if ($showClassroomDetails && $selectedClassroom)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-diagram-3-fill mr-2"></i>
                            {{ $selectedClassroom->name }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                            wire:click="closeClassroomDetails"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-info-circle mr-2"></i>
                                    {{ __('general.classroom_information') }}
                                </h6>
                                <p><strong>{{ __('general.description') }}:</strong> {{ $selectedClassroom->description }}</p>
                                <p><strong>{{ __('general.created_date') }}:</strong> {{ $selectedClassroom->created_at->format('d/m/Y H:i') }}
                                </p>
                                <p><strong>{{ __('general.student_count') }}:</strong> {{ $selectedClassroom->students->count() }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-success mb-3">
                                    <i class="bi bi-graph-up mr-2"></i>
                                    {{ __('general.quick_statistics') }}
                                </h6>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="border rounded p-3">
                                            <h4 class="text-primary mb-1">{{ $selectedClassroom->lessons->count() }}
                                            </h4>
                                            <small class="text-muted">{{ __('general.lessons_label') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="border rounded p-3">
                                            <h4 class="text-success mb-1">
                                                {{ $selectedClassroom->assignments->count() }}</h4>
                                            <small class="text-muted">{{ __('general.assignments') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Students List -->
                        <div class="mt-4">
                            <h6 class="text-info mb-3">
                                <i class="bi bi-people mr-2"></i>
                                {{ __('general.student_list') }} ({{ $selectedClassroom->students->count() }})
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ __('general.no') }}</th>
                                            <th>{{ __('general.full_name') }}</th>
                                            <th>Email</th>
                                            <th>{{ __('general.joined_at') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($selectedClassroom->students as $index => $student)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $student->name }}</td>
                                                <td>{{ $student->email }}</td>
                                                <td>{{ $student->pivot->created_at->format('d/m/Y') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">{{ __('general.no_students_yet') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Recent Lessons -->
                        <div class="mt-4">
                            <h6 class="text-warning mb-3">
                                <i class="bi bi-book mr-2"></i>
                                {{ __('general.recent_lessons') }}
                            </h6>
                            @forelse($selectedClassroom->lessons->take(3) as $lesson)
                                <div class="card mb-2">
                                    <div class="card-body py-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $lesson->title }}</h6>
                                                <small
                                                    class="text-muted">{{ Str::limit($lesson->description, 50) }}</small>
                                            </div>
                                            <small
                                                class="text-muted">{{ $lesson->created_at->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted text-center">{{ __('general.no_lessons_yet') }}</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="closeClassroomDetails">{{ __('general.close') }}</button>
                        <a href="#" class="btn btn-primary">
                            <i class="bi bi-pencil mr-1"></i>
                            {{ __('general.edit_class') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</x-layouts.dash-teacher>
