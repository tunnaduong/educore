<x-layouts.dash-teacher active="my-class">
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('teacher.my-class.index') }}" class="text-decoration-none">
                                <i class="bi bi-arrow-left mr-1"></i>
                                Lớp học của tôi
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
                    <a href="{{ route('teacher.attendance.take', $classroom) }}" wire:navigate
                        class="btn btn-primary btn-sm">
                        <i class="bi bi-calendar-check mr-1"></i>
                        Điểm danh
                    </a>
                    <button class="btn btn-outline-primary btn-sm" wire:click="showAddLessonModal">
                        <i class="bi bi-plus-circle mr-1"></i>
                        Thêm bài học
                    </button>
                    <button class="btn btn-outline-success btn-sm" wire:click="showAddAssignmentModal">
                        <i class="bi bi-plus-circle mr-1"></i>
                        Thêm bài tập
                    </button>
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
                        <p class="text-muted mb-0">Học sinh</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-book-fill text-info" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-1">{{ $classroom->lessons->count() }}</h4>
                        <p class="text-muted mb-0">Bài học</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-journal-text text-warning" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-1">{{ $classroom->assignments->count() }}</h4>
                        <p class="text-muted mb-0">Bài tập</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-calendar-check text-primary" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-1">{{ $classroom->attendances->count() }}</h4>
                        <p class="text-muted mb-0">Buổi học</p>
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
                            Tổng quan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'students' ? 'active' : '' }}"
                            wire:click="setActiveTab('students')" type="button">
                            <i class="bi bi-people mr-1"></i>
                            Học sinh
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'lessons' ? 'active' : '' }}"
                            wire:click="setActiveTab('lessons')" type="button">
                            <i class="bi bi-book mr-1"></i>
                            Bài học
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'assignments' ? 'active' : '' }}"
                            wire:click="setActiveTab('assignments')" type="button">
                            <i class="bi bi-journal-text mr-1"></i>
                            Bài tập
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'attendance' ? 'active' : '' }}"
                            wire:click="setActiveTab('attendance')" type="button">
                            <i class="bi bi-calendar-check mr-1"></i>
                            Điểm danh
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
                                Thông tin lớp học
                            </h6>
                            <div class="mb-3">
                                <strong>Tên lớp:</strong> {{ $classroom->name }}
                            </div>
                            <div class="mb-3">
                                <strong>Mô tả:</strong> {{ $classroom->description }}
                            </div>
                            <div class="mb-3">
                                <strong>Ngày tạo:</strong> {{ $classroom->created_at->format('d/m/Y H:i') }}
                            </div>
                            <div class="mb-3">
                                <strong>Trạng thái:</strong>
                                <span class="badge bg-success">Hoạt động</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success mb-3">
                                <i class="bi bi-graph-up mr-2"></i>
                                Thống kê nhanh
                            </h6>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="border rounded p-3 text-center">
                                        <h4 class="text-primary mb-1">{{ $classroom->lessons->count() }}</h4>
                                        <small class="text-muted">Bài học</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="border rounded p-3 text-center">
                                        <h4 class="text-success mb-1">{{ $classroom->assignments->count() }}</h4>
                                        <small class="text-muted">Bài tập</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="border rounded p-3 text-center">
                                        <h4 class="text-info mb-1">{{ $classroom->students->count() }}</h4>
                                        <small class="text-muted">Học sinh</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="border rounded p-3 text-center">
                                        <h4 class="text-warning mb-1">{{ $classroom->attendances->count() }}</h4>
                                        <small class="text-muted">Buổi học</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activities -->
                    <div class="mt-4">
                        <h6 class="text-info mb-3">
                            <i class="bi bi-clock-history mr-2"></i>
                            Hoạt động gần đây
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-warning mb-2">Bài học mới nhất</h6>
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
                                    <p class="text-muted">Chưa có bài học nào</p>
                                @endforelse
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-success mb-2">Bài tập mới nhất</h6>
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
                                    <p class="text-muted">Chưa có bài tập nào</p>
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
                            Danh sách học sinh ({{ $classroom->students->count() }})
                        </h6>
                        <button class="btn btn-primary btn-sm" wire:click="showAddStudentModal">
                            <i class="bi bi-plus-circle mr-1"></i>
                            Thêm học sinh
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Ngày tham gia</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
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
                                            <span class="badge bg-success">Hoạt động</span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bi bi-people text-muted" style="font-size: 2rem;"></i>
                                            <p class="mt-2">Chưa có học sinh nào trong lớp</p>
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
                            Danh sách bài học ({{ $classroom->lessons->count() }})
                        </h6>
                        <button class="btn btn-warning btn-sm" wire:click="showAddLessonModal">
                            <i class="bi bi-plus-circle mr-1"></i>
                            Thêm bài học
                        </button>
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
                                    <p class="mt-2 text-muted">Chưa có bài học nào</p>
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
                            Danh sách bài tập ({{ $classroom->assignments->count() }})
                        </h6>
                        <button class="btn btn-success btn-sm" wire:click="showAddAssignmentModal">
                            <i class="bi bi-plus-circle mr-1"></i>
                            Thêm bài tập
                        </button>
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
                                    <p class="mt-2 text-muted">Chưa có bài tập nào</p>
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
                            Lịch sử điểm danh
                        </h6>
                        <button class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle mr-1"></i>
                            Điểm danh mới
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Ngày</th>
                                    <th>Buổi học</th>
                                    <th>Số học sinh</th>
                                    <th>Có mặt</th>
                                    <th>Vắng</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($classroom->attendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->created_at->format('d/m/Y') }}</td>
                                        <td>Buổi {{ $attendance->session_number }}</td>
                                        <td>{{ $classroom->students->count() }}</td>
                                        <td>
                                            <span
                                                class="badge bg-success">{{ $attendance->present_count ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger">{{ $attendance->absent_count ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bi bi-calendar-check text-muted" style="font-size: 2rem;"></i>
                                            <p class="mt-2">Chưa có lịch sử điểm danh</p>
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

    <!-- Add Student Modal -->
    @if ($showAddStudentModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm học sinh vào lớp</h5>
                        <button type="button" class="btn-close" wire:click="closeModals"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Chức năng này sẽ được phát triển sau.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModals">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Add Lesson Modal -->
    @if ($showAddLessonModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm bài học mới</h5>
                        <button type="button" class="btn-close" wire:click="closeModals"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Chức năng này sẽ được phát triển sau.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModals">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Add Assignment Modal -->
    @if ($showAddAssignmentModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm bài tập mới</h5>
                        <button type="button" class="btn-close" wire:click="closeModals"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Chức năng này sẽ được phát triển sau.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModals">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</x-layouts.dash-teacher>
