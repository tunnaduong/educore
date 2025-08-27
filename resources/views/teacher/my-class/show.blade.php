<x-layouts.dash-teacher active="my-class">
    @include('components.language')
    @php
        $t = function ($vi, $en, $zh) {
            $l = app()->getLocale();
            return $l === 'vi' ? $vi : ($l === 'zh' ? $zh : $en);
        };
    @endphp
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('teacher.my-class.index') }}" class="text-decoration-none">
                                <i class="bi bi-arrow-left mr-1"></i>
                                {{ $t('Lớp học của tôi', 'My Classes', '我的课堂') }}
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
                        {{ $t('Điểm danh', 'Take Attendance', '点名') }}
                    </a>
                    <a href="{{ route('teacher.lessons.create', ['classroom_id' => $classroom->id]) }}"
                        class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-plus-circle mr-1"></i>
                        {{ $t('Thêm bài học', 'Add Lesson', '新增课程') }}
                    </a>
                    <a href="{{ route('teacher.assignments.create', ['classroom_id' => $classroom->id]) }}"
                        class="btn btn-outline-success btn-sm">
                        <i class="bi bi-plus-circle mr-1"></i>
                        {{ $t('Thêm bài tập', 'Add Assignment', '新增作业') }}
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
                        <p class="text-muted mb-0">{{ $t('Học sinh', 'Students', '学生') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-book-fill text-info" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-1">{{ $classroom->lessons->count() }}</h4>
                        <p class="text-muted mb-0">{{ $t('Bài học', 'Lessons', '课程') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-journal-text text-warning" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-1">{{ $classroom->assignments->count() }}</h4>
                        <p class="text-muted mb-0">{{ $t('Bài tập', 'Assignments', '作业') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-calendar-check text-primary" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-1">{{ $classroom->attendances->count() }}</h4>
                        <p class="text-muted mb-0">{{ $t('Buổi học', 'Sessions', '课次') }}</p>
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
                            {{ $t('Tổng quan', 'Overview', '总览') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'students' ? 'active' : '' }}"
                            wire:click="setActiveTab('students')" type="button">
                            <i class="bi bi-people mr-1"></i>
                            {{ $t('Học sinh', 'Students', '学生') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'lessons' ? 'active' : '' }}"
                            wire:click="setActiveTab('lessons')" type="button">
                            <i class="bi bi-book mr-1"></i>
                            {{ $t('Bài học', 'Lessons', '课程') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'assignments' ? 'active' : '' }}"
                            wire:click="setActiveTab('assignments')" type="button">
                            <i class="bi bi-journal-text mr-1"></i>
                            {{ $t('Bài tập', 'Assignments', '作业') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $activeTab === 'attendance' ? 'active' : '' }}"
                            wire:click="setActiveTab('attendance')" type="button">
                            <i class="bi bi-calendar-check mr-1"></i>
                            {{ $t('Điểm danh', 'Attendance', '点名') }}
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
                                {{ $t('Thông tin lớp học', 'Class Information', '班级信息') }}
                            </h6>
                            <div class="mb-3">
                                <strong>{{ $t('Tên lớp', 'Class name', '班级名称') }}:</strong> {{ $classroom->name }}
                            </div>
                            <div class="mb-3">
                                <strong>{{ $t('Mô tả', 'Description', '描述') }}:</strong> {{ $classroom->description }}
                            </div>
                            <div class="mb-3">
                                <strong>{{ $t('Ngày tạo', 'Created at', '创建时间') }}:</strong> {{ $classroom->created_at->format('d/m/Y H:i') }}
                            </div>
                            <div class="mb-3">
                                <strong>{{ $t('Trạng thái', 'Status', '状态') }}:</strong>
                                <span class="badge bg-success">{{ $t('Hoạt động', 'Active', '活跃') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success mb-3">
                                <i class="bi bi-graph-up mr-2"></i>
                                {{ $t('Thống kê nhanh', 'Quick Stats', '快速统计') }}
                            </h6>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="border rounded p-3 text-center">
                                        <h4 class="text-primary mb-1">{{ $classroom->lessons->count() }}</h4>
                                        <small class="text-muted">{{ $t('Bài học', 'Lessons', '课程') }}</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="border rounded p-3 text-center">
                                        <h4 class="text-success mb-1">{{ $classroom->assignments->count() }}</h4>
                                        <small class="text-muted">{{ $t('Bài tập', 'Assignments', '作业') }}</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="border rounded p-3 text-center">
                                        <h4 class="text-info mb-1">{{ $classroom->students->count() }}</h4>
                                        <small class="text-muted">{{ $t('Học sinh', 'Students', '学生') }}</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="border rounded p-3 text-center">
                                        <h4 class="text-warning mb-1">{{ $classroom->attendances->count() }}</h4>
                                        <small class="text-muted">{{ $t('Buổi học', 'Sessions', '课次') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Recent Activities -->
                    <div class="mt-4">
                        <h6 class="text-info mb-3">
                            <i class="bi bi-clock-history mr-2"></i>
                            {{ $t('Hoạt động gần đây', 'Recent Activities', '最近活动') }}
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-warning mb-2">{{ $t('Bài học mới nhất', 'Latest Lessons', '最新课程') }}</h6>
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
                                    <p class="text-muted">{{ $t('Chưa có bài học nào', 'No lessons yet', '暂无课程') }}</p>
                                @endforelse
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-success mb-2">{{ $t('Bài tập mới nhất', 'Latest Assignments', '最新作业') }}</h6>
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
                                    <p class="text-muted">{{ $t('Chưa có bài tập nào', 'No assignments yet', '暂无作业') }}</p>
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
                            {{ $t('Danh sách học sinh', 'Student List', '学生名单') }} ({{ $classroom->students->count() }})
                        </h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ $t('STT', 'No.', '序号') }}</th>
                                    <th>{{ $t('Họ tên', 'Full name', '姓名') }}</th>
                                    <th>Email</th>
                                    <th>{{ $t('Ngày tham gia', 'Joined at', '加入日期') }}</th>
                                    <th>{{ $t('Trạng thái', 'Status', '状态') }}</th>
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
                                                <span class="badge bg-success">{{ $t('Hoạt động', 'Active', '活跃') }}</span>
                                            @elseif($student->status === 'paused')
                                                <span class="badge bg-warning">{{ $t('Tạm dừng', 'Paused', '暂停') }}</span>
                                            @elseif($student->status === 'dropped')
                                                <span class="badge bg-danger">{{ $t('Đã rời lớp', 'Dropped', '已退课') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $t('Không xác định', 'Unknown', '未知') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bi bi-people text-muted" style="font-size: 2rem;"></i>
                                            <p class="mt-2">{{ $t('Chưa có học sinh nào trong lớp', 'No students in the class yet', '班级中尚无学生') }}</p>
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
                            {{ $t('Danh sách bài học', 'Lesson List', '课程列表') }} ({{ $classroom->lessons->count() }})
                        </h6>
                        <a href="{{ route('teacher.lessons.create', ['classroom_id' => $classroom->id]) }}"
                            class="btn btn-warning btn-sm">
                            <i class="bi bi-plus-circle mr-1"></i>
                            {{ $t('Thêm bài học', 'Add Lesson', '新增课程') }}
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
                                    <p class="mt-2 text-muted">{{ $t('Chưa có bài học nào', 'No lessons yet', '暂无课程') }}</p>
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
                            {{ $t('Danh sách bài tập', 'Assignment List', '作业列表') }} ({{ $classroom->assignments->count() }})
                        </h6>
                        <a href="{{ route('teacher.assignments.create', ['classroom_id' => $classroom->id]) }}"
                            class="btn btn-success btn-sm">
                            <i class="bi bi-plus-circle mr-1"></i>
                            {{ $t('Thêm bài tập', 'Add Assignment', '新增作业') }}
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
                                    <p class="mt-2 text-muted">{{ $t('Chưa có bài tập nào', 'No assignments yet', '暂无作业') }}</p>
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
                            {{ $t('Lịch sử điểm danh', 'Attendance History', '点名历史') }}
                        </h6>
                        <a href="{{ route('teacher.attendance.take', $classroom) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle mr-1"></i>
                            {{ $t('Điểm danh mới', 'New Attendance', '新增点名') }}
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ $t('Ngày', 'Date', '日期') }}</th>
                                    <th>{{ $t('Buổi học', 'Session', '课次') }}</th>
                                    <th>{{ $t('Số học sinh', 'Students', '学生数') }}</th>
                                    <th>{{ $t('Có mặt', 'Present', '到') }}</th>
                                    <th>{{ $t('Vắng', 'Absent', '缺') }}</th>
                                    <th>{{ $t('Thao tác', 'Actions', '操作') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($classroom->attendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $t('Buổi', 'Session', '第') }} {{ $attendance->session_number }}</td>
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
                                            <p class="mt-2">{{ $t('Chưa có lịch sử điểm danh', 'No attendance history yet', '暂无点名记录') }}</p>
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
