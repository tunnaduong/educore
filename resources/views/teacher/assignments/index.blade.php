<x-layouts.dash-teacher active="assignments">
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0 text-primary fs-4">
                    <i class="bi bi-journal-text mr-2"></i>Tổng quan giao bài tập
                </h4>
                <p class="text-muted mb-0">Quản lý và theo dõi tình hình giao - nộp bài tập</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('teacher.assignments.create') }}" class="btn btn-outline-primary">
                    <i class="bi bi-plus-circle mr-2"></i>Giao bài tập mới
                </a>
            </div>
        </div>

        <!-- Bộ lọc tháng/năm (nếu cần) -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-funnel mr-2"></i>Lọc theo tháng
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2 justify-content-end">
                            <select wire:model.live="selectedMonth" class="form-control" style="max-width: 150px;">
                                @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{ $month }}">Tháng {{ $month }}</option>
                                @endfor
                            </select>
                            <select wire:model.live="selectedYear" class="form-control" style="max-width: 120px;">
                                @for ($year = date('Y') - 2; $year <= date('Y') + 1; $year++)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê tổng quan -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0">Tổng bài tập</h6>
                                <h3 class="mb-0">{{ $overviewStats['total_assignments'] ?? 0 }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-journal-text fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0">Lớp có bài tập</h6>
                                <h3 class="mb-0">{{ $overviewStats['total_classes'] ?? 0 }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-mortarboard fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0">Tổng lượt nộp</h6>
                                <h3 class="mb-0">{{ $overviewStats['total_submissions'] ?? 0 }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-upload fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0">Tỷ lệ nộp bài</h6>
                                <h3 class="mb-0">{{ $overviewStats['submission_rate'] ?? 0 }}%</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-percent fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Top lớp nhiều bài tập nhất -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-trophy mr-2"></i>Top 5 lớp có nhiều bài tập nhất
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($topClasses->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Lớp học</th>
                                            <th class="text-center">Tổng bài tập</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topClasses as $classData)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm mr-3">
                                                            <i class="bi bi-mortarboard fs-4 text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">
                                                                {{ $classData['classroom']->name ?? '-' }}</div>
                                                            <small
                                                                class="text-muted">{{ $classData['classroom']->level ?? '' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge bg-secondary">{{ $classData['total_assignments'] }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-journal-x fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">Chưa có dữ liệu bài tập</h5>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Bài tập gần đây -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-primary">
                                <i class="bi bi-clock-history mr-2"></i>Bài tập gần đây
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($recentAssignments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tiêu đề</th>
                                            <th>Lớp học</th>
                                            <th>Hạn nộp</th>
                                            <th>Ngày giao</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentAssignments as $assignment)
                                            <tr>
                                                <td>{{ $assignment->title }}</td>
                                                <td>{{ $assignment->classroom->name ?? '-' }}</td>
                                                <td>{{ $assignment->deadline ? $assignment->deadline->format('d/m/Y H:i') : '-' }}
                                                </td>
                                                <td>{{ $assignment->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <a href="{{ route('teacher.assignments.show', $assignment->id) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('teacher.assignments.edit', $assignment->id) }}"
                                                        class="btn btn-sm btn-outline-warning">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#deleteAssignmentModal{{ $assignment->id }}"
                                                        class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- Delete Confirmation Modal -->
                                            <div class="modal fade" id="deleteAssignmentModal{{ $assignment->id }}"
                                                tabindex="-1"
                                                aria-labelledby="deleteAssignmentModalLabel{{ $assignment->id }}"
                                                aria-hidden="true">
                                                <div
                                                    class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="deleteAssignmentModalLabel{{ $assignment->id }}">
                                                                Xác nhận xóa bài tập</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Bạn có chắc chắn muốn xóa bài tập
                                                            "{{ $assignment->title }}"? Hành động này không thể hoàn
                                                            tác.
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Hủy</button>
                                                            <button type="button" class="btn btn-danger"
                                                                wire:click="deleteAssignment({{ $assignment->id }})"
                                                                data-bs-dismiss="modal">Xóa</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-journal-x fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">Chưa có bài tập nào</h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Top học viên nộp bài đúng hạn -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-star mr-2"></i>Top 5 học viên nộp bài đúng hạn
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($topStudents->count() > 0)
                            @foreach ($topStudents as $index => $studentData)
                                <div
                                    class="d-flex align-items-center mb-3 {{ $index < 3 ? 'p-2 bg-light rounded' : '' }}">
                                    <div class="mr-3">
                                        @if ($index < 3)
                                            <span class="badge bg-warning">{{ $index + 1 }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-medium">{{ $studentData['student']->name ?? '-' }}</div>
                                        <small class="text-muted">{{ $studentData['student']->email ?? '' }}</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-success">{{ $studentData['on_time_rate'] }}%</div>
                                        <small
                                            class="text-muted">{{ $studentData['on_time'] }}/{{ $studentData['total_submissions'] }}</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-3">
                                <i class="bi bi-people fs-1 text-muted mb-2"></i>
                                <p class="text-muted mb-0">Chưa có dữ liệu học viên</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-teacher>
