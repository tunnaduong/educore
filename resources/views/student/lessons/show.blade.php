<x-layouts.dash-student active="courses">
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
                            <div class="ratio ratio-16x9 rounded overflow-hidden mb-2">
                                <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}" frameborder="0"
                                    allowfullscreen></iframe>
                            </div>
                        @elseif ($isDrive && $driveId)
                            <div class="ratio ratio-16x9 rounded overflow-hidden mb-2">
                                <iframe src="https://drive.google.com/file/d/{{ $driveId }}/preview" width="640"
                                    height="480" allow="autoplay"></iframe>
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
                            class="btn btn-outline-success">
                            <i class="bi bi-download"></i> Tải tài liệu
                        </a>
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
</x-layouts.dash-student>
