<x-layouts.dash-admin active="reports">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('reports.index') }}" wire:navigate
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>Quay lại báo cáo
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-exclamation-triangle-fill mr-2"></i>Báo cáo trùng lịch học
            </h4>
            <p class="text-muted mb-0">Phát hiện và quản lý các trường hợp trùng lịch học</p>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Tìm kiếm</label>
                        <input wire:model.live="search" type="text" class="form-control" 
                               placeholder="Tìm theo tên lớp hoặc học sinh...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Lọc theo lớp</label>
                        <select wire:model.live="filterClassroom" class="form-select">
                            <option value="">Tất cả lớp</option>
                            @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Lọc theo học sinh</label>
                        <select wire:model.live="filterStudent" class="form-select">
                            <option value="">Tất cả học sinh</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0 text-primary">
                    <i class="bi bi-list-ul mr-2"></i>Danh sách trùng lịch
                    <span class="badge bg-warning ms-2">{{ $conflicts->total() }}</span>
                </h5>
            </div>
            <div class="card-body">
                @if($conflicts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Lớp học</th>
                                    <th>Học sinh</th>
                                    <th>Số lớp trùng</th>
                                    <th>Chi tiết trùng lịch</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($conflicts as $conflict)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-mortarboard text-primary me-2"></i>
                                                <div>
                                                    <div class="fw-medium">{{ $conflict['classroom']->name }}</div>
                                                    <small class="text-muted">{{ $conflict['classroom']->level }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-person-circle text-success me-2"></i>
                                                <div>
                                                    <div class="fw-medium">{{ $conflict['student']->name }}</div>
                                                    <small class="text-muted">{{ $conflict['student']->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger">{{ count($conflict['conflicts']) }}</span>
                                        </td>
                                        <td>
                                            <div class="small text-muted">
                                                @foreach($conflict['conflicts'] as $index => $detail)
                                                    @if($index < 2)
                                                        <div>{{ $detail['message'] }}</div>
                                                    @endif
                                                @endforeach
                                                @if(count($conflict['conflicts']) > 2)
                                                    <div class="text-primary">+{{ count($conflict['conflicts']) - 2 }} lớp khác</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <button wire:click="showConflictDetails({{ $conflict['classroom']->id }}, {{ $conflict['student']->id }})"
                                                    class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye mr-1"></i>Chi tiết
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($conflicts->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $conflicts->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-check-circle-fill text-success fs-1 d-block mb-3"></i>
                        <h5 class="text-success">Không có trùng lịch!</h5>
                        <p class="text-muted">Tất cả học sinh đều có lịch học hợp lý.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Chi tiết trùng lịch -->
    @if($showDetails && $selectedConflict)
    <div class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle-fill mr-2"></i>
                        Chi tiết trùng lịch
                    </h5>
                    <button type="button" class="btn-close" wire:click="closeDetails"></button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-primary">Thông tin học sinh</h6>
                            <div class="card border-primary">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-person-circle text-primary me-2"></i>
                                        <div>
                                            <strong>{{ $selectedConflict['student']->name }}</strong><br>
                                            <small class="text-muted">{{ $selectedConflict['student']->email }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-success">Lớp hiện tại</h6>
                            <div class="card border-success">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-mortarboard text-success me-2"></i>
                                        <div>
                                            <strong>{{ $selectedConflict['classroom']->name }}</strong><br>
                                            <small class="text-muted">
                                                @if($selectedConflict['classroom']->schedule)
                                                    {{ implode(', ', $selectedConflict['classroom']->schedule['days'] ?? []) }} - 
                                                    {{ $selectedConflict['classroom']->schedule['time'] ?? '' }}
                                                @else
                                                    Chưa có lịch học
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6 class="text-danger mb-3">Các lớp trùng lịch:</h6>
                    @foreach($selectedConflict['conflicts'] as $conflict)
                        <div class="card border-danger mb-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-danger">
                                    <i class="bi bi-calendar-event text-danger mr-2"></i>
                                    {{ $conflict['classroom']->name }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Lịch học trùng:</strong><br>
                                        <small class="text-muted">{{ $conflict['message'] }}</small>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Thời gian trùng:</strong><br>
                                        <small class="text-muted">
                                            @if($conflict['overlapTime'])
                                                {{ $conflict['overlapTime'] }}
                                            @else
                                                Toàn bộ thời gian
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeDetails">
                        <i class="bi bi-x-circle mr-2"></i>
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</x-layouts.dash-admin>
