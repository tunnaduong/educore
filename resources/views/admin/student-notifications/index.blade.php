<x-layouts.dash-student active="notifications">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Thông báo & Nhắc lịch</h2>
            <p class="text-muted mb-0">Xem các thông báo và nhắc nhở từ giáo viên</p>
        </div>
        <div class="d-flex gap-2">
            @if($this->unreadCount > 0)
                <button wire:click="markAllAsRead" class="btn btn-outline-primary">
                    <i class="bi bi-check-all me-2"></i>Đánh dấu tất cả đã đọc
                </button>
            @endif
            <div class="position-relative">
                <i class="bi bi-bell fs-4"></i>
                @if($this->unreadCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $this->unreadCount > 99 ? '99+' : $this->unreadCount }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tìm kiếm</label>
                    <input wire:model.live="search" type="text" class="form-control" placeholder="Tìm theo tiêu đề hoặc nội dung...">
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
                    <button wire:click="$set('search', '')" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise me-1"></i>Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="row">
        @if($notifications->count() > 0)
            @foreach($notifications as $notification)
                <div class="col-12 mb-3">
                    <div class="card {{ $notification->scheduled_at && $notification->scheduled_at->isPast() ? 'border-warning' : '' }} h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        @php
                                            $typeColors = [
                                                'info' => 'primary',
                                                'warning' => 'warning',
                                                'success' => 'success',
                                                'danger' => 'danger',
                                                'reminder' => 'info'
                                            ];
                                            $typeIcons = [
                                                'info' => 'bi-info-circle',
                                                'warning' => 'bi-exclamation-triangle',
                                                'success' => 'bi-check-circle',
                                                'danger' => 'bi-x-circle',
                                                'reminder' => 'bi-clock'
                                            ];
                                        @endphp
                                        <i class="bi {{ $typeIcons[$notification->type] }} text-{{ $typeColors[$notification->type] }} me-2"></i>
                                        <h6 class="card-title mb-0 {{ $notification->is_read ? '' : 'fw-bold' }}">
                                            {{ $notification->title }}
                                        </h6>
                                        @if(!$notification->is_read)
                                            <span class="badge bg-warning ms-2">Mới</span>
                                        @endif
                                    </div>
                                    
                                    <p class="card-text text-muted mb-3">
                                        {{ $notification->message }}
                                    </p>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex gap-3">
                                            @if($notification->classroom)
                                                <span class="badge bg-secondary">
                                                    <i class="bi bi-diagram-3 me-1"></i>{{ $notification->classroom->name }}
                                                </span>
                                            @endif
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        
                                        <div class="d-flex gap-2">
                                            @if(!$notification->is_read)
                                                <button wire:click="markAsRead({{ $notification->id }})" 
                                                        class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-check me-1"></i>Đã đọc
                                                </button>
                                            @endif
                                            @if($notification->user_id === auth()->id())
                                                <button wire:click="delete({{ $notification->id }})" 
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa thông báo này?')">
                                                    <i class="bi bi-trash me-1"></i>Xóa
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $notifications->links() }}
                </div>
            </div>
        @else
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-bell-slash" style="font-size: 4rem; color: #ccc;"></i>
                    <h5 class="mt-3 text-muted">Không có thông báo nào</h5>
                    <p class="text-muted">Bạn sẽ thấy thông báo mới ở đây khi giáo viên gửi</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Flash Messages -->
    @if(session()->has('message'))
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

    @if(session()->has('error'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="toast show" role="alert">
                <div class="toast-header">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                    <strong class="me-auto">Lỗi</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    {{ session('error') }}
                </div>
            </div>
        </div>
    @endif

    <style>
        .card.border-warning {
            border-width: 2px;
            border-color: #ffc107 !important;
            background-color: #fff3cd;
        }
        .toast {
            min-width: 300px;
        }
        
        /* Màu sắc cho trạng thái gửi */
        .card:not(.border-warning) {
            background-color: #ffffff;
        }
    </style>
</x-layouts.dash-student> 