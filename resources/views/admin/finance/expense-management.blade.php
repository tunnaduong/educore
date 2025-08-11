<x-layouts.dash-admin active="finance" title="Quản lý chi tiêu">
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="text-danger mb-1">
                    <i class="bi bi-wallet2 mr-2"></i>Quản lý chi tiêu
                </h4>
                <p class="text-muted mb-0">Theo dõi và quản lý các khoản chi của trung tâm</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.finance.index') }}" class="btn btn-outline-secondary btn-lg" wire:navigate>
                    <i class="bi bi-arrow-left mr-2"></i>Quay lại thống kê
                </a>
                <button class="btn btn-danger btn-lg shadow-sm" wire:click="openCreateModal">
                    <i class="bi bi-plus-circle mr-2"></i>Thêm khoản chi
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0 text-primary">
                    <i class="bi bi-funnel-fill mr-2"></i>Bộ lọc chi tiêu
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-tag-fill text-danger mr-1"></i>Loại chi tiêu
                        </label>
                        <select wire:model="filterType" class="form-control">
                            <option value="">Tất cả loại</option>
                            <option value="salary">Lương</option>
                            <option value="utilities">Tiện ích</option>
                            <option value="maintenance">Bảo trì</option>
                            <option value="supplies">Vật tư</option>
                            <option value="marketing">Marketing</option>
                            <option value="training">Đào tạo</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-calendar-month text-primary mr-1"></i>Tháng
                        </label>
                        <input type="month" wire:model="filterMonth" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-person-fill text-success mr-1"></i>Nhân viên
                        </label>
                        <select wire:model="filterStaff" class="form-control">
                            <option value="">Tất cả nhân viên</option>
                            @foreach ($this->staffs as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="text-muted small">
                            <i class="bi bi-info-circle mr-1"></i>
                            Tổng: {{ number_format($expenses->sum('amount')) }}₫
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Expenses Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-gradient-danger text-white">
                <h6 class="mb-0">
                    <i class="bi bi-list-ul mr-2"></i>Danh sách chi tiêu
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="8%">#</th>
                            <th width="15%">
                                <i class="bi bi-tag-fill mr-1"></i>Loại chi tiêu
                            </th>
                            <th width="15%">
                                <i class="bi bi-cash-stack mr-1"></i>Số tiền
                            </th>
                            <th width="12%">
                                <i class="bi bi-person-fill mr-1"></i>Nhân viên
                            </th>
                            <th width="12%">
                                <i class="bi bi-building mr-1"></i>Lớp học
                            </th>
                            <th width="15%">
                                <i class="bi bi-calendar-event mr-1"></i>Ngày chi
                            </th>
                            <th width="13%">
                                <i class="bi bi-chat-left-text mr-1"></i>Ghi chú
                            </th>
                            <th class="text-center" width="10%">
                                <i class="bi bi-gear mr-1"></i>Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                            <tr class="align-middle">
                                <td class="text-center fw-bold text-primary">{{ $expense->id }}</td>
                                <td>
                                    @php
                                        $typeMap = [
                                            'salary' => [
                                                'label' => 'Lương',
                                                'icon' => 'bi-people-fill',
                                                'color' => 'success',
                                            ],
                                            'utilities' => [
                                                'label' => 'Tiện ích',
                                                'icon' => 'bi-lightning-fill',
                                                'color' => 'warning',
                                            ],
                                            'maintenance' => [
                                                'label' => 'Bảo trì',
                                                'icon' => 'bi-tools',
                                                'color' => 'info',
                                            ],
                                            'supplies' => [
                                                'label' => 'Vật tư',
                                                'icon' => 'bi-box-seam',
                                                'color' => 'secondary',
                                            ],
                                            'marketing' => [
                                                'label' => 'Marketing',
                                                'icon' => 'bi-megaphone-fill',
                                                'color' => 'primary',
                                            ],
                                            'training' => [
                                                'label' => 'Đào tạo',
                                                'icon' => 'bi-mortarboard-fill',
                                                'color' => 'info',
                                            ],
                                            'other' => [
                                                'label' => 'Khác',
                                                'icon' => 'bi-three-dots',
                                                'color' => 'secondary',
                                            ],
                                        ];
                                        $type = $typeMap[$expense->type] ?? [
                                            'label' => $expense->type,
                                            'icon' => 'bi-question',
                                            'color' => 'secondary',
                                        ];
                                    @endphp
                                    <span
                                        class="badge bg-{{ $type['color'] }} bg-opacity-10 text-{{ $type['color'] }} border border-{{ $type['color'] }}">
                                        <i class="bi {{ $type['icon'] }} mr-1"></i>{{ $type['label'] }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-danger">-{{ number_format($expense->amount) }}₫</span>
                                </td>
                                <td>
                                    @if ($expense->staff)
                                        <div class="d-flex align-items-center">
                                            <small class="fw-semibold">{{ $expense->staff->name }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($expense->classroom)
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi bi-building-fill mr-1"></i>{{ $expense->classroom?->name ?? 'N/A' }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small">{{ $expense->spent_at->format('d/m/Y') }}</div>
                                </td>
                                <td>
                                    @if ($expense->note)
                                        <i class="bi bi-chat-left-fill text-primary" title="{{ $expense->note }}"></i>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button wire:click="editExpense({{ $expense->id }})"
                                            class="btn btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button wire:click="deleteExpense({{ $expense->id }})"
                                            onclick="return confirm('Bạn có chắc muốn xóa khoản chi này?')"
                                            class="btn btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-wallet text-muted" style="font-size: 3rem;"></i>
                                    <div class="mt-2 text-muted fs-5">Chưa có khoản chi nào</div>
                                    <button class="btn btn-danger mt-3" wire:click="openCreateModal">
                                        <i class="bi bi-plus-circle mr-1"></i>Thêm khoản chi đầu tiên
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        @if ($showCreateModal)
            <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">
                                <i class="bi {{ $editingExpenseId ? 'bi-pencil' : 'bi-plus-circle' }} mr-2"></i>
                                {{ $editingExpenseId ? 'Sửa khoản chi' : 'Thêm khoản chi mới' }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white"
                                wire:click="closeCreateModal"></button>
                        </div>
                        <form wire:submit.prevent="{{ $editingExpenseId ? 'updateExpense' : 'createExpense' }}">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-semibold">Loại chi tiêu *</label>
                                            <select wire:model="type" class="form-control" required>
                                                <option value="salary">💰 Lương</option>
                                                <option value="utilities">⚡ Tiện ích</option>
                                                <option value="maintenance">🔧 Bảo trì</option>
                                                <option value="supplies">📦 Vật tư</option>
                                                <option value="marketing">📢 Marketing</option>
                                                <option value="training">🎓 Đào tạo</option>
                                                <option value="other">➕ Khác</option>
                                            </select>
                                            @error('type')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-semibold">Số tiền *</label>
                                            <input type="number" wire:model="amount" class="form-control"
                                                min="1" required autocomplete="off">
                                            @error('amount')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-semibold">Nhân viên</label>
                                            <select wire:model="staff_id" class="form-control">
                                                <option value="">-- Chọn nhân viên --</option>
                                                @foreach ($this->staffs as $staff)
                                                    <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('staff_id')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-semibold">Lớp học</label>
                                            <select wire:model="class_id" class="form-control">
                                                <option value="">-- Chọn lớp --</option>
                                                @foreach ($this->classrooms as $class)
                                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('class_id')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-semibold">Ngày chi *</label>
                                            <input type="date" wire:model="spent_at" class="form-control"
                                                required>
                                            @error('spent_at')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-semibold">Ghi chú</label>
                                            <textarea wire:model="note" class="form-control" rows="2" placeholder="Mô tả chi tiết về khoản chi..."></textarea>
                                            @error('note')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    wire:click="closeCreateModal">Đóng</button>
                                <button type="submit" class="btn btn-danger">
                                    <i
                                        class="bi {{ $editingExpenseId ? 'bi-check-circle' : 'bi-plus-circle' }} mr-1"></i>
                                    {{ $editingExpenseId ? 'Cập nhật' : 'Thêm khoản chi' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        .bg-gradient-danger {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%) !important;
        }
    </style>
</x-layouts.dash-admin>
