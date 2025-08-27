<x-layouts.dash-teacher active="notifications">
    @include('components.language')
    <?php $t=function($vi,$en,$zh){$l=app()->getLocale();return $l==='zh'?$zh:($l==='en'?$en:$vi);}; ?>
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-bell mr-2"></i>{{ $t('Thông báo & Nhắc lịch','Notifications & Reminders','通知与提醒') }}
                    </h4>
                    <p class="text-muted mb-0">{{ $t('Tạo và quản lý thông báo cho học viên','Create and manage notifications for students','为学员创建和管理通知') }}</p>
                </div>
                <div class="d-flex">
                    <button wire:click="markAllAsRead" class="btn btn-outline-secondary mr-2">
                        <i class="bi bi-check-all mr-2"></i><span class="d-none d-md-inline">{{ $t('Đánh dấu tất cả đã đọc','Mark all as read','全部标记为已读') }}</span>
                    </button>
                    <button wire:click="deleteExpired" class="btn btn-outline-warning mr-2">
                        <i class="bi bi-trash mr-2"></i><span class="d-none d-md-inline">{{ $t('Xóa hết hạn','Delete expired','删除已过期') }}</span>
                    </button>
                    <button wire:click="create" class="btn btn-primary">
                        <i class="bi bi-plus-circle mr-2"></i><span class="d-none d-md-inline">{{ $t('Tạo thông báo mới','Create notification','创建新通知') }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ $t('Tìm kiếm','Search','搜索') }}</label>
                        <input wire:model.live="search" type="text" class="form-control"
                            placeholder="{{ $t('Tìm theo tiêu đề hoặc nội dung...','Search by title or content...','按标题或内容搜索…') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ $t('Loại thông báo','Type','通知类型') }}</label>
                        <select wire:model.live="filterType" class="form-control">
                            <option value="">{{ $t('Tất cả','All','全部') }}</option>
                            <option value="info">{{ $t('Thông tin','Info','信息') }}</option>
                            <option value="warning">{{ $t('Cảnh báo','Warning','警告') }}</option>
                            <option value="success">{{ $t('Thành công','Success','成功') }}</option>
                            <option value="danger">{{ $t('Nguy hiểm','Danger','危险') }}</option>
                            <option value="reminder">{{ $t('Nhắc nhở','Reminder','提醒') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ $t('Trạng thái','Status','状态') }}</label>
                        <select wire:model.live="filterStatus" class="form-control">
                            <option value="">{{ $t('Tất cả','All','全部') }}</option>
                            <option value="unread">{{ $t('Chưa đọc','Unread','未读') }}</option>
                            <option value="read">{{ $t('Đã đọc','Read','已读') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button wire:click="$set('search', '')" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-clockwise mr-1"></i><span class="d-none d-md-inline">{{ $t('Reset','Reset','重置') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                @if ($notifications->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ $t('Tiêu đề','Title','标题') }}</th>
                                    <th>{{ $t('Loại','Type','类型') }}</th>
                                    <th>{{ $t('Lớp học','Class','班级') }}</th>
                                    <th>{{ $t('Lịch gửi','Schedule','发送时间') }}</th>
                                    <th>{{ $t('Trạng thái','Status','状态') }}</th>
                                    <th>{{ $t('Ngày tạo','Created at','创建日期') }}</th>
                                    <th>{{ $t('Thao tác','Actions','操作') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($notifications as $notification)
                                    <tr
                                        class="{{ $notification->scheduled_at && $notification->scheduled_at->isPast() ? 'table-warning' : '' }}">
                                        <td>
                                            <div class="fw-bold">{{ $notification->title }}</div>
                                            <small
                                                class="text-muted">{{ Str::limit($notification->message, 50) }}</small>
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
                                                    'info' => $t('Thông tin','Info','信息'),
                                                    'warning' => $t('Cảnh báo','Warning','警告'),
                                                    'success' => $t('Thành công','Success','成功'),
                                                    'danger' => $t('Nguy hiểm','Danger','危险'),
                                                    'reminder' => $t('Nhắc nhở','Reminder','提醒'),
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $typeColors[$notification->type] }}">
                                                {{ $typeLabels[$notification->type] }}
                                            </span>
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
                                                    <small class="text-warning fw-bold">{{ $t('Đã gửi','Sent','已发送') }}</small>
                                                @else
                                                    <small class="text-muted">{{ $t('Chờ gửi','Scheduled','等待发送') }}</small>
                                                @endif
                                            @else
                                                <span class="text-success fw-bold">{{ $t('Đã gửi','Sent','已发送') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($notification->is_read)
                                                <span class="badge bg-success">{{ $t('Đã đọc','Read','已读') }}</span>
                                            @else
                                                <span class="badge bg-warning">{{ $t('Chưa đọc','Unread','未读') }}</span>
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
                                                        class="btn btn-sm btn-outline-success" title="{{ $t('Đánh dấu đã đọc','Mark as read','标记为已读') }}">
                                                        <i class="bi bi-check"></i>
                                                    </button>
                                                @endif
                                                <button wire:click="edit({{ $notification->id }})"
                                                    class="btn btn-sm btn-outline-primary" title="{{ $t('Chỉnh sửa','Edit','编辑') }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button wire:click="duplicate({{ $notification->id }})"
                                                    class="btn btn-sm btn-outline-info" title="{{ $t('Sao chép','Duplicate','复制') }}">
                                                    <i class="bi bi-files"></i>
                                                </button>
                                                @if ($notification->scheduled_at && $notification->scheduled_at->isFuture())
                                                    <button wire:click="sendNow({{ $notification->id }})"
                                                        class="btn btn-sm btn-outline-success" title="{{ $t('Gửi ngay','Send now','立即发送') }}">
                                                        <i class="bi bi-send"></i>
                                                    </button>
                                                @endif
                                                <button wire:click="delete({{ $notification->id }})"
                                                    class="btn btn-sm btn-outline-danger" title="{{ $t('Xóa','Delete','删除') }}"
                                                    wire:confirm="{{ $t('Bạn có chắc chắn muốn xóa thông báo này?','Are you sure you want to delete this notification?','确定要删除此通知吗？') }}">
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
                        <h5 class="mt-3 text-muted">{{ $t('Không có thông báo nào','No notifications','暂无通知') }}</h5>
                        <p class="text-muted">{{ $t('Tạo thông báo đầu tiên để bắt đầu','Create your first notification to get started','创建第一条通知以开始使用') }}</p>
                        <button wire:click="create" class="btn btn-primary">
                            <i class="bi bi-plus-circle mr-2"></i><span class="d-none d-md-inline">{{ $t('Tạo thông báo mới','Create notification','创建新通知') }}</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    @if ($showCreateModal)
        <div class="modal fade show" wire:ignore.self id="createModal" tabindex="-1"
            style="display: block; background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $t('Tạo thông báo mới','Create notification','创建新通知') }}</h5>
                        <button type="button" class="btn-close"
                            wire:click="$set('showCreateModal', false)"></button>
                    </div>
                    <form wire:submit="store">
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">{{ $t('Tiêu đề','Title','标题') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        wire:model="title" placeholder="{{ $t('Nhập tiêu đề thông báo...','Enter notification title...','输入通知标题…') }}">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">{{ $t('Nội dung','Content','内容') }} <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" wire:model="message" rows="4"
                                        placeholder="{{ $t('Nhập nội dung thông báo...','Enter notification content...','输入通知内容…') }}"></textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ $t('Loại thông báo','Type','通知类型') }} <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror"
                                        wire:model="type">
                                        <option value="info">{{ $t('Thông tin','Info','信息') }}</option>
                                        <option value="warning">{{ $t('Cảnh báo','Warning','警告') }}</option>
                                        <option value="success">{{ $t('Thành công','Success','成功') }}</option>
                                        <option value="danger">{{ $t('Nguy hiểm','Danger','危险') }}</option>
                                        <option value="reminder">{{ $t('Nhắc nhở','Reminder','提醒') }}</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ $t('Lớp học','Class','班级') }}</label>
                                    <select class="form-control @error('class_id') is-invalid @enderror"
                                        wire:model="class_id">
                                        <option value="">{{ $t('Tất cả lớp học','All classes','全部班级') }}</option>
                                        @foreach ($classrooms as $classroom)
                                            <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ $t('Lịch gửi','Schedule','发送时间') }}</label>
                                    <input type="datetime-local"
                                        class="form-control @error('scheduled_at') is-invalid @enderror"
                                        wire:model="scheduled_at">
                                    @error('scheduled_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ $t('Để trống để gửi ngay','Leave empty to send now','留空则立即发送') }}</small>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" wire:model="is_urgent"
                                            id="is_urgent">
                                        <label class="form-check-label" for="is_urgent">
                                            {{ $t('Thông báo khẩn cấp','Urgent notification','紧急通知') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="$set('showCreateModal', false)">{{ $t('Huỷ','Cancel','取消') }}</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle mr-2"></i><span class="d-none d-md-inline">{{ $t('Tạo thông báo','Create','创建通知') }}</span>
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
                        <h5 class="modal-title">{{ $t('Chỉnh sửa thông báo','Edit notification','编辑通知') }}</h5>
                        <button type="button" class="btn-close" wire:click="$set('showEditModal', false)"></button>
                    </div>
                    <form wire:submit="update">
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">{{ $t('Tiêu đề','Title','标题') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        wire:model="title" placeholder="{{ $t('Nhập tiêu đề thông báo...','Enter notification title...','输入通知标题…') }}">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">{{ $t('Nội dung','Content','内容') }} <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" wire:model="message" rows="4"
                                        placeholder="{{ $t('Nhập nội dung thông báo...','Enter notification content...','输入通知内容…') }}"></textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ $t('Loại thông báo','Type','通知类型') }} <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror"
                                        wire:model="type">
                                        <option value="info">{{ $t('Thông tin','Info','信息') }}</option>
                                        <option value="warning">{{ $t('Cảnh báo','Warning','警告') }}</option>
                                        <option value="success">{{ $t('Thành công','Success','成功') }}</option>
                                        <option value="danger">{{ $t('Nguy hiểm','Danger','危险') }}</option>
                                        <option value="reminder">{{ $t('Nhắc nhở','Reminder','提醒') }}</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ $t('Lớp học','Class','班级') }}</label>
                                    <select class="form-control @error('class_id') is-invalid @enderror"
                                        wire:model="class_id">
                                        <option value="">{{ $t('Tất cả lớp học','All classes','全部班级') }}</option>
                                        @foreach ($classrooms as $classroom)
                                            <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ $t('Lịch gửi','Schedule','发送时间') }}</label>
                                    <input type="datetime-local"
                                        class="form-control @error('scheduled_at') is-invalid @enderror"
                                        wire:model="scheduled_at">
                                    @error('scheduled_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ $t('Để trống để gửi ngay','Leave empty to send now','留空则立即发送') }}</small>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" wire:model="is_urgent"
                                            id="is_urgent_edit">
                                        <label class="form-check-label" for="is_urgent_edit">
                                            {{ $t('Thông báo khẩn cấp','Urgent notification','紧急通知') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="$set('showEditModal', false)">{{ $t('Huỷ','Cancel','取消') }}</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle mr-2"></i><span class="d-none d-md-inline">{{ $t('Cập nhật thông báo','Update notification','更新通知') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <style>
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
    </style>
</x-layouts.dash-teacher>
