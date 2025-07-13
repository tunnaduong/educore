<x-layouts.dash-admin active="submissions">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <h4 class="card-title mb-2 text-primary">
                            <i class="bi bi-journal-check me-2"></i>Chấm bài: <span
                                class="fw-bold">{{ $assignment->title }}</span>
                        </h4>
                        <div class="mb-2">
                            <span class="badge bg-info text-dark me-2"><i class="bi bi-mortarboard"></i> Lớp:
                                {{ $assignment->classroom?->name ?? '-' }}</span>
                            <span class="badge bg-warning text-dark"><i class="bi bi-calendar3"></i> Hạn nộp:
                                {{ $assignment->deadline ? $assignment->deadline->format('d/m/Y H:i') : '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex align-items-center">
                        <i class="bi bi-people me-2 text-primary"></i>
                        <span class="mb-0 text-primary">Danh sách bài nộp</span>
                    </div>
                    <div class="card-body p-0">
                        @if ($submissions->count() > 0)
                            <div class="row g-3">
                                @foreach ($submissions as $submission)
                                    <div class="col-12">
                                        <div
                                            class="card mb-2 shadow-sm border border-2 {{ $submission->score !== null ? 'border-success' : 'border-secondary' }}">
                                            <div class="card-body d-flex flex-column flex-xxl-row gap-3">
                                                <div class="d-flex align-items-center mb-3 mb-md-0"
                                                    style="min-width:220px">
                                                    <div class="me-3">
                                                        <i class="bi bi-person-circle fs-4 text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold fs-5">
                                                            {{ $submission->student?->user?->name ?? '-' }}</div>
                                                        <div>
                                                            <span class="badge bg-secondary">
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
                                                            </span>
                                                            @if ($submission->submitted_at)
                                                                <span class="badge bg-info text-dark ms-1"><i
                                                                        class="bi bi-clock"></i>
                                                                    {{ $submission->submitted_at->format('d/m/Y H:i') }}</span>
                                                            @endif
                                                            @if ($submission->score !== null)
                                                                <span class="badge bg-success ms-1"><i
                                                                        class="bi bi-check-circle"></i> Đã chấm</span>
                                                            @else
                                                                <span class="badge bg-secondary ms-1"><i
                                                                        class="bi bi-hourglass-split"></i> Chưa
                                                                    chấm</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <style>
                                                    @media (min-width: 1400px) {
                                                        .btn-xxl-h70 {
                                                            height: 70px !important;
                                                        }
                                                    }
                                                </style>
                                                <div
                                                    class="d-flex flex-column flex-xxl-row align-items-stretch align-items-xxl-center gap-2 flex-grow-1 justify-content-md-end flex-fill">

                                                    <div class="d-flex flex-row flex-xxl-column gap-2">
                                                        <input type="number" min="0" max="10"
                                                            step="0.1"
                                                            class="form-control form-control-sm text-center mb-2 mb-md-0"
                                                            wire:model.defer="grading.{{ $submission->id }}.score"
                                                            placeholder="Điểm (0-10)"
                                                            oninput="if(this.value > 10) this.value = 10; if(this.value < 0) this.value = 0;">
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center mb-2 mb-md-0"
                                                            wire:model.defer="grading.{{ $submission->id }}.feedback"
                                                            placeholder="Nhận xét...">
                                                    </div>
                                                    <div class="row gx-2">
                                                        <div class="col-6">
                                                            <button
                                                                class="btn btn-sm btn-outline-primary mb-2 mb-md-0 btn-xxl-h70 w-100"
                                                                wire:click="viewSubmission({{ $submission->id }})">
                                                                <i class="bi bi-eye"></i><br
                                                                    class="d-none d-xxl-inline">
                                                                Xem bài nộp
                                                            </button>
                                                        </div>
                                                        <div class="col-6">
                                                            <button
                                                                class="btn btn-sm btn-success px-3 mb-2 mb-md-0 btn-xxl-h70 w-100"
                                                                wire:click="saveGrade({{ $submission->id }})">
                                                                <i class="bi bi-save"></i><br
                                                                    class="d-none d-xxl-inline">
                                                                Lưu
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($submission->score !== null && $submission->feedback)
                                                <div class="card-footer bg-light text-success small ps-5">
                                                    <i class="bi bi-chat-left-quote"></i> Nhận xét: <span
                                                        class="fw-semibold">{{ $submission->feedback }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if (session()->has('success'))
                                <div class="alert alert-success mt-3 text-center fw-bold shadow-sm">
                                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                                </div>
                            @endif

                            @if (session()->has('error'))
                                <div class="alert alert-danger mt-3 text-center fw-bold shadow-sm">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info text-center m-0 py-4">
                                <i class="bi bi-info-circle fs-3"></i><br>Chưa có bài nộp nào.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-none d-lg-block">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-lightbulb me-2"></i>Hướng dẫn chấm bài
                    </div>
                    <div class="card-body small">
                        <ul class="mb-2 ps-3">
                            <li>Bấm <span class="badge bg-primary"><i class="bi bi-eye"></i> Xem</span> để xem nội dung
                                bài nộp.</li>
                            <li>Nhập điểm (0-10) và nhận xét cho từng bài nộp.</li>
                            <li>Bấm <span class="badge bg-success"><i class="bi bi-save"></i></span> để lưu lại.</li>
                            <li>Trạng thái <span class="badge bg-success">Đã chấm</span> sẽ hiển thị khi đã nhập điểm.
                            </li>
                        </ul>
                        <div class="alert alert-info p-2 mb-0">
                            <i class="bi bi-info-circle"></i> Chỉ giáo viên hoặc admin mới có quyền chấm bài.
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <i class="bi bi-journal-text me-2"></i>Thông tin bài tập
                    </div>
                    <div class="card-body small">
                        <div><b>Tiêu đề:</b> {{ $assignment->title }}</div>
                        <div><b>Lớp:</b> {{ $assignment->classroom?->name ?? '-' }}</div>
                        <div><b>Hạn nộp:</b>
                            {{ $assignment->deadline ? $assignment->deadline->format('d/m/Y H:i') : '-' }}</div>
                        <div><b>Mô tả:</b> <span class="text-muted">{{ $assignment->description ?? 'Không có' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal xem nội dung bài nộp -->
    @if ($showModal && $selectedSubmission)
        <div class="modal fade show" style="display: block;" tabindex="-1" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-eye me-2"></i>Xem bài nộp của
                            {{ $selectedSubmission->student->user->name }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>Bài tập:</strong> {{ $selectedSubmission->assignment->title }}
                            </div>
                            <div class="col-md-6">
                                <strong>Loại bài nộp:</strong>
                                <span class="badge bg-secondary">
                                    @switch($selectedSubmission->submission_type)
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
                                            {{ $selectedSubmission->submission_type }}
                                    @endswitch
                                </span>
                            </div>
                        </div>

                        <!-- Đề bài -->
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">
                                    <i class="bi bi-journal-text me-2"></i>Đề bài
                                </h6>
                            </div>
                            <div class="card-body">
                                <h6 class="fw-bold mb-2">{{ $selectedSubmission->assignment->title }}</h6>
                                @if ($selectedSubmission->assignment->description)
                                    <div class="text-muted">
                                        {!! nl2br(e($selectedSubmission->assignment->description)) !!}
                                    </div>
                                @else
                                    <div class="text-muted">Không có mô tả</div>
                                @endif

                                @if ($selectedSubmission->assignment->attachment)
                                    <div class="mt-2">
                                        <strong>File đính kèm:</strong>
                                        <a href="{{ asset('storage/' . $selectedSubmission->assignment->attachment) }}"
                                            target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                                            <i class="bi bi-download me-1"></i>Tải file đề bài
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Đáp án của học viên -->
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">
                                    <i class="bi bi-person-check me-2"></i>Đáp án của
                                    {{ $selectedSubmission->student->user->name }}
                                </h6>
                            </div>
                            <div class="card-body">
                                @if ($selectedSubmission->submission_type === 'text' || $selectedSubmission->submission_type === 'essay')
                                    <h6 class="fw-bold mb-2">
                                        @if ($selectedSubmission->submission_type === 'essay')
                                            Nội dung tự luận:
                                        @else
                                            Nội dung điền từ:
                                        @endif
                                    </h6>
                                    <div class="border rounded p-3 bg-white">
                                        {!! nl2br(e($selectedSubmission->content)) !!}
                                    </div>
                                @elseif($selectedSubmission->submission_type === 'image' && $selectedSubmission->content)
                                    <h6 class="fw-bold mb-2">Ảnh bài nộp:</h6>
                                    <div class="text-center">
                                        <img src="{{ asset('storage/' . $selectedSubmission->content) }}"
                                            class="img-fluid rounded" style="max-height: 400px;" alt="Ảnh bài nộp">
                                    </div>
                                @elseif($selectedSubmission->submission_type === 'video' && $selectedSubmission->content)
                                    <h6 class="fw-bold mb-2">Video bài nộp:</h6>
                                    <div class="text-center">
                                        <video controls class="w-100" style="max-height: 400px;">
                                            <source src="{{ asset('storage/' . $selectedSubmission->content) }}"
                                                type="video/mp4">
                                            Trình duyệt không hỗ trợ video.
                                        </video>
                                    </div>
                                @elseif($selectedSubmission->submission_type === 'audio' && $selectedSubmission->content)
                                    <h6 class="fw-bold mb-2">Âm thanh bài nộp:</h6>
                                    <div class="text-center">
                                        <audio controls class="w-100">
                                            <source src="{{ asset('storage/' . $selectedSubmission->content) }}"
                                                type="audio/mpeg">
                                            Trình duyệt không hỗ trợ audio.
                                        </audio>
                                    </div>
                                @elseif($selectedSubmission->content)
                                    <h6 class="fw-bold mb-2">File đính kèm:</h6>
                                    @if (in_array(pathinfo($selectedSubmission->content, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                        <!-- Hiển thị ảnh -->
                                        <div class="text-center">
                                            <img src="{{ asset('storage/' . $selectedSubmission->content) }}"
                                                class="img-fluid rounded" style="max-height: 400px;"
                                                alt="Ảnh bài nộp">
                                        </div>
                                    @elseif(in_array(pathinfo($selectedSubmission->content, PATHINFO_EXTENSION), ['mp4', 'avi', 'mov', 'wmv']))
                                        <!-- Hiển thị video -->
                                        <div class="text-center">
                                            <video controls class="w-100" style="max-height: 400px;">
                                                <source src="{{ asset('storage/' . $selectedSubmission->content) }}"
                                                    type="video/mp4">
                                                Trình duyệt không hỗ trợ video.
                                            </video>
                                        </div>
                                    @elseif(in_array(pathinfo($selectedSubmission->content, PATHINFO_EXTENSION), ['mp3', 'wav', 'ogg']))
                                        <!-- Hiển thị audio -->
                                        <div class="text-center">
                                            <audio controls class="w-100">
                                                <source src="{{ asset('storage/' . $selectedSubmission->content) }}"
                                                    type="audio/mpeg">
                                                Trình duyệt không hỗ trợ audio.
                                            </audio>
                                        </div>
                                    @else
                                        <!-- File khác -->
                                        <div class="text-center">
                                            <i class="bi bi-file-earmark fs-1 text-muted mb-2"></i>
                                            <p class="text-muted">{{ basename($selectedSubmission->content) }}</p>
                                            <a href="{{ asset('storage/' . $selectedSubmission->content) }}"
                                                target="_blank" class="btn btn-primary">
                                                <i class="bi bi-download me-2"></i>Tải xuống
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center text-muted">
                                        <i class="bi bi-file-earmark-x fs-1 mb-2"></i>
                                        <p>Không có nội dung bài nộp</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">
                            <i class="bi bi-x-circle me-2"></i>Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</x-layouts.dash-admin>
