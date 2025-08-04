<x-layouts.dash-admin active="classrooms" title="Quản lý lớp học">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">
                    <i class="fas fa-graduation-cap mr-2"></i>Quản lý lớp học
                </h4>
                <a href="{{ route('classrooms.create') ?? '#' }}" wire:navigate class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>Thêm lớp học
                </a>
            </div>

            <!-- Search Bar & Filters -->
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Tìm kiếm và lọc</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                                <input wire:model.live="search" type="text" class="form-control"
                                    placeholder="Tìm kiếm theo tên lớp hoặc giảng viên...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select wire:model.live="filterTeacher" class="form-control">
                                <option value="">Tất cả giảng viên</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select wire:model.live="filterStatus" class="form-control">
                                <option value="">Tất cả trạng thái</option>
                                <option value="active">Đang hoạt động</option>
                                <option value="inactive">Đã kết thúc</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Classrooms Table -->
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Danh sách lớp học</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th>Tên lớp</th>
                                    <th>Giảng viên</th>
                                    <th class="text-center" style="width: 100px;">Số học viên</th>
                                    <th class="text-center" style="width: 120px;">Trạng thái</th>
                                    <th class="text-center" style="width: 200px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($classrooms as $index => $classroom)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3">
                                                    <i class="fas fa-graduation-cap fa-2x text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="font-weight-bold">{{ $classroom->name }}</div>
                                                    <small class="text-muted">{{ $classroom->notes }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($classroom->teachers->count())
                                                @foreach ($classroom->teachers as $teacher)
                                                    <span class="badge badge-secondary">{{ $teacher->name }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Chưa có giáo viên</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-info">{{ $classroom->students_count }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge badge-{{ $classroom->status == 'active' ? 'success' : 'secondary' }}">
                                                {{ $classroom->status == 'active' ? 'Đang hoạt động' : 'Đã kết thúc' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('classrooms.show', $classroom) }}" wire:navigate
                                                    class="btn btn-sm btn-outline-secondary" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('classrooms.attendance', $classroom) }}" wire:navigate
                                                    class="btn btn-sm btn-outline-info" title="Điểm danh">
                                                    <i class="fas fa-calendar-check"></i>
                                                </a>
                                                <a href="{{ route('classrooms.attendance-history', $classroom) }}"
                                                    wire:navigate class="btn btn-sm btn-outline-secondary"
                                                    title="Lịch sử điểm danh">
                                                    <i class="fas fa-calendar-week"></i>
                                                </a>
                                                <a href="{{ route('classrooms.assign-students', $classroom) }}"
                                                    wire:navigate class="btn btn-sm btn-outline-success"
                                                    title="Gán học viên">
                                                    <i class="fas fa-user-plus"></i>
                                                </a>
                                                <a href="{{ route('classrooms.edit', $classroom) }}" wire:navigate
                                                    class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" data-toggle="modal"
                                                    data-target="#deleteModal{{ $classroom->id }}"
                                                    class="btn btn-sm btn-outline-danger" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Delete Confirmation Modal -->
                                    <div class="modal fade" id="deleteModal{{ $classroom->id }}" tabindex="-1"
                                        aria-labelledby="deleteModalLabel{{ $classroom->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $classroom->id }}">
                                                        Xác nhận xóa lớp học</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Bạn có chắc chắn muốn xóa lớp học "{{ $classroom->name }}"? Hành động
                                                    này không thể hoàn tác.
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Hủy</button>
                                                    <button type="button" class="btn btn-danger" id="confirmDelete"
                                                        wire:click="delete({{ $classroom->id }})"
                                                        data-dismiss="modal">Xóa</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Hiển thị {{ $classrooms->firstItem() ?? 0 }} - {{ $classrooms->lastItem() ?? 0 }}
                            trên tổng số {{ $classrooms->total() ?? 0 }} lớp học
                        </div>
                        <div>
                            {{ $classrooms->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
