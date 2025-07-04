<x-layouts.dash-admin active="quizzes">
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-journal-check me-2"></i>Quản lý bài kiểm tra
                    </h4>
                    <p class="text-muted mb-0">Danh sách tất cả bài kiểm tra trong hệ thống</p>
                </div>
                <a href="{{ route('quizzes.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Tạo bài kiểm tra mới
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tìm kiếm</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            wire:model.live="search" 
                            placeholder="Tìm theo tên hoặc mô tả..."
                        >
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Lớp học</label>
                        <select class="form-select" wire:model.live="filterClass">
                            <option value="">Tất cả lớp</option>
                            @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-select" wire:model.live="filterStatus">
                            <option value="">Tất cả</option>
                            <option value="active">Còn hạn</option>
                            <option value="expired">Hết hạn</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" wire:click="$set('search', '')">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quiz List -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Danh sách bài kiểm tra
                </h6>
            </div>
            <div class="card-body">
                @if($quizzes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tiêu đề</th>
                                    <th>Lớp học</th>
                                    <th>Số câu hỏi</th>
                                    <th>Hạn nộp</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quizzes as $quiz)
                                    <tr>
                                        <td>
                                            <div class="fw-medium">{{ $quiz->title }}</div>
                                            @if($quiz->description)
                                                <small class="text-muted">{{ Str::limit($quiz->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $quiz->classroom->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $quiz->getQuestionCount() }}</span>
                                        </td>
                                        <td>
                                            @if($quiz->deadline)
                                                <div class="fw-medium">{{ $quiz->deadline->format('d/m/Y H:i') }}</div>
                                                <small class="text-muted">{{ $quiz->deadline->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">Không có hạn</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($quiz->isExpired())
                                                <span class="badge bg-danger">Hết hạn</span>
                                            @else
                                                <span class="badge bg-success">Còn hạn</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $quiz->created_at->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $quiz->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('quizzes.show', $quiz) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Xem chi tiết">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('quizzes.edit', $quiz) }}" 
                                                   class="btn btn-sm btn-outline-warning" 
                                                   title="Sửa">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ route('quizzes.results', $quiz) }}" 
                                                   class="btn btn-sm btn-outline-info" 
                                                   title="Xem kết quả">
                                                    <i class="bi bi-graph-up"></i>
                                                </a>
                                                <button 
                                                    type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Xóa"
                                                    onclick="confirm('Bạn có chắc chắn muốn xóa bài kiểm tra này?') || event.stopImmediatePropagation()"
                                                    wire:click="deleteQuiz({{ $quiz->id }})"
                                                >
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $quizzes->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-journal-x fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">Không có bài kiểm tra nào</h5>
                        <p class="text-muted">Hãy tạo bài kiểm tra đầu tiên để bắt đầu.</p>
                        <a href="{{ route('quizzes.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Tạo bài kiểm tra
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Flash Message -->
        @if(session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>
</x-layouts.dash-admin>
