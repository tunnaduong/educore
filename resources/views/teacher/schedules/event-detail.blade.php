<div>
    @if ($showModal)
        <div class="modal fade show" style="display: block;" tabindex="-1" aria-labelledby="eventDetailModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventDetailModalLabel">
                            @if ($eventType === 'schedule')
                                <i class="bi bi-calendar3 text-primary mr-2"></i>Chi tiết lịch học
                            @elseif ($eventType === 'lesson')
                                <i class="bi bi-book text-primary mr-2"></i>Chi tiết bài học
                            @elseif($eventType === 'assignment')
                                <i class="bi bi-journal-text text-warning mr-2"></i>Chi tiết bài tập
                            @elseif($eventType === 'quiz')
                                <i class="bi bi-patch-question text-success mr-2"></i>Chi tiết kiểm tra
                            @endif
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($eventData)
                            @if ($eventType === 'schedule')
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4 class="text-primary">Lịch học định kỳ</h4>
                                        <p class="text-muted">Lớp: {{ $eventData->name }}</p>

                                        @if ($eventData->schedule)
                                            <div class="mb-3">
                                                <h6>Lịch học:</h6>
                                                @php
                                                    $schedule = $eventData->schedule;
                                                @endphp
                                                @if (is_array($schedule))
                                                    <ul class="list-group">
                                                        @foreach ($schedule as $day => $timeSlots)
                                                            @if (is_array($timeSlots))
                                                                <li class="list-group-item">
                                                                    <strong>{{ ucfirst($day) }}:</strong>
                                                                    @foreach ($timeSlots as $timeSlot)
                                                                        @if (isset($timeSlot['start_time']) && isset($timeSlot['end_time']))
                                                                            {{ $timeSlot['start_time'] }} -
                                                                            {{ $timeSlot['end_time'] }}
                                                                            @if (isset($timeSlot['location']))
                                                                                ({{ $timeSlot['location'] }})
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6>Thông tin lớp học</h6>
                                                <p><strong>Tên lớp:</strong> {{ $eventData->name }}</p>
                                                <p><strong>Cấp độ:</strong> {{ $eventData->level ?? 'Chưa cập nhật' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($eventType === 'lesson')
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4 class="text-primary">{{ $eventData->title }}</h4>
                                        <p class="text-muted">Lớp: {{ $eventData->classroom->name }}</p>

                                        @if ($eventData->description)
                                            <div class="mb-3">
                                                <h6>Mô tả:</h6>
                                                <p>{{ $eventData->description }}</p>
                                            </div>
                                        @endif

                                        @if ($eventData->content)
                                            <div class="mb-3">
                                                <h6>Nội dung:</h6>
                                                <p>{{ $eventData->content }}</p>
                                            </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Ngày tạo:</h6>
                                                <p>{{ \Carbon\Carbon::parse($eventData->created_at)->format('d/m/Y') }}
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Số bài học:</h6>
                                                <p>{{ $eventData->number ?? 'Chưa cập nhật' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6>Thông tin lớp học</h6>
                                                <p><strong>Tên lớp:</strong> {{ $eventData->classroom->name }}</p>
                                                <p><strong>Số học sinh:</strong>
                                                    {{ $eventData->classroom->students_count ?? 0 }}</p>
                                                <p><strong>Môn học:</strong>
                                                    {{ $eventData->classroom->subject ?? 'Chưa cập nhật' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($eventType === 'assignment')
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4 class="text-warning">{{ $eventData->title }}</h4>
                                        <p class="text-muted">Lớp: {{ $eventData->classroom->name }}</p>

                                        @if ($eventData->description)
                                            <div class="mb-3">
                                                <h6>Mô tả:</h6>
                                                <p>{{ $eventData->description }}</p>
                                            </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Ngày giao bài:</h6>
                                                <p>{{ \Carbon\Carbon::parse($eventData->created_at)->format('d/m/Y') }}
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Hạn nộp:</h6>
                                                <p class="text-danger">
                                                    {{ \Carbon\Carbon::parse($eventData->deadline)->format('H:i - d/m/Y') }}
                                                </p>
                                            </div>
                                        </div>

                                        @if ($eventData->types)
                                            <div class="mb-3">
                                                <h6>Loại bài tập:</h6>
                                                <p>{{ implode(', ', $eventData->types) }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6>Thống kê bài tập</h6>
                                                <p><strong>Đã nộp:</strong> {{ $eventData->submissions_count ?? 0 }}
                                                </p>
                                                <p><strong>Chưa nộp:</strong>
                                                    {{ ($eventData->classroom->students_count ?? 0) - ($eventData->submissions_count ?? 0) }}
                                                </p>
                                                <p><strong>Đã chấm:</strong> {{ $eventData->graded_count ?? 0 }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($eventType === 'quiz')
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4 class="text-success">{{ $eventData->title }}</h4>
                                        <p class="text-muted">Lớp: {{ $eventData->classroom->name }}</p>

                                        @if ($eventData->description)
                                            <div class="mb-3">
                                                <h6>Mô tả:</h6>
                                                <p>{{ $eventData->description }}</p>
                                            </div>
                                        @endif

                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Hạn nộp:</h6>
                                                <p>{{ \Carbon\Carbon::parse($eventData->deadline)->format('H:i - d/m/Y') }}
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Ngày tạo:</h6>
                                                <p>{{ \Carbon\Carbon::parse($eventData->created_at)->format('d/m/Y') }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>Số câu hỏi:</h6>
                                                <p>{{ count(json_decode($eventData->questions, true) ?? []) }} câu</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>Trạng thái:</h6>
                                                <p>{{ $eventData->deadline > now() ? 'Còn hạn' : 'Đã hết hạn' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6>Thống kê kiểm tra</h6>
                                                <p><strong>Đã làm:</strong> {{ $eventData->attempts_count ?? 0 }}</p>
                                                <p><strong>Chưa làm:</strong>
                                                    {{ ($eventData->classroom->students_count ?? 0) - ($eventData->attempts_count ?? 0) }}
                                                </p>
                                                <p><strong>Điểm trung bình:</strong>
                                                    {{ $eventData->average_score ?? 'Chưa có' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="text-center">
                                <p>Không tìm thấy thông tin sự kiện.</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Đóng</button>
                        @if ($eventData)
                            @if ($eventType === 'schedule')
                                <a href="{{ route('admin.schedules.edit', $eventData->id) }}" class="btn btn-primary">
                                    <i class="bi bi-pencil mr-2"></i>Chỉnh sửa lịch học
                                </a>
                            @elseif ($eventType === 'lesson')
                                <a href="{{ route('teacher.lessons.edit', $eventData->id) }}" class="btn btn-primary">
                                    <i class="bi bi-pencil mr-2"></i>Chỉnh sửa
                                </a>
                            @elseif($eventType === 'assignment')
                                <a href="{{ route('teacher.assignments.edit', $eventData->id) }}"
                                    class="btn btn-warning">
                                    <i class="bi bi-pencil mr-2"></i>Chỉnh sửa
                                </a>
                            @elseif($eventType === 'quiz')
                                <a href="{{ route('teacher.quizzes.edit', $eventData->id) }}" class="btn btn-success">
                                    <i class="bi bi-pencil mr-2"></i>Chỉnh sửa
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
