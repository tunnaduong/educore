<x-layouts.dash-admin active="evaluation-management">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-bar-chart-line me-2"></i>Quản lý đánh giá chất lượng học viên
                    </h4>
                    <p class="text-muted mb-0">Xem đánh giá và quản lý câu hỏi đánh giá</p>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4" id="evaluationTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'evaluations' ? 'active' : '' }}"
                        wire:click="$set('activeTab', 'evaluations')" type="button" role="tab" style="cursor: pointer;">
                    <i class="bi bi-list-check me-2"></i>Danh sách đánh giá
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'questions' ? 'active' : '' }}"
                        wire:click="$set('activeTab', 'questions')" type="button" role="tab" style="cursor: pointer;">
                    <i class="bi bi-question-circle me-2"></i>Quản lý câu hỏi
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="evaluationTabsContent">
            <!-- Tab 1: Danh sách đánh giá -->
            <div class="{{ $activeTab === 'evaluations' ? 'd-block' : 'd-none' }}" id="evaluations" role="tabpanel">
                <!-- Hướng dẫn tính điểm -->
                <div class="alert alert-info mb-4">
                    <h6 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Hướng dẫn tính điểm:</h6>
                    <ul class="mb-0">
                        <li><strong>1-2 điểm:</strong> Không hài lòng</li>
                        <li><strong>3 điểm:</strong> Bình thường</li>
                        <li><strong>4-5 điểm:</strong> Hài lòng</li>
                    </ul>
                </div>

                <!-- Bộ lọc -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="classroomFilter" class="form-label">Lọc theo lớp:</label>
                                <select wire:model.live="classroomId" class="form-select" id="classroomFilter">
                                    <option value="">Tất cả lớp</option>
                                    @foreach($classrooms as $classroom)
                                        <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thống kê điểm trung bình -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Điểm TB Giáo viên</h6>
                                        <h3 class="mb-0">{{ number_format($avgTeacher, 1) }}/5.0</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-person-check fs-1"></i>
                                    </div>
                                </div>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-light" style="width: {{ ($avgTeacher / 5) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Điểm TB Khóa học</h6>
                                        <h3 class="mb-0">{{ number_format($avgCourse, 1) }}/5.0</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-book-check fs-1"></i>
                                    </div>
                                </div>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-light" style="width: {{ ($avgCourse / 5) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Điểm TB Cá nhân</h6>
                                        <h3 class="mb-0">{{ number_format($avgPersonal, 1) }}/5.0</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-heart-check fs-1"></i>
                                    </div>
                                </div>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-light" style="width: {{ ($avgPersonal / 5) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danh sách đánh giá -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Danh sách đánh giá ({{ $total }} đánh giá)</h6>
                    </div>
                    <div class="card-body">
                        @if($evaluations->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Học viên</th>
                                            <th>Lớp</th>
                                            <th>Điểm TB</th>
                                            <th>Trạng thái</th>
                                            <th>Ngày gửi</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($evaluations as $evaluation)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                            <span class="text-white fw-bold">{{ substr($evaluation->student->user->name ?? 'N/A', 0, 1) }}</span>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">{{ $evaluation->student->user->name ?? 'N/A' }}</div>
                                                            <small class="text-muted">{{ $evaluation->student->user->email ?? 'N/A' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $evaluation->student->classroom->name ?? 'N/A' }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="fw-bold me-2">{{ number_format($evaluation->getOverallRating(), 1) }}/5.0</span>
                                                        @php
                                                            $rating = $evaluation->getOverallRating();
                                                            $color = $rating >= 4 ? 'success' : ($rating >= 3 ? 'warning' : 'danger');
                                                        @endphp
                                                        <span class="badge bg-{{ $color }}">
                                                            @if($rating >= 4) Hài lòng
                                                            @elseif($rating >= 3) Bình thường
                                                            @else Không hài lòng
                                                            @endif
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($evaluation->submitted_at)
                                                        <span class="badge bg-success">Đã gửi</span>
                                                    @else
                                                        <span class="badge bg-warning">Chưa gửi</span>
                                                    @endif
                                                </td>
                                                <td>{{ $evaluation->submitted_at ? $evaluation->submitted_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" wire:click="showEvaluationDetail({{ $evaluation->id }})">
                                                        <i class="bi bi-eye me-1"></i>Xem chi tiết
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $evaluations->links() }}
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">Chưa có đánh giá nào</h5>
                                <p class="text-muted">Học viên chưa gửi đánh giá nào trong hệ thống.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tab 2: Quản lý câu hỏi -->
            <div class="{{ $activeTab === 'questions' ? 'd-block' : 'd-none' }}" id="questions" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Quản lý câu hỏi đánh giá</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-3">
                            <button class="btn btn-primary" wire:click="showAddQuestionModal">
                                <i class="bi bi-plus-circle me-2"></i>Thêm câu hỏi
                            </button>
                        </div>
                        @if($questions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Thứ tự</th>
                                            <th>Danh mục</th>
                                            <th>Câu hỏi</th>
                                            <th>Trạng thái</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($questions as $question)
                                            <tr>
                                                <td>{{ $question->order }}</td>
                                                <td>
                                                    @switch($question->category)
                                                        @case('teacher')
                                                            <span class="badge bg-primary">Giáo viên</span>
                                                            @break
                                                        @case('course')
                                                            <span class="badge bg-success">Khóa học</span>
                                                            @break
                                                        @case('personal')
                                                            <span class="badge bg-warning">Cá nhân</span>
                                                            @break
                                                    @endswitch
                                                </td>
                                                <td>{{ $question->question }}</td>
                                                <td>
                                                    @if($question->is_active)
                                                        <span class="badge bg-success">Hoạt động</span>
                                                    @else
                                                        <span class="badge bg-secondary">Không hoạt động</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-primary" wire:click="showEditQuestionModal({{ $question->id }})">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button class="btn btn-outline-{{ $question->is_active ? 'warning' : 'success' }}"
                                                                wire:click="toggleQuestionStatus({{ $question->id }})">
                                                            <i class="bi bi-{{ $question->is_active ? 'pause' : 'play' }}"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger"
                                                                wire:click="deleteQuestion({{ $question->id }})"
                                                                onclick="return confirm('Bạn có chắc chắn muốn xóa câu hỏi này?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-question-circle fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">Chưa có câu hỏi nào</h5>
                                <p class="text-muted">Hãy thêm câu hỏi đánh giá để học viên có thể đánh giá chất lượng học tập.</p>
                                <button class="btn btn-primary" wire:click="showAddQuestionModal">
                                    <i class="bi bi-plus-circle me-2"></i>Thêm câu hỏi đầu tiên
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal chi tiết đánh giá -->
        @if($selectedEvaluation)
        <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5); z-index: 1050;" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary fw-bold fs-4">
                            <i class="bi bi-star-fill text-warning me-2"></i>
                            Chi tiết đánh giá của {{ $selectedEvaluation->student->user->name ?? 'Học viên' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeEvaluationDetail">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <strong>Nhóm 1 - Đánh giá giáo viên:</strong>
                            <ul class="mb-2">
                                @foreach ($selectedEvaluation->teacher_ratings ?? [] as $k => $v)
                                    @php
                                        $question = $questions->where('category', 'teacher')->where('order', $k)->first();
                                    @endphp
                                    <li>{{ $question ? $question->question : "Câu hỏi $k" }}: {{ $v }}/5</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="mb-3">
                            <strong>Nhóm 2 - Đánh giá khóa học:</strong>
                            <ul class="mb-2">
                                @foreach ($selectedEvaluation->course_ratings ?? [] as $k => $v)
                                    @php
                                        $question = $questions->where('category', 'course')->where('order', $k)->first();
                                    @endphp
                                    <li>{{ $question ? $question->question : "Câu hỏi $k" }}: {{ $v }}/5</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="mb-3">
                            <strong>Nhóm 3 - Mức độ hài lòng cá nhân:</strong>
                            @php
                                $personalQuestion = $questions->where('category', 'personal')->first();
                            @endphp
                            <p class="mb-2">{{ $personalQuestion ? $personalQuestion->question : 'Mức độ hài lòng cá nhân' }}: {{ $selectedEvaluation->personal_satisfaction }}/5</p>
                        </div>
                        @if($selectedEvaluation->suggestions)
                            <div class="mb-3">
                                <strong>Đề xuất cải thiện:</strong>
                                <p class="mb-0">{{ $selectedEvaluation->suggestions }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeEvaluationDetail">
                            Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Modal thêm/sửa câu hỏi -->
        @if($showQuestionModal)
        <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5); z-index: 1050;" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-question-circle me-2"></i>
                            {{ $editingQuestion ? 'Sửa câu hỏi' : 'Thêm câu hỏi mới' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeQuestionModal">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <form wire:submit.prevent="saveQuestion">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="category" class="form-label">Danh mục <span class="text-danger">*</span></label>
                                <select wire:model="questionForm.category" class="form-select" id="category">
                                    <option value="">Chọn danh mục</option>
                                    <option value="teacher">Giáo viên</option>
                                    <option value="course">Khóa học</option>
                                    <option value="personal">Cá nhân</option>
                                </select>
                                @error('questionForm.category') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="question" class="form-label">Câu hỏi <span class="text-danger">*</span></label>
                                <textarea wire:model="questionForm.question" class="form-control" id="question" rows="3" placeholder="Nhập câu hỏi đánh giá..."></textarea>
                                @error('questionForm.question') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="order" class="form-label">Thứ tự hiển thị</label>
                                <input type="number" wire:model="questionForm.order" class="form-control" id="order" min="0" placeholder="0">
                                @error('questionForm.order') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input wire:model="questionForm.is_active" class="form-check-input" type="checkbox" id="is_active">
                                    <label class="form-check-label" for="is_active">
                                        Câu hỏi hoạt động
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeQuestionModal">
                                Hủy
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ $editingQuestion ? 'Cập nhật' : 'Thêm mới' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>

    @if (session()->has('success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1060;">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1060;">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
</x-layouts.dash-admin>
