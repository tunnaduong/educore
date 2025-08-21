<x-layouts.dash-teacher active="lessons">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-eye mr-2"></i>Chi tiết bài học
                    </h4>
                    <p class="text-muted mb-0">{{ __('general.lesson_detail_description') }}</p>
                </div>
                <div>
                    <a href="{{ route('teacher.lessons.edit', $lesson->id) }}" class="btn btn-warning mr-2">
                        <i class="bi bi-pencil mr-1"></i>Chỉnh sửa
                    </a>
                    <a href="{{ route('teacher.lessons.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left mr-1"></i>Quay lại
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
                            <i class="bi bi-file-earmark-text mr-2"></i>{{ __('general.lesson_info') }}
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
                                    @if ($lesson->number)
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
                                    @if ($lesson->description)
                                        {!! nl2br(e($lesson->description)) !!}
                                    @else
                                        <span class="text-muted">Không có mô tả</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Video Section -->
                @if ($lesson->video)
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bi bi-play-circle mr-2"></i>Video bài học
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
                @if ($lesson->attachment)
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bi bi-file-earmark mr-2"></i>Tài liệu đính kèm
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center p-3 border rounded">
                                <i class="bi bi-file-earmark-text fs-2 text-primary mr-3"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ basename($lesson->attachment) }}</h6>
                                    <small class="text-muted">Tài liệu bài học</small>
                                </div>
                            </div>
                            <div class="mt-3 d-flex gap-2">
                                <a href="{{ asset('storage/' . $lesson->attachment) }}" target="_blank"
                                    class="btn btn-success flex-fill">
                                    <i class="bi bi-download mr-1"></i>{{ __('general.download') }}
                                </a>
                                <button class="btn btn-outline-primary flex-fill" type="button"
                                    onclick="openPreviewModal()">
                                    <i class="bi bi-eye"></i> {{ __('general.preview_document') }}
                                </button>
                            </div>
                            @php
                                $ext = strtolower(pathinfo($lesson->attachment, PATHINFO_EXTENSION));
                                $fileUrl = asset('storage/' . $lesson->attachment);
                            @endphp
                        </div>
                    </div>
                @endif

                <!-- Lesson Info -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle mr-2"></i>Thông tin khác
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

    <!-- Modal Preview -->
    <style>
        .modal-custom {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content-custom {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 0;
            border: none;
            border-radius: 8px;
            width: 90%;
            max-width: 1000px;
            max-height: 90vh;
            overflow: hidden;
        }

        .modal-header-custom {
            padding: 15px 20px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-body-custom {
            padding: 0;
            max-height: calc(90vh - 80px);
            overflow: auto;
        }

        .close-custom {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            background: none;
        }

        .close-custom:hover {
            color: #000;
        }
    </style>
    <div id="previewModal" class="modal-custom">
        <div class="modal-content-custom">
            <div class="modal-header-custom">
                <h5 class="modal-title">Xem trước tài liệu</h5>
                <button type="button" class="close-custom" onclick="closePreviewModal()">&times;</button>
            </div>
            <div class="modal-body-custom">
                @if ($lesson->attachment)
                    @if (in_array($ext, ['pdf']))
                        <iframe src="{{ $fileUrl }}" width="100%" height="600px"
                            style="border:1px solid #ccc;"></iframe>
                    @elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']))
                        <img src="{{ $fileUrl }}" alt="Tài liệu hình ảnh"
                            class="img-fluid border rounded d-block mx-auto" style="max-height:600px;">
                    @elseif (in_array($ext, ['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx']))
                        <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode($fileUrl) }}"
                            width="100%" height="600px" frameborder="0"></iframe>
                    @else
                        <div class="alert alert-info m-3">Không hỗ trợ xem trước loại tệp này. Vui lòng tải về để xem
                            chi tiết.</div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <script>
        function openPreviewModal() {
            document.getElementById('previewModal').style.display = 'block';
        }

        function closePreviewModal() {
            document.getElementById('previewModal').style.display = 'none';
        }
        window.onclick = function(event) {
            var modal = document.getElementById('previewModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>

</x-layouts.dash-teacher>
