<x-layouts.dash-student active="assignments">
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-pencil me-2"></i>Làm bài tập: {{ $assignment->title }}
                    </h4>
                    <p class="text-muted mb-0">{{ $assignment->classroom->name }} -
                        @if ($assignment->classroom->teachers->count())
                            {{ $assignment->classroom->teachers->pluck('name')->join(', ') }}
                        @else
                            Chưa có giáo viên
                        @endif
                    </p>
                </div>
                <div class="text-end">
                    <div class="badge bg-warning text-dark">Hạn nộp: {{ $assignment->deadline->format('d/m/Y H:i') }}
                    </div>
                    <div class="small text-muted mt-1">{{ $this->getTimeRemaining() }}</div>
                </div>
            </div>
        </div>

        <!-- Assignment Info -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Thông tin bài tập
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
                                <h6>Loại bài tập cần làm:</h6>
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
                        @if ($assignment->attachment_path)
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Tài liệu đính kèm</h6>
                                    <a href="{{ Storage::url($assignment->attachment_path) }}" target="_blank"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-download me-2"></i>Tải xuống tài liệu
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

        <!-- Submission Form -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">
                    <i class="bi bi-upload me-2"></i>Nộp bài tập
                </h6>
            </div>
            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                    </div>
                @endif
                <form wire:submit="submitAssignment">
                    <!-- Submission Type Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Chọn loại bài nộp:</label>
                        <div class="row g-3">
                            @if (in_array('text', $assignment->types))
                                <div class="col-md-4">
                                    <div class="form-check border rounded p-3 h-100">
                                        <input class="form-check-input ms-0 me-3" type="radio"
                                            wire:model.live="submissionType" id="type_text" value="text">
                                        <label class="form-check-label" for="type_text">
                                            <i class="bi bi-pencil-square text-primary me-2"></i>
                                            <strong>Điền từ</strong>
                                            <div class="small text-muted mt-1">Nhập câu trả lời ngắn</div>
                                        </label>
                                    </div>
                                </div>
                            @endif

                            @if (in_array('essay', $assignment->types))
                                <div class="col-md-4">
                                    <div class="form-check border rounded p-3 h-100">
                                        <input class="form-check-input ms-0 me-3" type="radio"
                                            wire:model.live="submissionType" id="type_essay" value="essay">
                                        <label class="form-check-label" for="type_essay">
                                            <i class="bi bi-file-text text-success me-2"></i>
                                            <strong>Bài luận</strong>
                                            <div class="small text-muted mt-1">Viết bài luận dài</div>
                                        </label>
                                    </div>
                                </div>
                            @endif

                            @if (in_array('image', $assignment->types))
                                <div class="col-md-4">
                                    <div class="form-check border rounded p-3 h-100">
                                        <input class="form-check-input ms-0 me-3" type="radio"
                                            wire:model.live="submissionType" id="type_image" value="image">
                                        <label class="form-check-label" for="type_image">
                                            <i class="bi bi-image text-info me-2"></i>
                                            <strong>Upload ảnh</strong>
                                            <div class="small text-muted mt-1">Tải lên ảnh bài viết tay</div>
                                        </label>
                                    </div>
                                </div>
                            @endif

                            @if (in_array('audio', $assignment->types))
                                <div class="col-md-4">
                                    <div class="form-check border rounded p-3 h-100">
                                        <input class="form-check-input ms-0 me-3" type="radio"
                                            wire:model.live="submissionType" id="type_audio" value="audio">
                                        <label class="form-check-label" for="type_audio">
                                            <i class="bi bi-mic text-warning me-2"></i>
                                            <strong>Ghi âm</strong>
                                            <div class="small text-muted mt-1">Thu âm luyện nói</div>
                                        </label>
                                    </div>
                                </div>
                            @endif

                            @if (in_array('video', $assignment->types))
                                <div class="col-md-4">
                                    <div class="form-check border rounded p-3 h-100">
                                        <input class="form-check-input ms-0 me-3" type="radio"
                                            wire:model.live="submissionType" id="type_video" value="video">
                                        <label class="form-check-label" for="type_video">
                                            <i class="bi bi-camera-video text-danger me-2"></i>
                                            <strong>Video</strong>
                                            <div class="small text-muted mt-1">Quay video luyện nói</div>
                                        </label>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @error('submissionType')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Content Input Based on Type -->
                    @if ($submissionType)
                        <div class="mb-4">
                            @switch($submissionType)
                                @case('text')
                                    <label for="content" class="form-label fw-bold">Nội dung bài làm:</label>
                                    <textarea wire:model.live="content" id="content" rows="4"
                                        class="form-control @error('content') is-invalid @enderror" placeholder="Nhập câu trả lời của bạn..."></textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @break

                                @case('essay')
                                    <label for="essay" class="form-label fw-bold">Bài luận:</label>
                                    <textarea wire:model.live="essay" id="essay" rows="12"
                                        class="form-control @error('essay') is-invalid @enderror" placeholder="Viết bài luận của bạn..."></textarea>
                                    <div class="form-text">Viết bài luận chi tiết theo yêu cầu của bài tập.</div>
                                    @error('essay')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @break

                                @case('image')
                                    <label for="imageFile" class="form-label fw-bold">Upload ảnh bài viết:</label>
                                    <input type="file" wire:model.live="imageFile" id="imageFile" accept="image/*"
                                        class="form-control @error('imageFile') is-invalid @enderror">
                                    <div class="form-text">Chỉ chấp nhận file ảnh (JPG, PNG, GIF, WEBP). Kích thước tối đa
                                        10MB.</div>
                                    @error('imageFile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if ($imageFile)
                                        <div class="mt-3">
                                            <img src="{{ $imageFile->temporaryUrl() }}" alt="Preview"
                                                class="img-fluid rounded" style="max-height: 300px;">
                                        </div>
                                    @endif
                                @break

                                @case('audio')
                                    <label for="audioFile" class="form-label fw-bold">Upload file âm thanh:</label>
                                    <input type="file" wire:model.live="audioFile" id="audioFile" accept="audio/*"
                                        class="form-control @error('audioFile') is-invalid @enderror">
                                    <div class="form-text">Chỉ chấp nhận file âm thanh (MP3, WAV, M4A). Kích thước tối đa 10MB.
                                    </div>
                                    @error('audioFile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if ($audioFile)
                                        <div class="mt-3">
                                            <audio controls class="w-100">
                                                <source src="{{ $audioFile->temporaryUrl() }}"
                                                    type="audio/{{ $audioFile->getClientOriginalExtension() }}">
                                                Trình duyệt của bạn không hỗ trợ âm thanh.
                                            </audio>
                                        </div>
                                    @endif
                                @break

                                @case('video')
                                    <label for="videoFile" class="form-label fw-bold">Upload file video:</label>
                                    <input type="file" wire:model.live="videoFile" id="videoFile" accept="video/*"
                                        class="form-control @error('videoFile') is-invalid @enderror">
                                    <div class="form-text">Chỉ chấp nhận file video (MP4, AVI, MOV). Kích thước tối đa 200MB.
                                    </div>
                                    @error('videoFile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if ($videoFile)
                                        <div class="mt-3">
                                            <video controls class="w-100 rounded">
                                                <source src="{{ $videoFile->temporaryUrl() }}"
                                                    type="video/{{ $videoFile->getClientOriginalExtension() }}">
                                                Trình duyệt của bạn không hỗ trợ video.
                                            </video>
                                        </div>
                                    @endif
                                @break
                            @endswitch
                        </div>
                    @endif

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('student.assignments.show', $assignment->id) }}"
                            class="btn btn-outline-secondary" wire:navigate>
                            <i class="bi bi-arrow-left me-2"></i>Quay lại
                        </a>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                <i class="bi bi-upload me-2"></i>Nộp bài
                            </span>
                            <span wire:loading>
                                <i class="bi bi-hourglass-split me-2"></i>Đang tải...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.dash-student>
