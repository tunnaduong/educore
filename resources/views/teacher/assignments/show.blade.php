<x-layouts.dash-teacher active="assignments">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('teacher.assignments.index') }}"
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>Quay lại danh sách bài tập
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-journal-text mr-2"></i>Chi tiết bài tập
            </h4>
            <p class="text-muted mb-0">{{ $assignment->title }}</p>
        </div>
        <div class="row">
            <!-- Thông tin bài tập -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-info-circle mr-2"></i>Thông tin bài tập
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
                            <label class="form-label text-muted small">Loại bài tập yêu cầu</label>
                            <div class="fw-medium">
                                @if ($assignment->types && count($assignment->types) > 0)
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($assignment->types as $type)
                                            <span class="badge bg-primary">{{ $this->getTypeLabel($type) }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    -
                                @endif
                            </div>
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
                            <i class="bi bi-people mr-2"></i>Danh sách học viên & tình trạng nộp bài
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $student)
                                            @php
                                                $status = $this->getSubmissionStatus($student);
                                            @endphp
                                            <tr>
                                                <td>{{ $student->name }}</td>
                                                <td>{{ $student->email }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $status['class'] }}">{{ $status['label'] }}</span>
                                                    @if ($status['status'] !== 'not_submitted')
                                                        <div class="small text-muted mt-1">
                                                            {{ $status['submitted_count'] }}/{{ $status['required_count'] }}
                                                            loại
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="bi bi-info-circle mr-2"></i>Chưa có học viên nào trong lớp này.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-teacher>
