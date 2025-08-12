<x-layouts.dash-teacher active="grading">
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('teacher.grading.index') }}" wire:navigate
                        class="text-decoration-none text-secondary">
                        <i class="bi bi-arrow-left mr-1"></i>Quay lại danh sách
                    </a>
                    <h4 class="mt-2 mb-0 text-primary fs-4">
                        <i class="bi bi-check-circle mr-2"></i>Chấm điểm bài tập
                    </h4>
                    <p class="text-muted mb-0">{{ $assignment->title }}</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Thông tin bài tập -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-journal-text mr-2"></i>Thông tin bài tập
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tiêu đề:</label>
                            <p class="mb-0">{{ $assignment->title }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lớp học:</label>
                            <p class="mb-0">{{ $assignment->classroom->name ?? '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Hạn nộp:</label>
                            @if ($assignment->deadline)
                                <p class="mb-0">{{ $assignment->deadline->format('d/m/Y H:i') }}</p>
                                <small class="text-muted">{{ $assignment->deadline->diffForHumans() }}</small>
                            @else
                                <p class="mb-0 text-muted">Không có hạn</p>
                            @endif
                        </div>
                        @if ($assignment->description)
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mô tả:</label>
                                <p class="mb-0">{{ $assignment->description }}</p>
                            </div>
                        @endif
                        @if ($assignment->attachment)
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tệp đính kèm:</label>
                                <div>
                                    <a href="{{ Storage::url($assignment->attachment) }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-download mr-1"></i>Tải xuống
                                    </a>
                                </div>
                            </div>
                        @endif
                        @if ($assignment->video)
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Video:</label>
                                <div>
                                    <video controls class="w-100 rounded">
                                        <source src="{{ Storage::url($assignment->video) }}" type="video/mp4">
                                        Trình duyệt không hỗ trợ video.
                                    </video>
                                </div>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Loại bài tập:</label>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach ($assignment->types as $type)
                                    <span class="badge bg-info">{{ $this->getSubmissionTypeLabel($type) }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Điểm tối đa:</label>
                            <p class="mb-0">{{ $assignment->score ?? 'Không có' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Thống kê -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-bar-chart mr-2"></i>Thống kê
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h4 class="text-primary mb-1">{{ $submissions->count() }}</h4>
                                    <small class="text-muted">Tổng bài nộp</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success mb-1">{{ $submissions->where('score', '!=', null)->count() }}
                                </h4>
                                <small class="text-muted">Đã chấm</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách bài nộp -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                        <div>
                            <i class="bi bi-people mr-2"></i>
                            <span class="mb-0">Danh sách bài nộp của học viên</span>
                        </div>
                        <div class="text-white-50 small">
                            {{ $submissions->count() }} bài nộp
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if ($submissions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Học viên</th>
                                            <th>Loại bài nộp</th>
                                            <th>Thời gian nộp</th>
                                            <th>Điểm</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($submissions as $submission)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm mr-3">
                                                            <i class="bi bi-person-circle fs-4 text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">
                                                                {{ $submission->student->user->name ?? '-' }}</div>
                                                            <small
                                                                class="text-muted">{{ $submission->student->user->email ?? '' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($submission->submission_type)
                                                        <span
                                                            class="badge bg-info">{{ $this->getSubmissionTypeLabel($submission->submission_type) }}</span>
                                                        @if ($submission->content)
                                                            <br><small class="text-muted">Có nội dung</small>
                                                        @else
                                                            <br><small class="text-muted">Chưa nộp</small>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary">Không xác định</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($submission->submitted_at)
                                                        <div class="fw-medium">
                                                            {{ $submission->submitted_at->format('d/m/Y H:i') }}</div>
                                                        <small
                                                            class="text-muted">{{ $submission->submitted_at->diffForHumans() }}</small>
                                                    @else
                                                        <span class="text-muted">Chưa nộp</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($submission->score !== null)
                                                        <span
                                                            class="badge bg-success">{{ $submission->score }}/10</span>
                                                    @else
                                                        <span class="badge bg-warning">Chưa chấm</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button wire:click="viewSubmission({{ $submission->id }})"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-eye mr-1"></i>Xem
                                                        </button>
                                                        @if (($submission->submission_type === 'essay' || $submission->submission_type === 'text') && $submission->submission_type !== 'image')
                                                            <a href="{{ route('teacher.ai.grading', $submission->id) }}"
                                                                class="btn btn-sm btn-outline-success">
                                                                <i class="fas fa-robot mr-1"></i>AI
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-people fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">Chưa có bài nộp nào</h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal xem chi tiết bài nộp -->
    @if ($showModal && $selectedSubmission)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-eye mr-2"></i>Chi tiết bài nộp
                        </h5>
                        <button type="button" class="close" wire:click="closeModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Thông tin học viên</h6>
                                <p><strong>Tên:</strong> {{ $selectedSubmission->student->user->name ?? '-' }}</p>
                                <p><strong>Email:</strong> {{ $selectedSubmission->student->user->email ?? '-' }}</p>
                                <p><strong>Thời gian nộp:</strong>
                                    {{ $selectedSubmission->submitted_at ? $selectedSubmission->submitted_at->format('d/m/Y H:i') : 'Chưa nộp' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6>Nội dung bài nộp</h6>
                                @if ($selectedSubmission->content)
                                    @if ($selectedSubmission->submission_type === 'text' || $selectedSubmission->submission_type === 'essay')
                                        <div class="mb-3">
                                            <div class="border rounded p-3 bg-light">
                                                {{ $selectedSubmission->content }}
                                            </div>
                                        </div>
                                    @elseif ($selectedSubmission->submission_type === 'image')
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Ảnh bài nộp:</label>
                                            <div>
                                                <img src="{{ Storage::url($selectedSubmission->content) }}"
                                                    alt="Bài nộp" class="img-fluid rounded border"
                                                    style="max-width: 100%;">
                                                <div class="mt-2">
                                                    <a href="{{ Storage::url($selectedSubmission->content) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-download mr-1"></i>Tải xuống
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($selectedSubmission->submission_type === 'video')
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Video bài nộp:</label>
                                            <div>
                                                <video controls class="w-100 rounded">
                                                    <source src="{{ Storage::url($selectedSubmission->content) }}"
                                                        type="video/mp4">
                                                    Trình duyệt không hỗ trợ video.
                                                </video>
                                                <div class="mt-2">
                                                    <a href="{{ Storage::url($selectedSubmission->content) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-download mr-1"></i>Tải xuống
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($selectedSubmission->submission_type === 'audio')
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Audio bài nộp:</label>
                                            <div>
                                                <audio controls class="w-100">
                                                    <source src="{{ Storage::url($selectedSubmission->content) }}"
                                                        type="audio/mpeg">
                                                    Trình duyệt không hỗ trợ audio.
                                                </audio>
                                                <div class="mt-2">
                                                    <a href="{{ Storage::url($selectedSubmission->content) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-download mr-1"></i>Tải xuống
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Tệp đính kèm:</label>
                                            <div>
                                                <a href="{{ Storage::url($selectedSubmission->content) }}"
                                                    target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-download mr-1"></i>Tải xuống
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="mb-3">
                                        <div class="alert alert-warning">
                                            <i class="bi bi-exclamation-triangle mr-2"></i>
                                            Không có nội dung bài nộp
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <!-- Form chấm điểm -->
                        <h6>Chấm điểm</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Điểm (0-10):</label>
                                <input type="number" class="form-control"
                                    wire:model.defer="grading.{{ $selectedSubmission->id }}.score" min="0"
                                    max="10" step="0.1" placeholder="Nhập điểm..."
                                    oninput="if(this.value > 10) this.value = 10; if(this.value < 0) this.value = 0;"
                                    onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46 || event.charCode === 8 || event.charCode === 9">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nhận xét:</label>
                                <textarea class="form-control" wire:model.defer="grading.{{ $selectedSubmission->id }}.feedback" rows="3"
                                    placeholder="Nhập nhận xét..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Đóng</button>
                        <button type="button" class="btn btn-primary"
                            wire:click="saveGrade({{ $selectedSubmission->id }})">
                            <i class="bi bi-save mr-1"></i>Lưu điểm
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    @if (session()->has('success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif
</x-layouts.dash-teacher>
