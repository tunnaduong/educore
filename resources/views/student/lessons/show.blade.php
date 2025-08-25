<x-layouts.dash-student active="lessons">
    @include('components.language')
    <div class="row">
        <div class="container-fluid">
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 text-primary fs-4">
                            <i class="bi bi-book mr-2"></i>{{ $lesson->title }}
                        </h4>
                        <p class="text-muted mb-0">Chi tiết bài học</p>
                    </div>
                    <div>
                        <a href="{{ route('student.lessons.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="mb-3">
                        <strong class="text-muted">Mô tả:</strong>
                        <div class="mt-1">{!! nl2br(e($lesson->description)) !!}</div>
                    </div>

                    @php
                        $isYoutube = $lesson->video && Str::contains($lesson->video, ['youtube.com', 'youtu.be']);
                        $isDrive = $lesson->video && Str::contains($lesson->video, 'drive.google.com/file/d/');
                        $youtubeId = null;
                        $driveId = null;
                        if ($isYoutube) {
                            if (Str::contains($lesson->video, 'youtu.be/')) {
                                $youtubeId = Str::after($lesson->video, 'youtu.be/');
                                $youtubeId = Str::before($youtubeId, '?');
                            } elseif (Str::contains($lesson->video, 'v=')) {
                                $youtubeId = Str::after($lesson->video, 'v=');
                                $youtubeId = Str::before($youtubeId, '&');
                            }
                        }
                        if ($isDrive) {
                            $driveId = Str::between($lesson->video, '/file/d/', '/');
                        }
                    @endphp
                    @if ($lesson->video)
                        <div class="mb-3">
                            <strong class="text-muted">Video bài học:</strong><br>
                            @if ($isYoutube && $youtubeId)
                                <div class="ratio ratio-16x9 rounded overflow-hidden mb-2"
                                    style="position: relative;padding-bottom: 56.25%;">
                                    <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}" frameborder="0"
                                        allowfullscreen
                                        style="position: absolute;top: 0;left: 0; width: 100%;height: 100%;"></iframe>
                                </div>
                            @elseif ($isDrive && $driveId)
                                <div class="ratio ratio-16x9 rounded overflow-hidden mb-2"
                                    style="position: relative;padding-bottom: 56.25%;">
                                    <iframe src="https://drive.google.com/file/d/{{ $driveId }}/preview"
                                        style="position: absolute;top: 0;left: 0; width: 100%;height: 100%;"
                                        allow="autoplay"></iframe>
                                </div>
                            @else
                                <a href="{{ $lesson->video }}" target="_blank" class="btn btn-outline-primary"><i
                                        class="bi bi-play-circle"></i> Xem video</a>
                            @endif
                        </div>
                    @endif

                    @if ($lesson->attachment)
                        <div class="mb-3">
                            <strong class="text-muted">Tài liệu đính kèm:</strong><br>
                            <a href="{{ asset('storage/' . $lesson->attachment) }}" target="_blank"
                                class="btn btn-outline-success mb-2">
                                <i class="bi bi-download"></i> Tải tài liệu
                            </a>
                            <button class="btn btn-outline-primary mb-2 ml-2" type="button"
                                onclick="openPreviewModal()">
                                <i class="bi bi-eye"></i> Xem trước tài liệu
                            </button>
                            @php
                                $ext = strtolower(pathinfo($lesson->attachment, PATHINFO_EXTENSION));
                                $fileUrl = asset('storage/' . $lesson->attachment);
                            @endphp
                        </div>
                    @endif
                </div>
            </div>
            <div class="mt-4">
                @if (session()->has('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (!$completed)
                    <button wire:click="markAsCompleted" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Đánh dấu đã hoàn thành
                    </button>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="bi bi-check2-circle"></i> Bạn đã hoàn thành bài học này!
                    </div>
                @endif
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
</x-layouts.dash-student>
