<x-layouts.dash-student active="courses">
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-book me-2"></i>Danh sách bài học
                    </h4>
                    <p class="text-muted mb-0">Các bài học bạn có thể xem và tham khảo</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" class="form-control" wire:model.live="search"
                            placeholder="Tìm theo tên hoặc mô tả...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Lớp học</label>
                        <select class="form-select" wire:model.live="filterClass">
                            <option value="">Tất cả lớp</option>
                            @foreach ($classrooms ?? [] as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" wire:click="$set('search', '')">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lesson List -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Danh sách bài học
                </h6>
            </div>
            <div class="card-body">
                @if ($lessons->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tiêu đề</th>
                                    <th>Lớp học</th>
                                    <th>Mô tả</th>
                                    <th>Tệp/Video</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lessons as $lesson)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ $lesson->number }}</span>
                                            <span class="fw-medium">{{ $lesson->title }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $lesson->classroom->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ Str::limit($lesson->description, 50) }}</small>
                                        </td>
                                        <td>
                                            @if ($lesson->attachment)
                                                <a href="{{ asset('storage/' . $lesson->attachment) }}" target="_blank"
                                                    class="badge bg-success">Tệp</a>
                                            @endif
                                            @if ($lesson->video)
                                                <a href="{{ $lesson->video }}" target="_blank"
                                                    class="badge bg-warning text-dark">Video</a>
                                            @endif
                                            @if (!$lesson->attachment && !$lesson->video)
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (!empty($completedLessons[$lesson->id]) && $completedLessons[$lesson->id])
                                                <span class="badge bg-success">Đã hoàn thành</span>
                                            @else
                                                <span class="badge bg-danger">Chưa xem</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('student.lessons.show', $lesson->id) }}" wire:navigate
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> Xem chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $lessons->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-journal-x fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">Không có bài học nào</h5>
                        <p class="text-muted">Bạn chưa có bài học nào để xem.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.dash-student>
