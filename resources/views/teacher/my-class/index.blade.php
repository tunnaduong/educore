<x-layouts.dash-teacher active="my-class">
    @include('components.language')
    @php
        $t = function ($vi, $en, $zh) {
            $l = app()->getLocale();
            return $l === 'vi' ? $vi : ($l === 'zh' ? $zh : $en);
        };
    @endphp
    <div class="container">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4 class="mb-0 fs-4 text-primary">
                    <i class="bi bi-diagram-3-fill text-primary mr-2"></i>
                    {{ $t('Lớp học của tôi', 'My Classes', '我的课堂') }}
                </h4>
                <p class="text-muted mb-0">{{ $t('Quản lý các lớp học bạn đang giảng dạy', 'Manage the classes you are teaching', '管理您正在教授的班级') }}</p>
            </div>
            <div class="col-md-6 text-end">
                <div class="d-flex justify-content-end gap-2">
                    <div class="input-group" style="max-width: 300px;">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" wire:model.live="search" class="form-control"
                            placeholder="{{ $t('Tìm kiếm lớp học...', 'Search classes...', '搜索班级...') }}">
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
                        <p class="text-muted mb-0">{{ $t('Tổng số lớp', 'Total Classes', '总班级数') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-people-fill text-success" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-1">
                            {{ $classrooms->sum(function ($classroom) {return $classroom->students->count();}) }}</h4>
                        <p class="text-muted mb-0">{{ $t('Tổng học sinh', 'Total Students', '学生总数') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-book text-info" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-1">
                            {{ $classrooms->sum(function ($classroom) {return $classroom->lessons->count();}) }}</h4>
                        <p class="text-muted mb-0">{{ $t('Tổng bài học', 'Total Lessons', '总课程数') }}</p>
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
                        <p class="text-muted mb-0">{{ $t('Tổng bài tập', 'Total Assignments', '总作业数') }}</p>
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
                                <span class="badge bg-light text-dark">{{ $classroom->students->count() }} {{ $t('Học sinh', 'Students', '学生') }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small mb-3">{{ Str::limit($classroom->description, 100) }}</p>

                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <div class="border-end">
                                        <h6 class="mb-1 text-primary">{{ $classroom->lessons->count() }}</h6>
                                        <small class="text-muted">{{ $t('Bài học', 'Lessons', '课程') }}</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-end">
                                        <h6 class="mb-1 text-success">{{ $classroom->assignments->count() }}</h6>
                                        <small class="text-muted">{{ $t('Bài tập', 'Assignments', '作业') }}</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <h6 class="mb-1 text-info">{{ $classroom->students->count() }}</h6>
                                    <small class="text-muted">{{ $t('Học sinh', 'Students', '学生') }}</small>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('teacher.my-class.show', $classroom->id) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye mr-1"></i>
                                    {{ $t('Xem chi tiết', 'View details', '查看详情') }}
                                </a>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <small class="text-muted">
                                <i class="bi bi-calendar mr-1"></i>
                                {{ $t('Tạo ngày', 'Created on', '创建日期') }}: {{ $classroom->created_at->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="bi bi-diagram-3 text-muted" style="font-size: 4rem;"></i>
                        <h5 class="mt-3 text-muted">{{ $t('Chưa có lớp học nào', 'No classes yet', '暂无班级') }}</h5>
                        <p class="text-muted">{{ $t('Bạn chưa được phân công giảng dạy lớp học nào.', 'You have not been assigned to teach any class.', '您尚未被分配教授任何班级。') }}</p>
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
                                    {{ $t('Thông tin lớp học', 'Class Information', '班级信息') }}
                                </h6>
                                <p><strong>{{ $t('Mô tả', 'Description', '描述') }}:</strong> {{ $selectedClassroom->description }}</p>
                                <p><strong>{{ $t('Ngày tạo', 'Created at', '创建时间') }}:</strong> {{ $selectedClassroom->created_at->format('d/m/Y H:i') }}
                                </p>
                                <p><strong>{{ $t('Số học sinh', 'Number of students', '学生人数') }}:</strong> {{ $selectedClassroom->students->count() }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-success mb-3">
                                    <i class="bi bi-graph-up mr-2"></i>
                                    {{ $t('Thống kê', 'Statistics', '统计') }}
                                </h6>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="border rounded p-3">
                                            <h4 class="text-primary mb-1">{{ $selectedClassroom->lessons->count() }}
                                            </h4>
                                            <small class="text-muted">{{ $t('Bài học', 'Lessons', '课程') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="border rounded p-3">
                                            <h4 class="text-success mb-1">
                                                {{ $selectedClassroom->assignments->count() }}</h4>
                                            <small class="text-muted">{{ $t('Bài tập', 'Assignments', '作业') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Students List -->
                        <div class="mt-4">
                            <h6 class="text-info mb-3">
                                <i class="bi bi-people mr-2"></i>
                                {{ $t('Danh sách học sinh', 'Student List', '学生名单') }} ({{ $selectedClassroom->students->count() }})
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ $t('STT', 'No.', '序号') }}</th>
                                            <th>{{ $t('Họ tên', 'Full name', '姓名') }}</th>
                                            <th>Email</th>
                                            <th>{{ $t('Ngày tham gia', 'Joined at', '加入日期') }}</th>
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
                                                <td colspan="4" class="text-center text-muted">{{ $t('Chưa có học sinh nào', 'No students yet', '暂无学生') }}
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
                                {{ $t('Bài học gần đây', 'Recent Lessons', '近期课程') }}
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
                                <p class="text-muted text-center">{{ $t('Chưa có bài học nào', 'No lessons yet', '暂无课程') }}</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="closeClassroomDetails">{{ $t('Đóng', 'Close', '关闭') }}</button>
                        <a href="#" class="btn btn-primary">
                            <i class="bi bi-pencil mr-1"></i>
                            {{ $t('Chỉnh sửa lớp học', 'Edit Class', '编辑班级') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</x-layouts.dash-teacher>
