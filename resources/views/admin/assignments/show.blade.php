<x-layouts.dash-admin active="assignments">
    @include('components.language')
    <div class="container-fluid">
        <!-- Success/Error Messages -->
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('assignments.overview') }}" class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>{{ __('views.back_to_assignments_overview') }}
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-journal-text mr-2"></i>{{ __('views.assignment_details') }}
            </h4>
            <p class="text-muted mb-0">{{ $assignment->title }}</p>
        </div>

        <div class="row">
            <!-- Thông tin bài tập -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-info-circle mr-2"></i>{{ __('views.assignment_information') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('views.title') }}</label>
                            <div class="fw-medium">{{ $assignment->title }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('views.classroom_name') }}</label>
                            <div class="fw-medium">{{ $classroom->name ?? '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('views.deadline') }}</label>
                            <div class="fw-medium">
                                {{ $assignment->deadline ? $assignment->deadline->format('d/m/Y H:i') : '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('views.assigned_date') }}</label>
                            <div class="fw-medium">{{ $assignment->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('views.max_score') }}</label>
                            <div class="fw-medium">
                                @if ($assignment->max_score)
                                    <span class="badge bg-success">{{ $assignment->max_score }}/10</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('views.description') }}</label>
                            <div class="fw-medium">{!! nl2br(e($assignment->description)) !!}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('views.assignment_types') }}</label>
                            <div class="fw-medium">
                                @if ($assignment->types && is_array($assignment->types))
                                    @foreach ($assignment->types as $type)
                                        <span class="badge bg-primary me-1">{{ ucfirst($type) }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('views.attached_file') }}</label>
                            <div class="fw-medium">
                                @if ($assignment->attachment_path)
                                    <a href="{{ Storage::url($assignment->attachment_path) }}" target="_blank"
                                        class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-file-earmark-arrow-down"></i> {{ __('views.download_file') }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('views.assignment_video') }}</label>
                            <div class="fw-medium">
                                @if ($assignment->video_path)
                                    <video width="240" height="135" controls class="rounded">
                                        <source src="{{ Storage::url($assignment->video_path) }}"
                                            type="video/mp4">
                                        {{ __('views.browser_not_support_video') }}
                                    </video>
                                @else
                                    <span class="text-muted">-</span>
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
                            <i class="bi bi-people mr-2"></i>{{ __('views.student_list_and_submission_status') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($students->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ __('views.student') }}</th>
                                            <th>{{ __('views.email') }}</th>
                                            <th>{{ __('views.submission_status') }}</th>
                                            <th>{{ __('views.submission_time') }}</th>
                                            <th>{{ __('views.score') }}</th>
                                            <th>{{ __('views.file') }}</th>
                                            <th>{{ __('views.video') }}</th>
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
                                                        <div class="avatar-sm mr-3">
                                                            <i class="bi bi-person-circle fs-4 text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">{{ $student->name }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $student->email ?? __('views.not_available') }}</td>
                                                <td>
                                                    @if ($submission)
                                                        @if ($submission->submitted_at && $assignment->deadline && $submission->submitted_at <= $assignment->deadline)
                                                            <span class="badge bg-success">{{ __('views.on_time') }}</span>
                                                        @elseif ($submission->submitted_at)
                                                            <span class="badge bg-warning">{{ __('views.late') }}</span>
                                                        @else
                                                            <span class="badge bg-info">{{ __('views.draft') }}</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary">{{ __('views.not_submitted') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($submission && $submission->submitted_at)
                                                        {{ $submission->submitted_at->format('d/m/Y H:i') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($submission && $submission->score !== null)
                                                        <span class="badge bg-success">{{ $submission->score }}/10</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($submission && $submission->file_path)
                                                        <a href="{{ Storage::url($submission->file_path) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-success">
                                                            <i class="bi bi-file-earmark-arrow-down"></i> {{ __('views.download_file') }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($submission && $submission->video_path)
                                                        <video width="160" height="90" controls class="rounded">
                                                            <source src="{{ Storage::url($submission->video_path) }}"
                                                                type="video/mp4">
                                                            {{ __('views.browser_not_support_video') }}
                                                        </video>
                                                    @elseif ($submission && $submission->content)
                                                        <div class="small text-muted">
                                                            {{ Str::limit($submission->content, 50) }}
                                                        </div>
                                                    @else
                                                        <span class="text-muted">-</span>
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
                                <h5 class="text-muted">{{ __('views.no_students') }}</h5>
                                <p class="text-muted">{{ __('views.please_assign_students_to_class') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
