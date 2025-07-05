<x-layouts.dash-admin active="submissions">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <h4 class="card-title mb-2 text-primary">
                            <i class="bi bi-journal-check me-2"></i>Chấm bài: <span class="fw-bold">{{ $assignment->title }}</span>
                        </h4>
                        <div class="mb-2">
                            <span class="badge bg-info text-dark me-2"><i class="bi bi-mortarboard"></i> Lớp: {{ $assignment->classroom?->name ?? '-' }}</span>
                            <span class="badge bg-warning text-dark"><i class="bi bi-calendar3"></i> Hạn nộp: {{ $assignment->deadline ? $assignment->deadline->format('d/m/Y H:i') : '-' }}</span>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex align-items-center">
                        <i class="bi bi-people me-2 text-primary"></i>
                        <h5 class="mb-0 text-primary">Danh sách bài nộp</h5>
                    </div>
                    <div class="card-body p-0">
                        @if($submissions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Học viên</th>
                                            <th>Thời gian nộp</th>
                                            <th>Nội dung</th>
                                            <th class="text-center">Điểm</th>
                                            <th class="text-center">Nhận xét</th>
                                            <th class="text-center">Trạng thái</th>
                                            <th class="text-center">Lưu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($submissions as $submission)
                                            <tr>
                                                <td>
                                                    <span class="fw-semibold"><i class="bi bi-person-circle me-1"></i>{{ $submission->student?->name ?? '-' }}</span>
                                                </td>
                                                <td>
                                                    <span class="small text-muted">
                                                        <i class="bi bi-clock me-1"></i>{{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : '-' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($submission->content)
                                                        <a href="{{ asset('storage/' . $submission->content) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-file-earmark-arrow-down"></i> Tải file
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center" style="max-width:90px;">
                                                    <input type="number" min="0" max="100" step="0.1" class="form-control form-control-sm text-center" wire:model.defer="grading.{{ $submission->id }}.score" style="width:80px;display:inline-block;">
                                                </td>
                                                <td class="text-center" style="max-width:180px;">
                                                    <input type="text" class="form-control form-control-sm text-center" wire:model.defer="grading.{{ $submission->id }}.feedback" style="width:170px;display:inline-block;">
                                                </td>
                                                <td class="text-center">
                                                    @if($submission->score !== null)
                                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Đã chấm</span>
                                                    @else
                                                        <span class="badge bg-secondary"><i class="bi bi-hourglass-split"></i> Chưa chấm</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-success btn-sm px-3" wire:click="saveGrade({{ $submission->id }})">
                                                        <i class="bi bi-save"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @if($submission->score !== null && $submission->feedback)
                                                <tr>
                                                    <td colspan="7" class="bg-light text-success small ps-5">
                                                        <i class="bi bi-chat-left-quote"></i> Nhận xét: <span class="fw-semibold">{{ $submission->feedback }}</span>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if (session()->has('success'))
                                <div class="alert alert-success mt-3 text-center fw-bold shadow-sm">
                                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
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
                            <li>Nhập điểm (0-100) và nhận xét cho từng bài nộp.</li>
                            <li>Bấm <span class="badge bg-success"><i class="bi bi-save"></i></span> để lưu lại.</li>
                            <li>Trạng thái <span class="badge bg-success">Đã chấm</span> sẽ hiển thị khi đã nhập điểm.</li>
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
                        <div><b>Hạn nộp:</b> {{ $assignment->deadline ? $assignment->deadline->format('d/m/Y H:i') : '-' }}</div>
                        <div><b>Mô tả:</b> <span class="text-muted">{{ $assignment->description ?? 'Không có' }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
