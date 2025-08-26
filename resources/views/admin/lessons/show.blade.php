<x-layouts.dash-admin active="lessons">
    @include('components.language')
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
    <div class="container py-4">
        <a href="{{ route('lessons.index') }}" class="text-decoration-none text-secondary d-inline-block mb-3">
            <i class="bi bi-arrow-left mr-2"></i>Quay lại danh sách
        </a>
        <div class="card shadow-lg border-0">
            <div class="card-body p-4">
                <div class="row g-4 align-items-start">
                    <div class="col-md-8">
                        <h3 class="mb-3 text-success fw-bold">
                            <i class="bi bi-journal-richtext mr-2"></i>{{ $lesson->title }}
                        </h3>
                        <div class="mb-2">
                            <span class="badge bg-info mr-2"><i class="bi bi-hash"></i> Bài số:
                                {{ $lesson->number }}</span>
                            <span class="badge bg-secondary"><i class="bi bi-calendar-event"></i>
                                {{ $lesson->created_at?->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="mb-3">
                            <strong class="text-muted">Mô tả:</strong>
                            <style>
                                .description-content img {
                                    max-width: 100% !important;
                                    height: auto !important;
                                }
                            </style>
                            <div class="border rounded p-2 bg-light mt-1 description-content">
                                {!! $lesson->description ?: 'Không có mô tả.' !!}
                            </div>
                        </div>
                        @if ($lesson->content)
                            <div class="mb-3">
                                <strong class="text-muted">Nội dung chi tiết:</strong>
                                <div class="border rounded p-3 bg-white mt-1">{!! $lesson->content !!}</div>
                            </div>
                        @endif
                        <div class="d-flex gap-2 mt-4">
                            <a href="{{ route('lessons.edit', $lesson->id) }}" class="btn btn-warning"><i
                                    class="bi bi-pencil mr-1"></i>Sửa</a>
                            <button type="button" class="btn btn-danger" wire:click="deleteLesson({{ $lesson->id }})"
                                wire:confirm="Bạn có chắc chắn muốn xóa bài học '{{ $lesson->title }}' không?"><i
                                    class="bi bi-trash mr-1"></i>Xóa</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <strong class="text-muted">Video bài học:</strong><br>
                            @if ($lesson->video)
                                <x-video-embed :url="$lesson->video" title="Video bài học" />
                            @else
                                <span class="text-muted">Không có video</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <strong class="text-muted">Tài liệu/Slide:</strong><br>
                            @if ($lesson->attachment)
                                <a href="{{ asset('storage/' . $lesson->attachment) }}" target="_blank"
                                    class="btn btn-outline-success mb-2"><i class="bi bi-download"></i> Tải tài liệu</a>
                                <button class="btn btn-outline-primary mb-2 ml-2" type="button"
                                    onclick="openPreviewModal()">
                                    <i class="bi bi-eye"></i> Xem trước tài liệu
                                </button>
                                @php
                                    $ext = strtolower(pathinfo($lesson->attachment, PATHINFO_EXTENSION));
                                    $fileUrl = asset('storage/' . $lesson->attachment);
                                @endphp
                            @else
                                <span class="text-muted">Không có tài liệu</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Preview -->
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

        // Đóng modal khi click bên ngoài
        window.onclick = function(event) {
            var modal = document.getElementById('previewModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</x-layouts.dash-admin>
