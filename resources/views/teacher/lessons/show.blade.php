<x-layouts.dash-teacher active="lessons">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-eye me-2"></i>Chi tiết bài học
                    </h4>
                    <p class="text-muted mb-0">Xem thông tin chi tiết bài học và tài nguyên</p>
                </div>
                <div>
                    <a href="{{ route('teacher.lessons.edit', $lesson->id) }}" wire:navigate class="btn btn-warning me-2">
                        <i class="bi bi-pencil me-1"></i>Chỉnh sửa
                    </a>
                    <a href="{{ route('teacher.lessons.index') }}" wire:navigate class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>

        <!-- Lesson Details -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-file-earmark-text me-2"></i>Thông tin bài học
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Tiêu đề</label>
                                <p class="mb-0">{{ $lesson->title }}</p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Số bài học</label>
                                <p class="mb-0">
                                    @if($lesson->number)
                                        <span class="badge bg-info">{{ $lesson->number }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Lớp học</label>
                                <p class="mb-0">
                                    <span class="badge bg-primary">{{ $lesson->classroom->name ?? 'N/A' }}</span>
                                </p>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Mô tả</label>
                                <p class="mb-0">
                                    @if($lesson->description)
                                        {{ $lesson->description }}
                                    @else
                                        <span class="text-muted">Không có mô tả</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Video Section -->
                @if($lesson->video)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-play-circle me-2"></i>Video bài học
                        </h6>
                    </div>
                    <div class="card-body">
                        <x-video-embed :url="$lesson->video" title="Video bài học" />
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <!-- Attachment Section -->
                @if($lesson->attachment)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-file-earmark me-2"></i>Tài liệu đính kèm
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center p-3 border rounded">
                            <i class="bi bi-file-earmark-text fs-2 text-primary me-3"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ basename($lesson->attachment) }}</h6>
                                <small class="text-muted">Tài liệu bài học</small>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ asset('storage/' . $lesson->attachment) }}" target="_blank" 
                                class="btn btn-success w-100">
                                <i class="bi bi-download me-1"></i>Tải xuống
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Lesson Info -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Thông tin khác
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold">Ngày tạo</label>
                                <p class="mb-0">{{ $lesson->created_at?->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">Cập nhật lần cuối</label>
                                <p class="mb-0">{{ $lesson->updated_at?->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-teacher> 