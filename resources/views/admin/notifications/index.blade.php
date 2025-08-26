<x-layouts.dash-admin active="notifications">
    @include('components.language')
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-bell mr-2"></i>Thông báo & Nhắc lịch
            </h4>
            <p class="text-muted mb-0">{{ __('views.create_and_manage_notifications') }}</p>
        </div>
        <div class="d-flex">
            <button wire:click="markAllAsRead" class="btn btn-outline-secondary mr-2">
                <i class="bi bi-check-all mr-md-2"></i><span class="d-none d-md-inline">Đánh dấu tất cả đã đọc</span>
            </button>
            <button wire:click="deleteExpired" class="btn btn-outline-warning mr-2">
                <i class="bi bi-trash mr-md-2"></i><span class="d-none d-md-inline">Xóa hết hạn</span>
            </button>
            <button wire:click="create" class="btn btn-primary">
                <i class="bi bi-plus-circle mr-md-2"></i><span class="d-none d-md-inline">Tạo thông báo mới</span>
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('views.search') }}</label>
                    <input wire:model.live="search" type="text" class="form-control"
                        placeholder="Tìm theo tiêu đề hoặc nội dung...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('views.notification_type') }}</label>
                    <select wire:model.live="filterType" class="form-control">
                        <option value="">{{ __('views.all') }}</option>
                        <option value="info">{{ __('views.info') }}</option>
                        <option value="warning">{{ __('views.warning') }}</option>
                        <option value="success">{{ __('views.success') }}</option>
                        <option value="danger">{{ __('views.danger') }}</option>
                        <option value="reminder">{{ __('views.reminder') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('views.notification_status') }}</label>
                    <select wire:model.live="filterStatus" class="form-control">
                        <option value="">{{ __('views.all') }}</option>
                        <option value="unread">{{ __('views.unread_status') }}</option>
                        <option value="read">{{ __('views.read_status') }}</option>
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
                                <th style="min-width: 200px">{{ __('views.notification_title') }}</th>
                                <th>{{ __('views.notification_type') }}</th>
                                <th>{{ __('views.notification_recipients') }}</th>
                                <th>{{ __('views.notification_class') }}</th>
                                <th>{{ __('views.notification_schedule') }}</th>
                                <th>{{ __('views.notification_status') }}</th>
                                <th>{{ __('views.notification_created_at') }}</th>
                                <th>{{ __('views.notification_actions') }}</th>
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
                                                'info' => __('views.info'),
                                                'warning' => __('views.warning'),
                                                'success' => __('views.success'),
                                                'danger' => __('views.danger'),
                                                'reminder' => __('views.reminder'),
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
                                            <span class="text-muted">{{ __('views.all_students') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($notification->classroom)
                                            <span
                                                class="badge bg-secondary">{{ $notification->classroom?->name ?? 'N/A' }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($notification->scheduled_at)
                                            <div>{{ $notification->scheduled_at->format('d/m/Y H:i') }}</div>
                                            @if ($notification->scheduled_at->isPast())
                                                <small class="text-warning fw-bold">{{ __('views.sent') }}</small>
                                            @else
                                                <small class="text-muted">{{ __('views.pending') }}</small>
                                            @endif
                                        @else
                                            <span class="text-success fw-bold">{{ __('views.sent') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($notification->is_read)
                                            <span class="badge bg-success">{{ __('views.read_status') }}</span>
                                        @else
                                            <span class="badge bg-warning">{{ __('views.unread_status') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $notification->created_at->format('d/m/Y') }}</div>
                                        <small
                                            class="text-muted">{{ $notification->created_at->format('H:i') }}</small>
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
                    <h5 class="mt-3 text-muted">{{ __('views.no_notifications_found') }}</h5>
                    <p class="text-muted">{{ __('views.create_first_notification') }}</p>
                    <button wire:click="create" class="btn btn-primary">
                        <i class="bi bi-plus-circle mr-2"></i><span class="d-none d-md-inline">Tạo thông báo
                            mới</span>
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
                        <h5 class="modal-title">{{ __('views.create_notification') }}</h5>
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
                                            {{ __('views.all_students') }}
                                        </label>
                                    </div>
                                </div>

                                @if (!$is_global)
                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('views.student') }}</label>
                                        <select wire:model="user_id" class="form-control">
                                            <option value="">{{ __('views.choose_student') }}</option>
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
                                    <input wire:model="scheduled_at" type="datetime-local"
                                        class="form-control @error('scheduled_at') is-invalid @enderror">
                                    @error('scheduled_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Để trống để gửi ngay</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Hết hạn</label>
                                    <input wire:model="expires_at" type="datetime-local"
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
                                wire:click="$set('showCreateModal', false)">{{ __('views.cancel') }}</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle mr-2"></i><span class="d-none d-md-inline">Tạo thông
                                    báo</span>
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
                        <h5 class="modal-title">{{ __('views.edit_notification') }}</h5>
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
                                    <input wire:model="scheduled_at" type="datetime-local"
                                        class="form-control @error('scheduled_at') is-invalid @enderror">
                                    @error('scheduled_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Để trống để gửi ngay</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Hết hạn</label>
                                    <input wire:model="expires_at" type="datetime-local"
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
                                wire:click="$set('showEditModal', false)">{{ __('views.cancel') }}</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle mr-2"></i><span class="d-none d-md-inline">Cập
                                    nhật</span>
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
                        <h5 class="modal-title">{{ __('views.confirm') }}</h5>
                        <button type="button" class="btn-close"
                            wire:click="$set('showDeleteModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ __('views.confirm_delete_notification') }}</p>
                        @if ($selectedNotification)
                            <div class="alert alert-warning">
                                <strong>{{ $selectedNotification->title }}</strong>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="$set('showDeleteModal', false)">{{ __('views.cancel') }}</button>
                        <button type="button" class="btn btn-danger" wire:click="confirmDelete">
                            <i class="bi bi-trash mr-2"></i><span class="d-none d-md-inline">Xóa</span>
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
                    <strong class="mr-auto">{{ __('views.success') }}</strong>
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

        /* Modal scrollable cho Bootstrap 4 */
        .modal-dialog {
            max-height: 90vh;
        }
        
        .modal-content {
            max-height: 90vh;
        }
        
        .modal-body {
            max-height: 60vh;
            overflow-y: auto;
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
