<x-layouts.dash-student active="notifications">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-bell me-2"></i>Thông báo & Nhắc lịch
                    </h4>
                    <p class="text-muted mb-0">Xem các thông báo và nhắc nhở từ giáo viên</p>
                </div>
                <div class="d-flex gap-2">
                    @if ($this->unreadCount > 0)
                        <button wire:click="markAllAsRead" class="btn btn-outline-primary">
                            <i class="bi bi-check-all me-2"></i>Đánh dấu tất cả đã đọc
                        </button>
                    @endif
                    <div class="position-relative">
                        <i class="bi bi-bell fs-4 text-primary"></i>
                        @if ($this->unreadCount > 0)
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $this->unreadCount > 99 ? '99+' : $this->unreadCount }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tìm kiếm</label>
                        <input wire:model.live="search" type="text" class="form-control"
                            placeholder="Tìm theo tiêu đề hoặc nội dung...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Loại thông báo</label>
                        <select wire:model.live="filterType" class="form-select">
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
                        <select wire:model.live="filterStatus" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="unread">Chưa đọc</option>
                            <option value="read">Đã đọc</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button wire:click="resetFilters" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-clockwise me-1"></i>Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Danh sách thông báo
                </h6>
            </div>
            <div class="card-body">
                @if ($notifications->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Thông báo</th>
                                    <th>Loại</th>
                                    <th>Lớp học</th>
                                    <th>Thời gian</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($notifications as $notification)
                                    <tr class="{{ $notification->is_read ? '' : 'table-warning' }}">
                                        <td>
                                            <div class="d-flex align-items-start">
                                                @php
                                                    $typeColors = [
                                                        'info' => 'primary',
                                                        'warning' => 'warning',
                                                        'success' => 'success',
                                                        'danger' => 'danger',
                                                        'reminder' => 'info',
                                                    ];
                                                    $typeIcons = [
                                                        'info' => 'bi-info-circle',
                                                        'warning' => 'bi-exclamation-triangle',
                                                        'success' => 'bi-check-circle',
                                                        'danger' => 'bi-x-circle',
                                                        'reminder' => 'bi-clock',
                                                    ];
                                                @endphp
                                                <i
                                                    class="bi {{ $typeIcons[$notification->type] }} text-{{ $typeColors[$notification->type] }} me-2 mt-1"></i>
                                                <div>
                                                    <div class="fw-medium {{ $notification->is_read ? '' : 'fw-bold' }}"
                                                        style="cursor: pointer;"
                                                        wire:click="showNotification({{ $notification->id }})">
                                                        {{ $notification->title }}
                                                    </div>
                                                    <small
                                                        class="text-muted">{{ Str::limit($notification->message, 100) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
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
                                            @if ($notification->classroom)
                                                <span class="badge bg-secondary">
                                                    <i
                                                        class="bi bi-diagram-3 me-1"></i>{{ $notification->classroom->name }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <small class="text-muted">
                                                    <i class="bi bi-clock me-1"></i>
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </small>
                                                @if ($notification->scheduled_at)
                                                    <br>
                                                    <small class="text-info">
                                                        <i class="bi bi-calendar-event me-1"></i>
                                                        Lịch: {{ $notification->scheduled_at->format('d/m/Y H:i') }}
                                                    </small>
                                                @endif
                                            </div>
                                        </td>

                                        <td>
                                            @if ($notification->is_read)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check me-1"></i>Đã đọc
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-exclamation me-1"></i>Mới
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (!$notification->is_read)
                                                <button wire:click="markAsRead({{ $notification->id }})"
                                                    class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-check me-1"></i>Đánh dấu đã đọc
                                                </button>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $notifications->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-bell-slash fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">Không có thông báo nào</h5>
                        <p class="text-muted">Bạn sẽ thấy thông báo mới ở đây khi giáo viên gửi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="toast show" role="alert">
                <div class="toast-header">
                    <i class="bi bi-check-circle text-success me-2"></i>
                    <strong class="me-auto">Thành công</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    {{ session('message') }}
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Chi tiết thông báo -->
    @if ($selectedNotification)
        <div class="modal fade show" style="display: block;" tabindex="-1" aria-labelledby="notificationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="d-flex align-items-center">
                            @php
                                $typeColors = [
                                    'info' => 'primary',
                                    'warning' => 'warning',
                                    'success' => 'success',
                                    'danger' => 'danger',
                                    'reminder' => 'info',
                                ];
                                $typeIcons = [
                                    'info' => 'bi-info-circle',
                                    'warning' => 'bi-exclamation-triangle',
                                    'success' => 'bi-check-circle',
                                    'danger' => 'bi-x-circle',
                                    'reminder' => 'bi-clock',
                                ];
                            @endphp
                            <i
                                class="bi {{ $typeIcons[$selectedNotification->type] }} text-{{ $typeColors[$selectedNotification->type] }} me-2"></i>
                            <h5 class="modal-title" id="notificationModalLabel">{{ $selectedNotification->title }}
                            </h5>
                        </div>
                        <button type="button" class="btn-close" wire:click="closeNotification"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <strong>Nội dung:</strong>
                            <div class="mt-2 p-3 bg-light rounded">
                                {{ $selectedNotification->message }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <strong>Loại thông báo:</strong>
                                @php
                                    $typeLabels = [
                                        'info' => 'Thông tin',
                                        'warning' => 'Cảnh báo',
                                        'success' => 'Thành công',
                                        'danger' => 'Nguy hiểm',
                                        'reminder' => 'Nhắc nhở',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $typeColors[$selectedNotification->type] }} ms-2">
                                    {{ $typeLabels[$selectedNotification->type] }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <strong>Trạng thái:</strong>
                                @if ($selectedNotification->is_read)
                                    <span class="badge bg-success ms-2">
                                        <i class="bi bi-check me-1"></i>Đã đọc
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark ms-2">
                                        <i class="bi bi-exclamation me-1"></i>Mới
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if ($selectedNotification->classroom)
                            <div class="mt-3">
                                <strong>Lớp học:</strong>
                                <span class="badge bg-secondary ms-2">
                                    <i class="bi bi-diagram-3 me-1"></i>{{ $selectedNotification->classroom->name }}
                                </span>
                            </div>
                        @endif

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <strong>Thời gian tạo:</strong>
                                <div class="text-muted">{{ $selectedNotification->created_at->format('d/m/Y H:i:s') }}
                                </div>
                            </div>
                            @if ($selectedNotification->scheduled_at)
                                <div class="col-md-6">
                                    <strong>Lịch gửi:</strong>
                                    <div class="text-muted">
                                        {{ $selectedNotification->scheduled_at->format('d/m/Y H:i:s') }}</div>
                                </div>
                            @endif
                        </div>

                        @if ($selectedNotification->expires_at)
                            <div class="mt-3">
                                <strong>Hạn xem:</strong>
                                <div class="text-muted">{{ $selectedNotification->expires_at->format('d/m/Y H:i:s') }}
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        @if (!$selectedNotification->is_read)
                            <button type="button" class="btn btn-success"
                                wire:click="markAsRead({{ $selectedNotification->id }})">
                                <i class="bi bi-check me-1"></i>Đánh dấu đã đọc
                            </button>
                        @endif
                        <button type="button" class="btn btn-secondary" wire:click="closeNotification">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</x-layouts.dash-student>
