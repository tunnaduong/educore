<x-layouts.dash-admin active="submissions">
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-journal-check mr-2"></i>Danh sách bài tập cần chấm
                    </h4>
                    <p class="text-muted mb-0">Quản lý và chấm điểm các bài tập của học viên</p>
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
                            placeholder="Tìm theo tên hoặc mô tả bài tập...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Lớp học</label>
                        <select class="form-control" wire:model.live="filterClassroom">
                            <option value="">Tất cả lớp</option>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-control" wire:model.live="filterStatus">
                            <option value="all">Tất cả</option>
                            <option value="has_submissions">Có bài nộp</option>
                            <option value="no_submissions">Chưa có bài nộp</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Sắp xếp</label>
                        <select class="form-control" wire:model.live="sortBy">
                            <option value="submissions_count">Số bài nộp</option>
                            <option value="created_at">Ngày tạo</option>
                            <option value="deadline">Hạn nộp</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-9">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                        <div>
                            <i class="bi bi-journal-check mr-2"></i>
                            <span class="mb-0">Danh sách bài tập cần chấm</span>
                        </div>
                        <div class="text-white-50 small">
                            {{ $assignments->total() }} bài tập
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if ($assignments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>
                                                Lớp học
                                                @if ($sortBy === 'created_at')
                                                    <i
                                                        class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </th>
                                            <th>Tiêu đề</th>
                                            <th>
                                                Hạn nộp
                                                @if ($sortBy === 'deadline')
                                                    <i
                                                        class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </th>
                                            <th class="text-center">
                                                Số bài nộp
                                                @if ($sortBy === 'submissions_count')
                                                    <i
                                                        class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </th>
                                            <th class="text-center">Trạng thái</th>
                                            <th class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($assignments as $assignment)
                                            <tr>
                                                <td>
                                                    <span class="fw-semibold">
                                                        <i class="bi bi-mortarboard mr-1"></i>
                                                        {{ $assignment->classroom?->name ?? '-' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="fw-medium">{{ $assignment->title }}</div>
                                                    @if ($assignment->description)
                                                        <small
                                                            class="text-muted">{{ Str::limit($assignment->description, 60) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-calendar3"></i>
                                                        {{ $assignment->deadline ? $assignment->deadline->format('d/m/Y H:i') : '-' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-info text-dark">
                                                        {{ $assignment->submissions_count }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($assignment->submissions_count > 0)
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check2-all"></i> Có bài nộp
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">
                                                            <i class="bi bi-hourglass-split"></i> Chưa có
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-primary btn-sm px-3"
                                                        wire:click="selectAssignment({{ $assignment->id }})">
                                                        <i class="bi bi-pencil-square"></i> Chấm bài
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mx-3 mt-4">
                                {{ $assignments->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        @else
                            <div class="alert alert-info text-center m-0 py-4">
                                <i class="bi bi-info-circle fs-3"></i><br>
                                Không có bài tập nào phù hợp với bộ lọc.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-3 d-none d-lg-block">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-light">
                        <i class="bi bi-lightbulb mr-2"></i>Hướng dẫn
                    </div>
                    <div class="card-body small">
                        <ul class="mb-2 ps-3">
                            <li>Chỉ hiện các bài tập thuộc lớp bạn phụ trách.</li>
                            <li>Bấm <span class="badge bg-primary"><i class="bi bi-pencil-square"></i> Chấm bài</span>
                                để vào giao diện chấm điểm.</li>
                            <li>Số bài nộp sẽ hiển thị theo từng bài tập.</li>
                            <li>Bài tập có bài nộp sẽ được sắp xếp lên đầu.</li>
                        </ul>
                        <div class="alert alert-info p-2 mb-0">
                            <i class="bi bi-info-circle"></i> Chỉ giáo viên hoặc admin mới có quyền chấm bài.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
