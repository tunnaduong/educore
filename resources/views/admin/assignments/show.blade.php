<x-layouts.dash-admin active="assignments">
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('assignments.overview') }}" wire:navigate
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left me-2"></i>Quay lại tổng quan bài tập
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $student)
                                            @php
                                                $submission = $submissions->firstWhere('student.user_id', $student->id);
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm me-3">
                                                            <i class="bi bi-person-circle fs-4 text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">{{ $student->name }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $student->email ?? 'Chưa có' }}</td>
                                                <td>
                                                    @if ($submission)
                                                        @if ($submission->submitted_at && $assignment->deadline && $submission->submitted_at <= $assignment->deadline)
                                                            <span class="badge bg-success">Đúng hạn</span>
                                                        @else
                                                            <span class="badge bg-warning">Trễ hạn</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary">Chưa nộp</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($submission && $submission->submitted_at)
                                                        {{ $submission->submitted_at->format('d/m/Y H:i') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($submission && $submission->file_path)
                                                        <a href="{{ asset('storage/' . $submission->file_path) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-success">
                                                            <i class="bi bi-file-earmark-arrow-down"></i> Tải file
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($submission && $submission->video_url)
                                                        <video width="160" height="90" controls>
                                                            <source src="{{ $submission->video_url }}"
                                                                type="video/mp4">
                                                            Trình duyệt không hỗ trợ video.
                                                        </video>
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
                            <div class="text-center py-4">
                                <i class="bi bi-people fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">Chưa có học viên nào</h5>
                                <p class="text-muted">Vui lòng gán học viên vào lớp học.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
