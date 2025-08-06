<x-layouts.dash-student active="reports">
    @include('components.language')
    <div class="container">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-bar-chart-fill mr-2"></i>Kết quả học tập
                    </h4>
                    <p class="text-muted mb-0">Tổng quan điểm số và điểm danh của bạn trong quá trình học</p>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="bi bi-journal-text" style="font-size:2.5rem; color:#fd7e14;"></i>
                        </div>
                        <div class="fw-bold">Điểm TB Bài tập</div>
                        <div class="fs-4 text-primary">{{ $avgAssignmentScore }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="bi bi-clipboard-check-fill" style="font-size:2.5rem; color:#6f42c1;"></i>
                        </div>
                        <div class="fw-bold">Điểm TB Kiểm tra</div>
                        <div class="fs-4 text-success">{{ $avgQuizScore }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="bi bi-person-check-fill" style="font-size:2.5rem; color:#20c997;"></i>
                        </div>
                        <div class="fw-bold">Số lần có mặt</div>
                        <div class="fs-4 text-info">{{ $attendancePresent }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="bi bi-person-x-fill" style="font-size:2.5rem; color:#dc3545;"></i>
                        </div>
                        <div class="fw-bold">Số lần vắng</div>
                        <div class="fs-4 text-danger">{{ $attendanceAbsent }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-journal-check mr-2"></i>Điểm bài tập
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Bài tập</th>
                                        <th>Lớp</th>
                                        <th>Điểm</th>
                                        <th>Nhận xét</th>
                                        <th>Ngày nộp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($assignmentSubmissions as $submission)
                                        <tr>
                                            <td>{{ $submission->assignment->title ?? '-' }}</td>
                                            <td>{{ $submission->assignment->classroom->name ?? '-' }}</td>
                                            <td>{{ $submission->score ?? '-' }}</td>
                                            <td>{{ $submission->feedback ?? '-' }}</td>
                                            <td>{{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Chưa có bài tập nào</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <i class="bi bi-clipboard-check mr-2"></i>Điểm kiểm tra
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Đề kiểm tra</th>
                                        <th>Lớp</th>
                                        <th>Điểm</th>
                                        <th>Thời gian làm</th>
                                        <th>Ngày nộp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($quizResults as $result)
                                        <tr>
                                            <td>{{ $result->quiz->title ?? '-' }}</td>
                                            <td>{{ $result->quiz->classroom->name ?? '-' }}</td>
                                            <td>{{ $result->score ?? '-' }}</td>
                                            <td>{{ $result->getDurationString() }}</td>
                                            <td>{{ $result->submitted_at ? $result->submitted_at->format('d/m/Y H:i') : '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Chưa có bài kiểm tra nào</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <i class="bi bi-calendar-check mr-2"></i>Thống kê điểm danh
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ngày</th>
                                        <th>Lớp</th>
                                        <th>Trạng thái</th>
                                        <th>Lý do vắng (nếu có)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $attendances = Auth::user()->studentProfile ? Auth::user()->studentProfile->attendances : (Auth::user()->student ? Auth::user()->student->attendances : collect()); @endphp
                                    @forelse($attendances as $attendance)
                                        <tr>
                                            <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                            <td>{{ $attendance->classroom->name ?? '-' }}</td>
                                            <td>
                                                @if ($attendance->present)
                                                    <span class="badge bg-success">Có mặt</span>
                                                @else
                                                    <span class="badge bg-danger">Vắng</span>
                                                @endif
                                            </td>
                                            <td>{{ $attendance->reason ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Chưa có dữ liệu điểm danh</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-student>
