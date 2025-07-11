<x-layouts.dash-admin active="submissions">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-lg-9">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex align-items-center">
                        <i class="bi bi-journal-check me-2"></i>
                        <h4 class="mb-0">Danh sách bài tập cần chấm</h4>
                    </div>
                    <div class="card-body p-0">
                        @if ($assignments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Lớp học</th>
                                            <th>Tiêu đề</th>
                                            <th>Hạn nộp</th>
                                            <th class="text-center">Số bài nộp</th>
                                            <th class="text-center">Trạng thái</th>
                                            <th class="text-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($assignments as $assignment)
                                            <tr>
                                                <td><span class="fw-semibold"><i
                                                            class="bi bi-mortarboard me-1"></i>{{ $assignment->classroom?->name ?? '-' }}</span>
                                                </td>
                                                <td>{{ $assignment->title }}</td>
                                                <td><span class="badge bg-warning text-dark"><i
                                                            class="bi bi-calendar3"></i>
                                                        {{ $assignment->deadline ? $assignment->deadline->format('d/m/Y H:i') : '-' }}</span>
                                                </td>
                                                <td class="text-center"><span
                                                        class="badge bg-info text-dark">{{ $assignment->submissions_count }}</span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($assignment->submissions_count > 0)
                                                        <span class="badge bg-success"><i class="bi bi-check2-all"></i>
                                                            Có bài nộp</span>
                                                    @else
                                                        <span class="badge bg-secondary"><i
                                                                class="bi bi-hourglass-split"></i> Chưa có</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-primary btn-sm px-3"
                                                        wire:click="selectAssignment({{ $assignment->id }})">
                                                        <i class="bi bi-pencil-square"></i> Chấm bài
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info text-center m-0 py-4">
                                <i class="bi bi-info-circle fs-3"></i><br>Không có bài tập nào cần chấm.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-3 d-none d-lg-block">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-light">
                        <i class="bi bi-lightbulb me-2"></i>Hướng dẫn
                    </div>
                    <div class="card-body small">
                        <ul class="mb-2 ps-3">
                            <li>Chỉ hiện các bài tập thuộc lớp bạn phụ trách.</li>
                            <li>Bấm <span class="badge bg-primary"><i class="bi bi-pencil-square"></i> Chấm bài</span>
                                để vào giao diện chấm điểm.</li>
                            <li>Số bài nộp sẽ hiển thị theo từng bài tập.</li>
                        </ul>
                        <div class="alert alert-info p-2 mb-0">
                            <i class="bi bi-info-circle"></i> Chỉ giáo viên hoặc admin mới có quyền chấm bài.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
