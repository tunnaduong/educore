<x-layouts.dash-admin active="notifications">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-bell mr-2"></i>Thông báo & Nhắc lịch
            </h4>
            <p class="text-muted mb-0">Tạo và quản lý thông báo cho học viên</p>
        </div>
        <div class="d-flex gap-2">
            <button wire:click="markAllAsRead" class="btn btn-outline-secondary">
                <i class="bi bi-check-all mr-2"></i>Đánh dấu tất cả đã đọc
            </button>
            <button wire:click="deleteExpired" class="btn btn-outline-warning">
                <i class="bi bi-trash mr-2"></i>Xóa hết hạn
            </button>
            <button wire:click="create" class="btn btn-primary">
                <i class="bi bi-plus-circle mr-2"></i>Tạo thông báo mới
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tìm kiếm</label>
                    <input wire:model.live="search" type="text" class="form-control"
                        placeholder="Tìm theo tiêu đề hoặc nội dung...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Loại thông báo</label>
                    <select wire:model.live="filterType" class="form-control">
                        <option value="">Tất cả</option>
                        <option value="info">Thông tin</option>
                        <option value="warning">Cảnh báo</option>
                        <option value="success">Thành công</option>
                        <option value="danger">Nguy hiểm</option>
                        <option value="reminder">Nhắc nhở</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Trạng thái</label>
                    <select wire:model.live="filterStatus" class="form-control">
                        <option value="">Tất cả</option>
                        <option value="unread">Chưa đọc</option>
                        <option value="read">Đã đọc</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button wire:click="$set('search', '')" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise mr-1"></i>Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="card">
        <div class="card-body p-0">
            @if ($notifications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tiêu đề</th>
                                <th>Loại</th>
                                <th>Người nhận</th>
                                <th>Lớp học</th>
                                <th>Lịch gửi</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($notifications as $notification)
                                <tr
                                    class="{{ $notification->scheduled_at && $notification->scheduled_at->isPast() ? 'table-warning' : '' }}">
                                    <td>
                                        <div class="fw-bold">{{ $notification->title }}</div>
                                        <small class="text-muted">{{ Str::limit($notification->message, 50) }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $typeColors = [
                                                'info' => 'primary',
                                                'warning' => 'warning',
                                                'success' => 'success',
                                                'danger' => 'danger',
                                                'reminder' => 'info',
                                            ];
                                            $typeLabels = [
                                                'info' => 'Thông tin',
                                                'warning' => 'Cảnh báo',
                                                'success' => 'Thành công',
                                                'danger' => 'Nguy hiểm',
                                                'reminder' => 'Nhắc nhở',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $typeColors[$notification->type] }}">
                                            {{ $typeLabels[$notification->type] }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($notification->user)
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-person-circle mr-2"></i>
                                                {{ $notification->user->name }}
                                            </div>
                                        @else
                                            <span class="text-muted">Tất cả học viên</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($notification->classroom)
                                            <span class="badge bg-secondary">{{ $notification->classroom->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($notification->scheduled_at)
                                            <div>{{ $notification->scheduled_at->format('d/m/Y H:i') }}</div>
                                            @if ($notification->scheduled_at->isPast())
                                                <small class="text-warning fw-bold">Đã gửi</small>
                                            @else
                                                <small class="text-muted">Chờ gửi</small>
                                            @endif
                                        @else
                                            <span class="text-success fw-bold">Đã gửi</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($notification->is_read)
                                            <span class="badge bg-success">Đã đọc</span>
                                        @else
                                            <span class="badge bg-warning">Chưa đọc</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $notification->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $notification->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if (!$notification->is_read)
                                                <button wire:click="toggleRead({{ $notification->id }})"
                                                    class="btn btn-sm btn-outline-success" title="Đánh dấu đã đọc">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                            @endif
                                            <button wire:click="edit({{ $notification->id }})"
                                                class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button wire:click="duplicate({{ $notification->id }})"
                                                class="btn btn-sm btn-outline-info" title="Sao chép">
                                                <i class="bi bi-files"></i>
                                            </button>
                                            @if ($notification->scheduled_at && $notification->scheduled_at->isFuture())
                                                <button wire:click="sendNow({{ $notification->id }})"
                                                    class="btn btn-sm btn-outline-success" title="Gửi ngay">
                                                    <i class="bi bi-send"></i>
                                                </button>
                                            @endif
                                            <button wire:click="delete({{ $notification->id }})"
                                                class="btn btn-sm btn-outline-danger" title="Xóa" onclick="return">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $notifications->links('vendor.pagination.bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-bell-slash" style="font-size: 3rem; color: #ccc;"></i>
                    <h5 class="mt-3 text-muted">Không có thông báo nào</h5>
                    <p class="text-muted">Tạo thông báo đầu tiên để bắt đầu</p>
                    <button wire:click="create" class="btn btn-primary">
                        <i class="bi bi-plus-circle mr-2"></i>Tạo thông báo mới
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Create Modal -->
    @if ($showCreateModal)
        <div class="modal fade show" wire:ignore.self id="createModal" tabindex="-1"
            style="display: block; background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tạo thông báo mới</h5>
                        <button type="button" class="btn-close"
                            wire:click="$set('showCreateModal', false)"></button>
                    </div>
                    <form wire:submit="store">
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                                    <input wire:model="title" type="text"
                                        class="form-control @error('title') is-invalid @enderror"
                                        placeholder="Nhập tiêu đề thông báo">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                                    <textarea wire:model="message" rows="4" class="form-control @error('message') is-invalid @enderror"
                                        placeholder="Nhập nội dung thông báo"></textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Loại thông báo <span
                                            class="text-danger">*</span></label>
                                    <select wire:model="type"
                                        class="form-control @error('type') is-invalid @enderror">
                                        <option value="info">Thông tin</option>
                                        <option value="warning">Cảnh báo</option>
                                        <option value="success">Thành công</option>
                                        <option value="danger">Nguy hiểm</option>
                                        <option value="reminder">Nhắc nhở</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check mt-4">
                                        <input wire:model="is_global" class="form-check-input" type="checkbox"
                                            id="isGlobal">
                                        <label class="form-check-label" for="isGlobal">
                                            Gửi cho tất cả học viên
                                        </label>
                                    </div>
                                </div>

                                @if (!$is_global)
                                    <div class="col-md-6">
                                        <label class="form-label">Học viên</label>
                                        <select wire:model="user_id" class="form-control">
                                            <option value="">Chọn học viên</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="col-md-6">
                                    <label class="form-label">Lớp học</label>
                                    <select wire:model="class_id" class="form-control">
                                        <option value="">Chọn lớp học</option>
                                        @foreach ($classrooms as $classroom)
                                            <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Lịch gửi</label>
                                    <input wire:model="scheduled_at" type="datetimr-local"
                                        class="form-control @error('scheduled_at') is-invalid @enderror">
                                    @error('scheduled_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Để trống để gửi ngay</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Hết hạn</label>
                                    <input wire:model="expires_at" type="datetimr-local"
                                        class="form-control @error('expires_at') is-invalid @enderror">
                                    @error('expires_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Để trống để không hết hạn</small>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="$set('showCreateModal', false)">Hủy</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle mr-2"></i>Tạo thông báo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Modal -->
    @if ($showEditModal)
        <div class="modal fade show" wire:ignore.self id="editModal" tabindex="-1"
            style="display: block; background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chỉnh sửa thông báo</h5>
                        <button type="button" class="btn-close" wire:click="$set('showEditModal', false)"></button>
                    </div>
                    <form wire:submit="update">
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                                    <input wire:model="title" type="text"
                                        class="form-control @error('title') is-invalid @enderror"
                                        placeholder="Nhập tiêu đề thông báo">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Nội dung <span class="text-danger">*</span></label>
                                    <textarea wire:model="message" rows="4" class="form-control @error('message') is-invalid @enderror"
                                        placeholder="Nhập nội dung thông báo"></textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Loại thông báo <span
                                            class="text-danger">*</span></label>
                                    <select wire:model="type"
                                        class="form-control @error('type') is-invalid @enderror">
                                        <option value="info">Thông tin</option>
                                        <option value="warning">Cảnh báo</option>
                                        <option value="success">Thành công</option>
                                        <option value="danger">Nguy hiểm</option>
                                        <option value="reminder">Nhắc nhở</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Học viên</label>
                                    <select wire:model="user_id" class="form-control">
                                        <option value="">Chọn học viên</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Lớp học</label>
                                    <select wire:model="class_id" class="form-control">
                                        <option value="">Chọn lớp học</option>
                                        @foreach ($classrooms as $classroom)
                                            <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Lịch gửi</label>
                                    <input wire:model="scheduled_at" type="datetimr-local"
                                        class="form-control @error('scheduled_at') is-invalid @enderror">
                                    @error('scheduled_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Để trống để gửi ngay</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Hết hạn</label>
                                    <input wire:model="expires_at" type="datetimr-local"
                                        class="form-control @error('expires_at') is-invalid @enderror">
                                    @error('expires_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Để trống để không hết hạn</small>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="$set('showEditModal', false)">Hủy</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle mr-2"></i>Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Modal -->
    @if ($showDeleteModal)
        <div class="modal fade show" wire:ignore.self id="deleteModal" tabindex="-1"
            style="display: block; background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Xác nhận xóa</h5>
                        <button type="button" class="btn-close"
                            wire:click="$set('showDeleteModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn xóa thông báo này không?</p>
                        @if ($selectedNotification)
                            <div class="alert alert-warning">
                                <strong>{{ $selectedNotification->title }}</strong>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="$set('showDeleteModal', false)">Hủy</button>
                        <button type="button" class="btn btn-danger" wire:click="confirmDelete">
                            <i class="bi bi-trash mr-2"></i>Xóa
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="toast show" role="alert">
                <div class="toast-header">
                    <i class="bi bi-check-circle text-success mr-2"></i>
                    <strong class="mr-auto">Thành công</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    {{ session('message') }}
                </div>
            </div>
        </div>
    @endif

    <style>
        .modal.show {
            background-color: rgba(0, 0, 0, 0.5);
        }

        /* Màu sắc cho trạng thái gửi */
        .table-warning {
            background-color: #fff3cd !important;
        }

        .table-warning:hover {
            background-color: #ffeaa7 !important;
        }

        /* Màu trắng cho chưa gửi */
        tr:not(.table-warning) {
            background-color: #ffffff;
        }

        tr:not(.table-warning):hover {
            background-color: #f8f9fa;
        }
    </style>

    <script>
        // Đảm bảo modal hoạt động với Livewire
        document.addEventListener('livewire:init', () => {
            Livewire.on('showCreateModal', () => {
                const modal = new bootstrap.Modal(document.getElementById('createModal'));
                modal.show();
            });

            Livewire.on('hideCreateModal', () => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('createModal'));
                if (modal) {
                    modal.hide();
                }
            });
        });
    </script>
</x-layouts.dash-admin>
