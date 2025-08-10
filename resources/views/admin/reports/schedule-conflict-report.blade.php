<x-layouts.dash-admin active="reports">
    @include('components.language')
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    Báo cáo trùng lịch học
                                </h5>
                                <small class="text-light">
                                    @if($lastChecked)
                                        Cập nhật lần cuối: {{ $lastChecked->format('d/m/Y H:i:s') }}
                                    @endif
                                </small>
                            </div>
                            <div class="d-flex gap-2">
                                <button wire:click="refreshConflicts" class="btn btn-light btn-sm" @if($loading) disabled @endif>
                                    <i class="bi bi-arrow-clockwise me-1"></i>
                                    @if($loading)
                                        <span class="spinner-border spinner-border-sm me-1"></span>Đang kiểm tra...
                                    @else
                                        Làm mới
                                    @endif
                                </button>
                                <a href="{{ route('admin.reports.schedule-conflict.export') }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-download me-1"></i>Xuất báo cáo
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        @if(session('message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-2"></i>
                                {{ session('message') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Tổng quan -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-warning">
                                    <div class="card-body text-center">
                                        <div class="display-6 text-warning mb-2">{{ $totalStudentConflicts }}</div>
                                        <h6 class="text-muted">Trùng lịch học sinh</h6>
                                        <small class="text-muted">Học sinh bị trùng lịch giữa các lớp</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-danger">
                                    <div class="card-body text-center">
                                        <div class="display-6 text-danger mb-2">{{ $totalTeacherConflicts }}</div>
                                        <h6 class="text-muted">Trùng lịch giáo viên</h6>
                                        <small class="text-muted">Giáo viên bị trùng lịch dạy</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($totalStudentConflicts === 0 && $totalTeacherConflicts === 0)
                            <div class="text-center py-5">
                                <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                                <h4 class="text-success mt-3">Không có trùng lịch!</h4>
                                <p class="text-muted">Tất cả lịch học đều được sắp xếp hợp lý.</p>
                            </div>
                        @else
                            <!-- Trùng lịch học sinh -->
                            @if(!empty($studentConflicts))
                                <div class="mb-4">
                                    <h6 class="text-warning mb-3">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        Trùng lịch học sinh ({{ $totalStudentConflicts }})
                                    </h6>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-warning">
                                                <tr>
                                                    <th>Lớp học</th>
                                                    <th>Học sinh</th>
                                                    <th>Trùng lịch với</th>
                                                    <th>Thời gian trùng</th>
                                                    <th>Hành động</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($studentConflicts as $conflict)
                                                    <tr>
                                                        <td>
                                                            <strong>{{ $conflict['classroom']->name }}</strong>
                                                            <br>
                                                            <small class="text-muted">{{ $conflict['classroom']->level }}</small>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                    {{ substr($conflict['student']->name, 0, 1) }}
                                                                </div>
                                                                <div>
                                                                    <div class="fw-medium">{{ $conflict['student']->name }}</div>
                                                                    <small class="text-muted">{{ $conflict['student']->email }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @foreach($conflict['conflicts'] as $conf)
                                                                <div class="mb-1">
                                                                    <span class="badge bg-warning text-dark">{{ $conf['classroom']->name }}</span>
                                                                </div>
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            @foreach($conflict['conflicts'] as $conf)
                                                                <div class="mb-1">
                                                                    @if($conf['overlapTime'])
                                                                        <span class="badge bg-danger">{{ $conf['overlapTime'] }}</span>
                                                                    @else
                                                                        <span class="text-muted">N/A</span>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('classrooms.edit', $conflict['classroom']) }}" 
                                                               class="btn btn-sm btn-outline-primary">
                                                                <i class="bi bi-pencil me-1"></i>Sửa lịch
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            <!-- Trùng lịch giáo viên -->
                            @if(!empty($teacherConflicts))
                                <div class="mb-4">
                                    <h6 class="text-danger mb-3">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        Trùng lịch giáo viên ({{ $totalTeacherConflicts }})
                                    </h6>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-danger">
                                                <tr>
                                                    <th>Lớp học</th>
                                                    <th>Giáo viên</th>
                                                    <th>Trùng lịch với</th>
                                                    <th>Thời gian trùng</th>
                                                    <th>Hành động</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($teacherConflicts as $conflict)
                                                    <tr>
                                                        <td>
                                                            <strong>{{ $conflict['classroom']->name }}</strong>
                                                            <br>
                                                            <small class="text-muted">{{ $conflict['classroom']->level }}</small>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                    {{ substr($conflict['teacher']->name, 0, 1) }}
                                                                </div>
                                                                <div>
                                                                    <div class="fw-medium">{{ $conflict['teacher']->name }}</div>
                                                                    <small class="text-muted">{{ $conflict['teacher']->email }}</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @foreach($conflict['conflicts'] as $conf)
                                                                <div class="mb-1">
                                                                    <span class="badge bg-danger">{{ $conf['classroom']->name }}</span>
                                                                </div>
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            @foreach($conflict['conflicts'] as $conf)
                                                                <div class="mb-1">
                                                                    @if($conf['overlapTime'])
                                                                        <span class="badge bg-danger">{{ $conf['overlapTime'] }}</span>
                                                                    @else
                                                                        <span class="text-muted">N/A</span>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('classrooms.edit', $conflict['classroom']) }}" 
                                                               class="btn btn-sm btn-outline-danger">
                                                                <i class="bi bi-pencil me-1"></i>Sửa lịch
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .avatar-sm {
            width: 32px;
            height: 32px;
            font-size: 14px;
            font-weight: 600;
        }
    </style>
</x-layouts.dash-admin>
