<x-layouts.dash-admin active="submissions" title="@lang('general.grade_assignment')">
    @include('components.language')
    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-white shadow-sm rounded">
                    <li class="breadcrumb-item">
                        <a href="{{ route('grading.list') }}" wire:navigate class="text-decoration-none">
                            <i class="fas fa-arrow-left mr-1"></i>@lang('general.back_to_list')
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang('general.grade_assignment')</li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Assignment Header Card -->
                    <div class="card border-0 shadow-sm mb-4"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="card-body text-white p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h3 class="mb-2 font-weight-bold">
                                        <i class="fas fa-check-circle mr-2"></i>{{ $assignment->title }}
                                    </h3>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge badge-light text-dark">
                                            <i class="fas fa-graduation-cap mr-1"></i>
                                            {{ $assignment->classroom?->name ?? '-' }}
                                        </span>
                                        <span class="badge badge-warning">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            {{ $assignment->deadline ? $assignment->deadline->format('d/m/Y H:i') : '-' }}
                                        </span>
                                        <span class="badge badge-info">
                                            <i class="fas fa-users mr-1"></i>
                                            {{ $submissions->count() }} @lang('general.submissions')
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-right">
                                    <div class="text-center">
                                        <div class="h2 mb-0 font-weight-bold">
                                            {{ $submissions->where('score', '!=', null)->count() }}/{{ $submissions->count() }}
                                        </div>
                                        <small>@lang('general.graded')</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submissions List -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 text-primary font-weight-bold">
                                <i class="fas fa-list-alt mr-2"></i>@lang('general.submission_list')
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            @if ($submissions->count() > 0)
                                <div class="p-3">
                                    @foreach ($submissions as $submission)
                                        <div class="card mb-3 border-0 shadow-sm hover-shadow"
                                            style="transition: all 0.3s ease; {{ $submission->score !== null ? 'border-left: 4px solid #28a745 !important;' : 'border-left: 4px solid #6c757d !important;' }}">
                                            <div class="card-body p-4">
                                                <div class="row align-items-center">
                                                    <!-- Student Info -->
                                                    <div class="col-md-4 mb-3 mb-md-0">
                                                        <div class="d-flex align-items-center">
                                                            <div class="mr-3">
                                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                                    style="width: 50px; height: 50px; font-size: 20px;">
                                                                    {{ substr($submission->student?->user?->name ?? 'A', 0, 1) }}
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-1 font-weight-bold">
                                                                    {{ $submission->student?->user?->name ?? '-' }}</h6>
                                                                <div class="d-flex flex-wrap gap-1">
                                                                    <span class="badge badge-secondary badge-pill">
                                                                        @switch($submission->submission_type)
                                                                            @case('text')
                                                                                @lang('general.text')
                                                                            @break

                                                                            @case('essay')
                                                                                @lang('general.essay')
                                                                            @break

                                                                            @case('image')
                                                                                @lang('general.image')
                                                                            @break

                                                                            @case('audio')
                                                                                @lang('general.audio')
                                                                            @break

                                                                            @case('video')
                                                                                @lang('general.video')
                                                                            @break

                                                                            @default
                                                                                {{ $submission->submission_type }}
                                                                        @endswitch
                                                                    </span>
                                                                    @if ($submission->submitted_at)
                                                                        <span class="badge badge-info badge-pill">
                                                                            <i class="fas fa-clock mr-1"></i>
                                                                            {{ $submission->submitted_at->format('d/m/Y H:i') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Grading Status -->
                                                    <div class="col-md-2 mb-3 mb-md-0 text-center">
                                                        @if ($submission->score !== null)
                                                            <div class="text-success">
                                                                <i class="fas fa-check-circle fa-2x"></i>
                                                                <div class="small font-weight-bold">@lang('general.graded')
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="text-muted">
                                                                <i class="fas fa-hourglass-half fa-2x"></i>
                                                                <div class="small">@lang('general.not_graded')</div>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Score Input -->
                                                    <div class="col-md-2 mb-3 mb-md-0">
                                                        <div class="form-group mb-0">
                                                            <label
                                                                class="small text-muted mb-1">@lang('general.score')</label>
                                                            <input type="number" min="0" max="10"
                                                                step="0.1"
                                                                class="form-control form-control-sm text-center"
                                                                wire:model.defer="grading.{{ $submission->id }}.score"
                                                                placeholder="0-10" style="border-radius: 20px;"
                                                                oninput="if(this.value > 10) this.value = 10; if(this.value < 0) this.value = 0;">
                                                        </div>
                                                    </div>

                                                    <!-- Actions -->
                                                    <div class="col-md-4">
                                                        <div class="d-flex gap-2">
                                                            <button class="btn btn-outline-primary btn-sm flex-fill"
                                                                wire:click="viewSubmission({{ $submission->id }})"
                                                                style="border-radius: 20px;">
                                                                <i class="fas fa-eye mr-1"></i>@lang('general.view')
                                                            </button>
                                                            <button class="btn btn-success btn-sm flex-fill"
                                                                wire:click="saveGrade({{ $submission->id }})"
                                                                style="border-radius: 20px;">
                                                                <i class="fas fa-save mr-1"></i>@lang('general.save')
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Feedback -->
                                                <div class="mt-3">
                                                    <div class="form-group mb-0">
                                                        <label class="small text-muted mb-1">@lang('general.feedback')</label>
                                                        <textarea class="form-control form-control-sm" wire:model.defer="grading.{{ $submission->id }}.feedback"
                                                            placeholder="@lang('general.feedback')..." rows="2" style="border-radius: 10px; resize: none;"></textarea>
                                                    </div>
                                                </div>

                                                <!-- Previous Feedback Display -->
                                                @if ($submission->score !== null && $submission->feedback)
                                                    <div class="mt-3 p-3 bg-light rounded"
                                                        style="border-left: 3px solid #28a745;">
                                                        <div class="small text-muted mb-1">
                                                            <i class="fas fa-comment mr-1"></i>@lang('general.previous_feedback'):
                                                        </div>
                                                        <div class="font-weight-bold">{{ $submission->feedback }}</div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Success/Error Messages -->
                                @if (session()->has('success'))
                                    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                @endif

                                @if (session()->has('error'))
                                    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
                                        <button type="button" class="close" data-dismiss="alert">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">@lang('general.no_submissions')</h5>
                                    <p class="text-muted">@lang('general.no_submissions_desc')</p>
                                </div>
                            @endif
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
                            <li>Bấm <span class="badge bg-primary"><i class="bi bi-eye"></i> Xem</span> để xem nội
                                dung
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
                        <div><b>Mô tả:</b> <span
                                class="text-muted">{{ $assignment->description ?? 'Không có' }}</span>
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
