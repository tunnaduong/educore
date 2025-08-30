<x-layouts.dash-student active="notifications">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-bell mr-2"></i>{{ __('views.student_pages.notifications.index.title') }}
                    </h4>
                    <p class="text-muted mb-0">{{ __('views.student_pages.notifications.index.subtitle') }}</p>
                </div>
                <div class="d-flex gap-2">
                    @if ($this->unreadCount > 0)
                        <button wire:click="markAllAsRead" class="btn btn-outline-primary">
                            <i class="bi bi-check-all mr-2"></i>{{ __('views.student_pages.notifications.index.mark_all_read') }}
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
                        <label class="form-label">{{ __('views.student_pages.notifications.index.search') }}</label>
                        <input wire:model.live="search" type="text" class="form-control"
                            placeholder="{{ __('views.student_pages.notifications.index.search_placeholder') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('views.student_pages.notifications.index.notification_type') }}</label>
                        <select wire:model.live="filterType" class="form-control">
                            <option value="">{{ __('views.student_pages.notifications.index.all') }}</option>
                            <option value="info">{{ __('views.student_pages.notifications.index.info') }}</option>
                            <option value="warning">{{ __('views.student_pages.notifications.index.warning') }}</option>
                            <option value="success">{{ __('views.student_pages.notifications.index.success') }}</option>
                            <option value="danger">{{ __('views.student_pages.notifications.index.danger') }}</option>
                            <option value="reminder">{{ __('views.student_pages.notifications.index.reminder') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('views.student_pages.notifications.index.status') }}</label>
                        <select wire:model.live="filterStatus" class="form-control">
                            <option value="">{{ __('views.student_pages.notifications.index.all') }}</option>
                            <option value="unread">{{ __('views.student_pages.notifications.index.unread') }}</option>
                            <option value="read">{{ __('views.student_pages.notifications.index.read') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button wire:click="resetFilters" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-clockwise mr-1"></i>{{ __('views.student_pages.notifications.index.reset') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-list-ul mr-2"></i>{{ __('views.student_pages.notifications.index.notifications_list') }}
                </h6>
            </div>
            <div class="card-body">
                @if ($notifications->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('views.student_pages.notifications.index.notification') }}</th>
                                    <th>{{ __('views.student_pages.notifications.index.type') }}</th>
                                    <th>{{ __('views.student_pages.notifications.index.class') }}</th>
                                    <th>{{ __('views.student_pages.notifications.index.time') }}</th>
                                    <th>{{ __('views.student_pages.notifications.index.status') }}</th>
                                    <th>{{ __('views.student_pages.notifications.index.actions') }}</th>
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
                                                    class="bi {{ $typeIcons[$notification->type] }} text-{{ $typeColors[$notification->type] }} mr-2 mt-1"></i>
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
                                                    'info' => __('views.student_pages.notifications.index.info'),
                                                    'warning' => __('views.student_pages.notifications.index.warning'),
                                                    'success' => __('views.student_pages.notifications.index.success'),
                                                    'danger' => __('views.student_pages.notifications.index.danger'),
                                                    'reminder' => __('views.student_pages.notifications.index.reminder'),
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
                                                        class="bi bi-diagram-3 mr-1"></i>{{ $notification->classroom?->name ?? 'N/A' }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <small class="text-muted">
                                                    <i class="bi bi-clock mr-1"></i>
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </small>
                                                @if ($notification->scheduled_at)
                                                    <br>
                                                    <small class="text-info">
                                                        <i class="bi bi-calendar-event mr-1"></i>
                                                        {{ __('views.student_pages.notifications.index.scheduled') }}: {{ $notification->scheduled_at->format('d/m/Y H:i') }}
                                                    </small>
                                                @endif
                                            </div>
                                        </td>

                                        <td>
                                            @if ($notification->is_read)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check mr-1"></i>{{ __('views.student_pages.notifications.index.read') }}
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-exclamation mr-1"></i>{{ __('views.student_pages.notifications.index.new') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (!$notification->is_read)
                                                <button wire:click="markAsRead({{ $notification->id }})"
                                                    class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-check mr-1"></i>{{ __('views.student_pages.notifications.index.mark_read') }}
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
                    <div>
                        {{ $notifications->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-bell-slash fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('views.student_pages.notifications.index.no_notifications') }}</h5>
                        <p class="text-muted">{{ __('views.student_pages.notifications.index.no_notifications_desc') }}</p>
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
                    <i class="bi bi-check-circle text-success mr-2"></i>
                    <strong class="mr-auto">{{ __('views.student_pages.notifications.index.success_message') }}</strong>
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
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
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
                                class="bi {{ $typeIcons[$selectedNotification->type] }} text-{{ $typeColors[$selectedNotification->type] }} mr-2"></i>
                            <h5 class="modal-title" id="notificationModalLabel">{{ $selectedNotification->title }}
                            </h5>
                        </div>
                        <button type="button" class="btn-close" wire:click="closeNotification"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <strong>{{ __('views.student_pages.notifications.index.content') }}:</strong>
                            <div class="mt-2 p-3 bg-light rounded">
                                {{ $selectedNotification->message }}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <strong>{{ __('views.student_pages.notifications.index.notification_type_label') }}:</strong>
                                @php
                                    $typeLabels = [
                                        'info' => __('views.student_pages.notifications.index.info'),
                                        'warning' => __('views.student_pages.notifications.index.warning'),
                                        'success' => __('views.student_pages.notifications.index.success'),
                                        'danger' => __('views.student_pages.notifications.index.danger'),
                                        'reminder' => __('views.student_pages.notifications.index.reminder'),
                                    ];
                                @endphp
                                <span class="badge bg-{{ $typeColors[$selectedNotification->type] }} ml-2">
                                    {{ $typeLabels[$selectedNotification->type] }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                <strong>{{ __('views.student_pages.notifications.index.status_label') }}:</strong>
                                @if ($selectedNotification->is_read)
                                    <span class="badge bg-success ml-2">
                                        <i class="bi bi-check mr-1"></i>{{ __('views.student_pages.notifications.index.read') }}
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark ml-2">
                                        <i class="bi bi-exclamation mr-1"></i>{{ __('views.student_pages.notifications.index.new') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if ($selectedNotification->classroom)
                            <div class="mt-3">
                                <strong>{{ __('views.student_pages.notifications.index.classroom') }}:</strong>
                                <span class="badge bg-secondary ml-2">
                                    <i
                                        class="bi bi-diagram-3 mr-1"></i>{{ $selectedNotification->classroom?->name ?? 'N/A' }}
                                </span>
                            </div>
                        @endif

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <strong>{{ __('views.student_pages.notifications.index.created_time') }}:</strong>
                                <div class="text-muted">{{ $selectedNotification->created_at->format('d/m/Y H:i:s') }}
                                </div>
                            </div>
                            @if ($selectedNotification->scheduled_at)
                                <div class="col-md-6">
                                    <strong>{{ __('views.student_pages.notifications.index.scheduled_time') }}:</strong>
                                    <div class="text-muted">
                                        {{ $selectedNotification->scheduled_at->format('d/m/Y H:i:s') }}</div>
                                </div>
                            @endif
                        </div>

                        @if ($selectedNotification->expires_at)
                            <div class="mt-3">
                                <strong>{{ __('views.student_pages.notifications.index.expiry_time') }}:</strong>
                                <div class="text-muted">{{ $selectedNotification->expires_at->format('d/m/Y H:i:s') }}
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        @if (!$selectedNotification->is_read)
                            <button type="button" class="btn btn-success"
                                wire:click="markAsRead({{ $selectedNotification->id }})">
                                <i class="bi bi-check mr-1"></i>{{ __('views.student_pages.notifications.index.mark_read') }}
                            </button>
                        @endif
                        <button type="button" class="btn btn-secondary" wire:click="closeNotification">{{ __('views.student_pages.notifications.index.close') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</x-layouts.dash-student>
