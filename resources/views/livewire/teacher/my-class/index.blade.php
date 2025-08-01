<x-layouts.dash-teacher active="my-class">
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="mb-0">
                    <i class="bi bi-diagram-3-fill text-primary me-2"></i>
                    Lớp học của tôi
                </h2>
                <p class="text-muted mb-0">Quản lý các lớp học bạn đang giảng dạy</p>
            </div>
            <div class="col-md-6 text-end">
                <div class="d-flex justify-content-end gap-2">
                    <div class="input-group" style="max-width: 300px;">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" wire:model.live="search" class="form-control" placeholder="Tìm kiếm lớp học...">
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
                        <p class="text-muted mb-0">Tổng số lớp</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-people-fill text-success" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-1">{{ $classrooms->sum(function($classroom) { return $classroom->students->count(); }) }}</h4>
                        <p class="text-muted mb-0">Tổng học sinh</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-book-fill text-info" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-1">{{ $classrooms->sum(function($classroom) { return $classroom->lessons->count(); }) }}</h4>
                        <p class="text-muted mb-0">Tổng bài học</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-journal-text text-warning" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-1">{{ $classrooms->sum(function($classroom) { return $classroom->assignments->count(); }) }}</h4>
                        <p class="text-muted mb-0">Tổng bài tập</p>
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
                            <span class="badge bg-light text-dark">{{ $classroom->students->count() }} HS</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">{{ Str::limit($classroom->description, 100) }}</p>
                        
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <div class="border-end">
                                    <h6 class="mb-1 text-primary">{{ $classroom->lessons->count() }}</h6>
                                    <small class="text-muted">Bài học</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-end">
                                    <h6 class="mb-1 text-success">{{ $classroom->assignments->count() }}</h6>
                                    <small class="text-muted">Bài tập</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <h6 class="mb-1 text-info">{{ $classroom->students->count() }}</h6>
                                <small class="text-muted">Học sinh</small>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('teacher.my-class.show', $classroom->id) }}" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-1"></i>
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <small class="text-muted">
                            <i class="bi bi-calendar me-1"></i>
                            Tạo ngày: {{ $classroom->created_at->format('d/m/Y') }}
                        </small>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-diagram-3 text-muted" style="font-size: 4rem;"></i>
                    <h5 class="mt-3 text-muted">Chưa có lớp học nào</h5>
                    <p class="text-muted">Bạn chưa được phân công giảng dạy lớp học nào.</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($classrooms->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $classrooms->links() }}
        </div>
        @endif
    </div>

    <!-- Classroom Details Modal -->
    @if($showClassroomDetails && $selectedClassroom)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-diagram-3-fill me-2"></i>
                        {{ $selectedClassroom->name }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="closeClassroomDetails"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-info-circle me-2"></i>
                                Thông tin lớp học
                            </h6>
                            <p><strong>Mô tả:</strong> {{ $selectedClassroom->description }}</p>
                            <p><strong>Ngày tạo:</strong> {{ $selectedClassroom->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Số học sinh:</strong> {{ $selectedClassroom->students->count() }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success mb-3">
                                <i class="bi bi-graph-up me-2"></i>
                                Thống kê
                            </h6>
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border rounded p-3">
                                        <h4 class="text-primary mb-1">{{ $selectedClassroom->lessons->count() }}</h4>
                                        <small class="text-muted">Bài học</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-3">
                                        <h4 class="text-success mb-1">{{ $selectedClassroom->assignments->count() }}</h4>
                                        <small class="text-muted">Bài tập</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Students List -->
                    <div class="mt-4">
                        <h6 class="text-info mb-3">
                            <i class="bi bi-people me-2"></i>
                            Danh sách học sinh ({{ $selectedClassroom->students->count() }})
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>STT</th>
                                        <th>Họ tên</th>
                                        <th>Email</th>
                                        <th>Ngày tham gia</th>
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
                                        <td colspan="4" class="text-center text-muted">Chưa có học sinh nào</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Recent Lessons -->
                    <div class="mt-4">
                        <h6 class="text-warning mb-3">
                            <i class="bi bi-book me-2"></i>
                            Bài học gần đây
                        </h6>
                        @forelse($selectedClassroom->lessons->take(3) as $lesson)
                        <div class="card mb-2">
                            <div class="card-body py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $lesson->title }}</h6>
                                        <small class="text-muted">{{ Str::limit($lesson->description, 50) }}</small>
                                    </div>
                                    <small class="text-muted">{{ $lesson->created_at->format('d/m/Y') }}</small>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted text-center">Chưa có bài học nào</p>
                        @endforelse
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeClassroomDetails">Đóng</button>
                    <a href="#" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i>
                        Chỉnh sửa lớp học
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</x-layouts.dash-teacher> 