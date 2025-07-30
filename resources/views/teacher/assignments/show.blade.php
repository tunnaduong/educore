<x-layouts.dash-teacher active="assignments">
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('teacher.assignments.index') }}" wire:navigate
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách bài tập
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-journal-text me-2"></i>Chi tiết bài tập
            </h4>
            <p class="text-muted mb-0">{{ $assignment->title }}</p>
        </div>
        <div class="row">
            <!-- Thông tin bài tập -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-info-circle me-2"></i>Thông tin bài tập
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Tiêu đề</label>
                            <div class="fw-medium">{{ $assignment->title }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Lớp học</label>
                            <div class="fw-medium">{{ $classroom->name ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Hạn nộp</label>
                            <div class="fw-medium">
                                {{ $assignment->deadline ? $assignment->deadline->format('d/m/Y H:i') : '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Ngày giao</label>
                            <div class="fw-medium">{{ $assignment->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Mô tả</label>
                            <div class="fw-medium">{!! nl2br(e($assignment->description)) !!}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">File đính kèm</label>
                            <div class="fw-medium">
                                @if ($assignment->attachment_path)
                                    <a href="{{ asset('storage/' . $assignment->attachment_path) }}" target="_blank"
                                        class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-file-earmark-arrow-down"></i> Tải file
                                    </a>
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Video giao</label>
                            <div class="fw-medium">
                                @if ($assignment->video_path)
                                    <video width="240" height="135" controls>
                                        <source src="{{ asset('storage/' . $assignment->video_path) }}"
                                            type="video/mp4">
                                        Trình duyệt không hỗ trợ video.
                                    </video>
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Danh sách học viên và nộp bài -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-people me-2"></i>Danh sách học viên & tình trạng nộp bài
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($students->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Học viên</th>
                                            <th>Email</th>
                                            <th>Trạng thái nộp</th>
                                            <th>Thời gian nộp</th>
                                            <th>File</th>
                                            <th>Video</th>
                                            <th>Chấm điểm</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $student)
                                            <tr>
                                                <td>{{ $student->name }}</td>
                                                <td>{{ $student->email }}</td>
                                                <td>
                                                    @if ($student->submission)
                                                        <span class="badge bg-success">Đã nộp</span>
                                                    @else
                                                        <span class="badge bg-secondary">Chưa nộp</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($student->submission)
                                                        {{ $student->submission->submitted_at ? $student->submission->submitted_at->format('d/m/Y H:i') : '-' }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($student->submission && $student->submission->attachment_path)
                                                        <a href="{{ asset('storage/' . $student->submission->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                                            <i class="bi bi-file-earmark-arrow-down"></i>
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($student->submission && $student->submission->video_path)
                                                        <video width="120" height="68" controls>
                                                            <source src="{{ asset('storage/' . $student->submission->video_path) }}" type="video/mp4">
                                                            Trình duyệt không hỗ trợ video.
                                                        </video>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($student->submission)
                                                        <form wire:submit.prevent="gradeSubmission({{ $student->submission->id }})" class="d-flex align-items-center gap-2">
                                                            <input type="number" wire:model.defer="grades.{{ $student->submission->id }}" class="form-control form-control-sm" style="width: 70px;" min="0" max="{{ $assignment->score ?? 100 }}">
                                                            <button type="submit" class="btn btn-sm btn-primary">Lưu</button>
                                                        </form>
                                                        @if ($student->submission->grade !== null)
                                                            <span class="badge bg-info mt-1">{{ $student->submission->grade }} điểm</span>
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="bi bi-info-circle me-2"></i>Chưa có học viên nào trong lớp này.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
</div>
</x-layouts.dash-teacher>
