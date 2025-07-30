<div>
    <div class="dropdown">
        <button class="btn btn-link text-white text-decoration-none position-relative" wire:click="toggleDropdown"
            type="button">
            <i class="bi bi-bell fs-5"></i>
            @if ($unreadCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                    style="font-size: 0.7rem;">
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>
            @endif
        </button>

        @if ($showDropdown)
            <div class="dropdown-menu dropdown-menu-start show"
                style="width: 350px; max-height: 400px; overflow-y: auto; z-index: 1050;">
                <div class="dropdown-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Thông báo</h6>
                    @if ($unreadCount > 0)
                        <small class="text-muted">{{ $unreadCount }} chưa đọc</small>
                    @endif
                </div>

                @if ($this->recentNotifications->count() > 0)
                    @foreach ($this->recentNotifications as $notification)
                        <div class="dropdown-item {{ $notification->is_read ? '' : 'bg-light' }} p-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-1">
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
                                            class="bi {{ $typeIcons[$notification->type] }} text-{{ $typeColors[$notification->type] }} me-2"></i>
                                        <h6 class="mb-0 {{ $notification->is_read ? '' : 'fw-bold' }}"
                                            style="font-size: 0.9rem;">
                                            {{ Str::limit($notification->title, 30) }}
                                        </h6>
                                    </div>
                                    <p class="mb-1 text-muted" style="font-size: 0.8rem;">
                                        {{ Str::limit($notification->message, 45) }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small
                                            class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        @if (!$notification->is_read)
                                            <button wire:click="markAsRead({{ $notification->id }})"
                                                class="btn btn-sm btn-outline-primary" style="font-size: 0.7rem;">
                                                Đã đọc
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="dropdown-item text-center p-2">
                        <a href="{{ auth()->user()->role === 'admin' ? route('notifications.index') : route('student.notifications.index') }}"
                            class="text-decoration-none">
                            Xem tất cả thông báo
                        </a>
                    </div>
                @else
                    <div class="dropdown-item text-center p-4">
                        <i class="bi bi-bell-slash text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mb-0 mt-2">Không có thông báo mới</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <style>
        .dropdown-menu {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(0, 0, 0, 0.1);
            right: auto !important;
            left: 30px !important;
            transform: translateX(-100%) !important;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .dropdown-item.bg-light {
            background-color: #f8f9fa !important;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn:disabled:hover {
            opacity: 0.6;
        }
    </style>
</div>
