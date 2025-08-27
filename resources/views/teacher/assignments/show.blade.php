<x-layouts.dash-teacher active="assignments">
    @include('components.language')
    @php
        $t = function ($vi, $en, $zh) {
            $l = app()->getLocale();
            return $l === 'vi' ? $vi : ($l === 'zh' ? $zh : $en);
        };
    @endphp
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('teacher.assignments.index') }}"
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>{{ $t('Quay lại danh sách bài tập', 'Back to assignment list', '返回作业列表') }}
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-journal-text mr-2"></i>{{ $t('Chi tiết bài tập', 'Assignment Details', '作业详情') }}
            </h4>
            <p class="text-muted mb-0">{{ $assignment->title }}</p>
        </div>
        <div class="row">
            <!-- Thông tin bài tập -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-info-circle mr-2"></i>{{ $t('Thông tin bài tập', 'Assignment Information', '作业信息') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ $t('Tiêu đề', 'Title', '标题') }}</label>
                            <div class="fw-medium">{{ $assignment->title }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ $t('Lớp học', 'Classroom', '班级') }}</label>
                            <div class="fw-medium">{{ $classroom->name ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ $t('Hạn nộp', 'Deadline', '截止时间') }}</label>
                            <div class="fw-medium">
                                {{ $assignment->deadline ? $assignment->deadline->format('d/m/Y H:i') : '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ $t('Ngày giao', 'Assigned at', '布置时间') }}</label>
                            <div class="fw-medium">{{ $assignment->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ $t('Mô tả', 'Description', '描述') }}</label>
                            <div class="fw-medium">{!! nl2br(e($assignment->description)) !!}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ $t('Loại bài tập yêu cầu', 'Required assignment types', '要求的作业类型') }}</label>
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
                            <label class="form-label text-muted small">{{ $t('File đính kèm', 'Attachment', '附件') }}</label>
                            <div class="fw-medium">
                                @if ($assignment->attachment_path)
                                    <a href="{{ asset('storage/' . $assignment->attachment_path) }}" target="_blank"
                                        class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-file-earmark-arrow-down"></i> {{ $t('Tải file', 'Download file', '下载文件') }}
                                    </a>
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ $t('Video giao', 'Assigned video', '布置的视频') }}</label>
                            <div class="fw-medium">
                                @if ($assignment->video_path)
                                    <video width="240" height="135" controls>
                                        <source src="{{ asset('storage/' . $assignment->video_path) }}"
                                            type="video/mp4">
                                        {{ $t('Trình duyệt không hỗ trợ video.', 'Browser does not support video.', '浏览器不支持视频。') }}
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
                            <i class="bi bi-people mr-2"></i>{{ $t('Danh sách học viên & tình trạng nộp bài', 'Student list & submission status', '学员列表与提交状态') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($students->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ $t('Học viên', 'Student', '学员') }}</th>
                                            <th>{{ $t('Email', 'Email', '邮箱') }}</th>
                                            <th>{{ $t('Trạng thái nộp', 'Submission status', '提交状态') }}</th>
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
                                                            {{ $t('loại', 'types', '类型') }}
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
                                <i class="bi bi-info-circle mr-2"></i>{{ $t('Chưa có học viên nào trong lớp này.', 'No students in this class yet.', '该班级暂无学员。') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-teacher>
