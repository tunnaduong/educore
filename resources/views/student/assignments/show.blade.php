<x-layouts.dash-student active="assignments">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-journal-text mr-2"></i>{{ $assignment->title }}
                    </h4>
                    <p class="text-muted mb-0">{{ $assignment->classroom?->name ?? 'N/A' }} -
                        @if ($assignment->classroom->teachers->count())
                            {{ $assignment->classroom->teachers->pluck('name')->join(', ') }}
                        @else
                            Chưa có giáo viên
                        @endif
                    </p>
                </div>
                <div class="text-end">
                    @php
                        $status = $this->getStatusBadge();
                    @endphp
                    <span
                        class="badge {{ $status['class'] === 'bg-green-100 text-green-800'
                            ? 'bg-success'
                            : ($status['class'] === 'bg-red-100 text-red-800'
                                ? 'bg-danger'
                                : 'bg-warning text-dark') }}">
                        {{ $status['text'] }}
                    </span>
                    <div class="small text-muted mt-1">{{ $this->getTimeRemaining() }}</div>
                </div>
            </div>
        </div>

        <!-- Assignment Details -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle mr-2"></i>Chi tiết bài tập
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <h6>Mô tả bài tập:</h6>
                            <div class="border rounded p-3 bg-light">
                                {!! nl2br(e($assignment->description)) !!}
                            </div>
                        </div>

                        @if ($assignment->types)
                            <div class="mb-3">
                                <h6>Loại bài tập:</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($assignment->types as $type)
                                        <span class="badge bg-primary">
                                            @switch($type)
                                                @case('text')
                                                    Điền từ
                                                @break

                                                @case('essay')
                                                    Tự luận
                                                @break

                                                @case('image')
                                                    Upload ảnh (bài viết tay)
                                                @break

                                                @case('audio')
                                                    Ghi âm luyện nói
                                                @break

                                                @case('video')
                                                    Video luyện nói
                                                @break

                                                @default
                                                    {{ $type }}
                                            @endswitch
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Thông tin bài tập</h6>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="bi bi-calendar3 mr-2"></i>
                                        <strong>Hạn nộp:</strong><br>
                                        {{ $assignment->deadline->format('d/m/Y H:i') }}
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-people mr-2"></i>
                                        <strong>Lớp học:</strong><br>
                                        {{ $assignment->classroom?->name ?? 'N/A' }}
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-person mr-2"></i>
                                        <strong>Giảng viên:</strong><br>
                                        @if ($assignment->classroom->teachers->count())
                                            {{ $assignment->classroom->teachers->pluck('name')->join(', ') }}
                                        @else
                                            Chưa có giáo viên
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>

                        @if ($assignment->attachment_path)
                            <div class="card mt-3">
                                <div class="card-body">
                                    <h6 class="card-title">Tài liệu đính kèm</h6>
                                    <a href="{{ Storage::url($assignment->attachment_path) }}" target="_blank"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-download mr-2"></i>Tải xuống tài liệu
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if ($assignment->video_path)
                            <div class="card mt-3">
                                <div class="card-body">
                                    <h6 class="card-title">Video hướng dẫn</h6>
                                    <video controls class="w-100 rounded">
                                        <source src="{{ Storage::url($assignment->video_path) }}" type="video/mp4">
                                        Trình duyệt của bạn không hỗ trợ video.
                                    </video>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Submission Section -->
        @if ($submissions && $submissions->count())
            @foreach ($submissions as $submission)
                <div class="card shadow-sm mb-4 border-success">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-check-circle mr-2"></i>Bài nộp của bạn -
                            @switch($submission->submission_type)
                                @case('text')
                                    Điền từ
                                @break

                                @case('essay')
                                    Tự luận
                                @break

                                @case('image')
                                    Ảnh
                                @break

                                @case('audio')
                                    Âm thanh
                                @break

                                @case('video')
                                    Video
                                @break

                                @default
                                    {{ $submission->submission_type }}
                            @endswitch
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <h6>Nội dung bài nộp:</h6>
                                    @php
                                        $content = $submission->content;
                                        $isFile =
                                            filter_var($content, FILTER_VALIDATE_URL) ||
                                            str_starts_with($content, 'assignments/');
                                    @endphp

                                    @if ($isFile)
                                        @php
                                            $extension = pathinfo($content, PATHINFO_EXTENSION);
                                        @endphp

                                        @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                            <div class="border rounded p-3">
                                                <h6>Ảnh bài viết:</h6>
                                                <img src="{{ Storage::url($content) }}" alt="Bài viết"
                                                    class="img-fluid rounded">
                                            </div>
                                        @elseif(in_array($extension, ['mp3', 'wav', 'm4a']))
                                            <div class="border rounded p-3">
                                                <h6>File âm thanh:</h6>
                                                <audio controls class="w-100">
                                                    <source src="{{ Storage::url($content) }}"
                                                        type="audio/{{ $extension }}">
                                                    Trình duyệt của bạn không hỗ trợ âm thanh.
                                                </audio>
                                            </div>
                                        @elseif(in_array($extension, ['mp4', 'avi', 'mov']))
                                            <div class="border rounded p-3">
                                                <h6>File video:</h6>
                                                <video controls class="w-100 rounded">
                                                    <source src="{{ Storage::url($content) }}"
                                                        type="video/{{ $extension }}">
                                                    Trình duyệt của bạn không hỗ trợ video.
                                                </video>
                                            </div>
                                        @endif
                                    @else
                                        <div class="border rounded p-3 bg-light">
                                            @if ($submission->submission_type === 'essay')
                                                <div class="prose">
                                                    {!! nl2br(e($content)) !!}
                                                </div>
                                            @else
                                                {!! nl2br(e($content)) !!}
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Thông tin nộp bài</h6>
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2">
                                                <i class="bi bi-clock mr-2"></i>
                                                <strong>Nộp lúc:</strong><br>
                                                {{ $submission->submitted_at->format('d/m/Y H:i') }}
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-file-earmark mr-2"></i>
                                                <strong>Loại bài nộp:</strong><br>
                                                @switch($submission->submission_type)
                                                    @case('text')
                                                        Điền từ
                                                    @break

                                                    @case('essay')
                                                        Tự luận
                                                    @break

                                                    @case('image')
                                                        Upload ảnh
                                                    @break

                                                    @case('audio')
                                                        Ghi âm
                                                    @break

                                                    @case('video')
                                                        Video
                                                    @break

                                                    @default
                                                        {{ $submission->submission_type }}
                                                @endswitch
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                @if ($submission->score !== null)
                                    <div class="card mt-3 border-primary">
                                        <div class="card-body text-center">
                                            <h6 class="card-title">Điểm số</h6>
                                            <div class="display-6 text-primary fw-bold">{{ $submission->score }}/10
                                            </div>
                                            @if ($submission->feedback)
                                                <div class="mt-2">
                                                    <small class="text-muted">Nhận xét:</small><br>
                                                    <small>{{ $submission->feedback }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="card mt-3 border-warning">
                                        <div class="card-body text-center">
                                            <i class="bi bi-hourglass-split fs-1 text-warning mb-2"></i>
                                            <h6 class="card-title">Chưa chấm điểm</h6>
                                            <small class="text-muted">Bài tập đang được giảng viên chấm điểm</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <!-- Action Buttons -->
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    @if ($this->canSubmit())
                        <a href="{{ route('student.assignments.submit', $assignment->id) }}"
                            class="btn btn-primary btn-lg" wire:navigate>
                            <i class="bi bi-pencil mr-2"></i>Bắt đầu làm bài
                        </a>
                    @elseif($this->isOverdue())
                        <div class="text-center">
                            <i class="bi bi-exclamation-triangle fs-1 text-danger mb-3"></i>
                            <h5 class="text-danger">Bài tập đã quá hạn</h5>
                            <p class="text-muted">Bạn không thể nộp bài tập này nữa.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if ($submissions->count() > 0 && $this->canRedo())
            <div class="text-center mb-3">
                <button class="btn btn-warning" wire:click="redoSubmission">
                    <i class="bi bi-arrow-clockwise mr-2"></i>Nộp lại bài
                </button>
            </div>
        @endif

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="{{ route('student.assignments.overview') }}" class="btn btn-outline-secondary" wire:navigate>
                <i class="bi bi-arrow-left mr-2"></i>Quay lại danh sách
            </a>
        </div>
    </div>
</x-layouts.dash-student>
