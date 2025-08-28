<x-layouts.dash-teacher active="assignments">
    @include('components.language')
    
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('teacher.assignments.index') }}"
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>{{ __('general.back_to_assignment_list') }}
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-journal-text mr-2"></i>{{ __('general.assignment_details') }}
            </h4>
            <p class="text-muted mb-0">{{ $assignment->title }}</p>
        </div>
        <div class="row">
            <!-- Thông tin bài tập -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-info-circle mr-2"></i>{{ __('general.assignment_info') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('general.title') }}</label>
                            <div class="fw-medium">{{ $assignment->title }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('general.classroom') }}</label>
                            <div class="fw-medium">{{ $classroom->name ?? __('general.not_available') }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('general.deadline') }}</label>
                            <div class="fw-medium">
                                {{ $assignment->deadline ? $assignment->deadline->format('d/m/Y H:i') : __('general.not_available') }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('general.assigned_at') }}</label>
                            <div class="fw-medium">{{ $assignment->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('general.description') }}</label>
                            <div class="fw-medium">{!! nl2br(e($assignment->description)) !!}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('general.required_assignment_types') }}</label>
                            <div class="fw-medium">
                                @if ($assignment->types && count($assignment->types) > 0)
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($assignment->types as $type)
                                            <span class="badge bg-primary">{{ $this->getTypeLabel($type) }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    {{ __('general.not_available') }}
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('general.attachment') }}</label>
                            <div class="fw-medium">
                                @if ($assignment->attachment_path)
                                    <a href="{{ asset('storage/' . $assignment->attachment_path) }}" target="_blank"
                                        class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-file-earmark-arrow-down"></i> {{ __('general.download_file') }}
                                    </a>
                                @else
                                    {{ __('general.not_available') }}
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('general.assigned_video') }}</label>
                            <div class="fw-medium">
                                @if ($assignment->video_path)
                                    <video width="240" height="135" controls>
                                        <source src="{{ asset('storage/' . $assignment->video_path) }}"
                                            type="video/mp4">
                                        {{ __('general.browser_not_support') }}
                                    </video>
                                @else
                                    {{ __('general.not_available') }}
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
                            <i class="bi bi-people mr-2"></i>{{ __('general.student_list_and_submission_status') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($students->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ __('general.student') }}</th>
                                            <th>{{ __('general.email') }}</th>
                                            <th>{{ __('general.submission_status') }}</th>
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
                                                            {{ __('general.types') }}
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
                                <i class="bi bi-info-circle mr-2"></i>{{ __('general.no_students_in_class_yet') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-teacher>
