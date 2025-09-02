<x-layouts.dash-teacher active="my-class">
    @include('components.language')
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('teacher.my-class.index') }}" class="text-decoration-none">
                                <i class="bi bi-arrow-left mr-1"></i>
                                {{ __('general.my_class') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $classroom->name }}</li>
                    </ol>
                </nav>
                <h2 class="mb-0">
                    <i class="bi bi-diagram-3-fill text-primary mr-2"></i>
                    {{ $classroom->name }}
                </h2>
                <p class="text-muted mb-0">{{ $classroom->description }}</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('teacher.attendance.take', $classroom) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-calendar-check mr-1"></i>
                        {{ __('general.new_attendance') }}
                    </a>
                    <a href="{{ route('teacher.lessons.create', ['classroom_id' => $classroom->id]) }}"
                        class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-plus-circle mr-1"></i>
                        {{ __('general.add_lesson') }}
                    </a>
                    <a href="{{ route('teacher.assignments.create', ['classroom_id' => $classroom->id]) }}"
                        class="btn btn-outline-success btn-sm">
                        <i class="bi bi-plus-circle mr-1"></i>
                        {{ __('general.add_assignment') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-people-fill text-success" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-1">{{ $classroom->students->count() }}</h4>
                        <p class="text-muted mb-0">{{ __('general.students') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-book-fill text-info" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-1">{{ $classroom->lessons->count() }}</h4>
                        <p class="text-muted mb-0">{{ __('general.lessons_label') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-journal-text text-warning" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-1">{{ $classroom->assignments->count() }}</h4>
                        <p class="text-muted mb-0">{{ __('general.assignments') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-calendar-check text-primary" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-1">{{ $classroom->attendances->count() }}</h4>
                        <p class="text-muted mb-0">{{ __('general.sessions') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <ul class="nav nav-tabs card-header-tabs" id="classroomTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'overview' ? 'active' : '' }}"
                            wire:click="setActiveTab('overview')" type="button">
                            <i class="bi bi-house mr-1"></i>
                            {{ __('general.overview') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'students' ? 'active' : '' }}"
                            wire:click="setActiveTab('students')" type="button">
                            <i class="bi bi-people mr-1"></i>
                            {{ __('general.students') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'lessons' ? 'active' : '' }}"
                            wire:click="setActiveTab('lessons')" type="button">
                            <i class="bi bi-book mr-1"></i>
                            {{ __('general.lessons_label') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'assignments' ? 'active' : '' }}"
                            wire:click="setActiveTab('assignments')" type="button">
                            <i class="bi bi-journal-text mr-1"></i>
                            {{ __('general.assignments') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'attendance' ? 'active' : '' }}"
                            wire:click="setActiveTab('attendance')" type="button">
                            <i class="bi bi-calendar-check mr-1"></i>
                            {{ __('general.attendance') }}
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <!-- Overview Tab -->
                @if ($activeTab === 'overview')
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-info-circle mr-2"></i>
                                {{ __('general.classroom_details') }}
                            </h6>
                            <div class="mb-3">
                                <strong>{{ __('general.classroom_name') }}:</strong> {{ $classroom->name }}
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('general.description') }}:</strong> {{ $classroom->description }}
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('general.created_date') }}:</strong> {{ $classroom->created_at->format('d/m/Y H:i') }}
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('general.status') }}:</strong>
                                <span class="badge bg-success">{{ __('general.active') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success mb-3">
                                <i class="bi bi-graph-up mr-2"></i>
                                {{ __('general.quick_statistics') }}
                            </h6>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="border rounded p-3 text-center">
                                        <h4 class="text-primary mb-1">{{ $classroom->lessons->count() }}</h4>
                                        <small class="text-muted">{{ __('general.lessons_label') }}</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="border rounded p-3 text-center">
                                        <h4 class="text-success mb-1">{{ $classroom->assignments->count() }}</h4>
                                        <small class="text-muted">{{ __('general.assignments') }}</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="border rounded p-3 text-center">
                                        <h4 class="text-info mb-1">{{ $classroom->students->count() }}</h4>
                                        <small class="text-muted">{{ __('general.students') }}</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="border rounded p-3 text-center">
                                        <h4 class="text-warning mb-1">{{ $classroom->attendances->count() }}</h4>
                                        <small class="text-muted">{{ __('general.sessions') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Recent Activities -->
                    <div class="mt-4">
                        <h6 class="text-info mb-3">
                            <i class="bi bi-clock-history mr-2"></i>
                            {{ __('general.recent_activities') }}
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-warning mb-2">{{ __('general.latest_lessons') }}</h6>
                                @forelse($classroom->lessons->take(3) as $lesson)
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
                                    <p class="text-muted">{{ __('general.no_lessons_yet') }}</p>
                                @endforelse
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-success mb-2">{{ __('general.recent_assignments') }}</h6>
                                @forelse($classroom->assignments->take(3) as $assignment)
                                    <div class="card mb-2">
                                        <div class="card-body py-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">{{ $assignment->title }}</h6>
                                                    <small
                                                        class="text-muted">{{ Str::limit($assignment->description, 50) }}</small>
                                                </div>
                                                <small
                                                    class="text-muted">{{ $assignment->created_at->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">{{ __('general.no_assignments_yet') }}</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Students Tab -->
                @if ($activeTab === 'students')
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-info mb-0">
                            <i class="bi bi-people mr-2"></i>
                            {{ __('general.student_list') }} ({{ $classroom->students->count() }})
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('general.no') }}</th>
                                    <th>{{ __('general.full_name') }}</th>
                                    <th>{{ __('general.email') }}</th>
                                    <th>{{ __('general.joined_at') }}</th>
                                    <th>{{ __('general.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($classroom->students as $index => $student)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-2"
                                                    style="width: 32px; height: 32px; font-size: 14px;">
                                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                                </div>
                                                {{ $student->name }}
                                            </div>
                                        </td>
                                        <td>{{ $student->email }}</td>
                                        <td>{{ $student->pivot->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            @if ($student->status === 'active')
                                                <span class="badge bg-success">{{ __('general.active') }}</span>
                                            @elseif($student->status === 'paused')
                                                <span class="badge bg-warning">{{ __('general.paused') }}</span>
                                            @elseif($student->status === 'dropped')
                                                <span class="badge bg-danger">{{ __('general.dropped') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('general.undefined') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bi bi-people text-muted" style="font-size: 2rem;"></i>
                                            <p class="mt-2">{{ __('general.no_students_in_class') }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif

                <!-- Lessons Tab -->
                @if ($activeTab === 'lessons')
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-warning mb-0">
                            <i class="bi bi-book mr-2"></i>
                            {{ __('general.lesson_list') }} ({{ $classroom->lessons->count() }})
                        </h6>
                        <a href="{{ route('teacher.lessons.create', ['classroom_id' => $classroom->id]) }}"
                            class="btn btn-warning btn-sm">
                            <i class="bi bi-plus-circle mr-1"></i>
                            {{ __('general.add_lesson') }}
                        </a>
                    </div>
                    <div class="row">
                        @forelse($classroom->lessons as $lesson)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-header bg-warning text-white">
                                        <h6 class="mb-0">{{ $lesson->title }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted small">{{ Str::limit($lesson->description, 100) }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small
                                                class="text-muted">{{ $lesson->created_at->format('d/m/Y') }}</small>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('teacher.lessons.show', $lesson->id) }}"
                                                    class="btn btn-outline-warning">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('teacher.lessons.edit', $lesson->id) }}"
                                                    class="btn btn-outline-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center py-4">
                                    <i class="bi bi-book text-muted" style="font-size: 3rem;"></i>
                                    <p class="mt-2 text-muted">{{ __('general.no_lessons_yet') }}</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                @endif

                <!-- Assignments Tab -->
                @if ($activeTab === 'assignments')
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-success mb-0">
                            <i class="bi bi-journal-text mr-2"></i>
                            {{ __('general.assignment_list') }} ({{ $classroom->assignments->count() }})
                        </h6>
                        <a href="{{ route('teacher.assignments.create', ['classroom_id' => $classroom->id]) }}"
                            class="btn btn-success btn-sm">
                            <i class="bi bi-plus-circle mr-1"></i>
                            {{ __('general.add_assignment') }}
                        </a>
                    </div>
                    <div class="row">
                        @forelse($classroom->assignments as $assignment)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0">{{ $assignment->title }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted small">{{ Str::limit($assignment->description, 100) }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small
                                                class="text-muted">{{ $assignment->created_at->format('d/m/Y') }}</small>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('teacher.assignments.show', $assignment->id) }}"
                                                    class="btn btn-outline-success">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('teacher.assignments.edit', $assignment->id) }}"
                                                    class="btn btn-outline-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center py-4">
                                    <i class="bi bi-journal-text text-muted" style="font-size: 3rem;"></i>
                                    <p class="mt-2 text-muted">{{ __('general.no_assignments_yet') }}</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                @endif

                <!-- Attendance Tab -->
                @if ($activeTab === 'attendance')
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-primary mb-0">
                            <i class="bi bi-calendar-check mr-2"></i>
                            {{ __('general.attendance_history') }}
                        </h6>
                        <a href="{{ route('teacher.attendance.take', $classroom) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle mr-1"></i>
                            {{ __('general.new_attendance') }}
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('general.date') }}</th>
                                    <th>{{ __('general.session_label') }}</th>
                                    <th>{{ __('general.student_count') }}</th>
                                    <th>{{ __('general.present') }}</th>
                                    <th>{{ __('general.absent') }}</th>
                                    <th>{{ __('general.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendanceSessions as $session)
                                    <tr>
                                        <td>{{ $session['date']->format('d/m/Y') }}</td>
                                        <td>{{ __('general.session_label') }}</td>
                                        <td>{{ $classroom->students->count() }}</td>
                                        <td>
                                            <span class="badge bg-success">{{ $session['present_count'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger">{{ $session['absent_count'] }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('teacher.attendance.take', $classroom) }}?date={{ $session['date']->format('Y-m-d') }}" class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bi bi-calendar-check text-muted" style="font-size: 2rem;"></i>
                                            <p class="mt-2">{{ __('general.no_attendance_history_yet') }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.dash-teacher>
