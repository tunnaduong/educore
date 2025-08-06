<x-layouts.dash-student active="assignments">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-journal-text mr-2"></i>Danh sách bài tập
                    </h4>
                    <p class="text-muted mb-0">Các bài tập bạn cần hoàn thành</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" class="form-control" wire:model.live="search"
                            placeholder="Tìm theo tên hoặc mô tả...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-control" wire:model.live="filterStatus">
                            <option value="all">Tất cả</option>
                            <option value="upcoming">Chưa đến hạn</option>
                            <option value="overdue">Quá hạn</option>
                            <option value="completed">Đã hoàn thành</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Lớp học</label>
                        <select class="form-control" wire:model.live="filterClassroom">
                            <option value="">Tất cả</option>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Giảng viên</label>
                        <select class="form-control" wire:model.live="filterTeacher">
                            <option value="">Tất cả</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Loại bài tập</label>
                        <select class="form-control" wire:model.live="filterType">
                            <option value="">Tất cả</option>
                            <option value="text">Điền từ</option>
                            <option value="essay">Tự luận</option>
                            <option value="image">Upload ảnh</option>
                            <option value="audio">Ghi âm</option>
                            <option value="video">Video</option>
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
                    <i class="bi bi-list-ul mr-2"></i>Danh sách bài tập
                </h6>
            </div>
            <div class="card-body">
                @if ($assignments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tiêu đề</th>
                                    <th>Lớp học</th>
                                    <th>Giảng viên</th>
                                    <th>Loại bài tập</th>
                                    <th>Hạn nộp</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assignments as $assignment)
                                    <tr>
                                        <td>
                                            <div class="fw-medium">
                                                <a href="{{ route('student.assignments.show', $assignment->id) }}"
                                                    class="text-decoration-none text-dark hover:text-primary"
                                                    wire:navigate>
                                                    {{ $assignment->title }}
                                                </a>
                                            </div>
                                            @if ($assignment->description)
                                                <small
                                                    class="text-muted">{{ Str::limit($assignment->description, 80) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $assignment->classroom->name }}</span>
                                        </td>
                                        <td>
                                            @if ($assignment->classroom->teachers->count())
                                                @foreach ($assignment->classroom->teachers as $teacher)
                                                    <span class="badge bg-secondary">{{ $teacher->name }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Chưa có giáo viên</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($assignment->types)
                                                @foreach ($assignment->types as $type)
                                                    <span class="badge bg-primary mr-1">
                                                        @switch($type)
                                                            @case('text')
                                                                Điền từ
                                                            @break

                                                            @case('essay')
                                                                Tự luận
                                                            @break

                                                            @case('image')
                                                                Upload ảnh
                                                            @break

                                                            @case('audio')
                                                                Ghi âm
                                                            @break

                                                            @case('video')
                                                                Video
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
                                                <span class="badge bg-success">Đã hoàn thành</span>
                                            @elseif($status === 'overdue')
                                                <span class="badge bg-danger">Quá hạn</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Cần làm</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($this->canSubmit($assignment))
                                                <a href="{{ route('student.assignments.submit', $assignment->id) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Làm bài"
                                                    wire:navigate>
                                                    <i class="bi bi-pencil"></i> Làm bài
                                                </a>
                                            @elseif($this->isCompleted($assignment))
                                                <a href="{{ route('student.assignments.show', $assignment->id) }}"
                                                    class="btn btn-sm btn-outline-success" title="Xem bài nộp"
                                                    wire:navigate>
                                                    <i class="bi bi-eye"></i> Xem bài nộp
                                                </a>
                                            @else
                                                <span class="text-muted">Quá hạn</span>
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
                        <h5 class="text-muted">Không có bài tập nào</h5>
                        <p class="text-muted">Hiện tại không có bài tập nào phù hợp với bộ lọc của bạn.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.dash-student>
