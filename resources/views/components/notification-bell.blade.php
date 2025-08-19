<div>
    <div class="dropdown" x-data="{ open: false }">
        <button class="btn btn-link text-muted text-decoration-none position-relative" @click="open = !open"
            @click.away="open = false" type="button">
            <i class="fas fa-bell"></i>
            @if ($unreadCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge badge-danger badge-pill"
                    style="font-size: 0.7rem;">
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>
            @endif
        </button>

        <div class="dropdown-menu dropdown-menu-left" :class="{ 'show': open }"
            style="width: 350px; max-height: 400px; overflow-y: auto; z-index: 1050;" x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95">
            <div class="dropdown-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">{{ __('views.notifications') }}</h6>
                @if ($unreadCount > 0)
                    <small class="text-muted">{{ $unreadCount }} {{ __('views.unread') }}</small>
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
                                            'info' => 'fas fa-info-circle',
                                            'warning' => 'fas fa-exclamation-triangle',
                                            'success' => 'fas fa-check-circle',
                                            'danger' => 'fas fa-times-circle',
                                            'reminder' => 'fas fa-clock',
                                        ];
                                    @endphp
                                    <i
                                        class="{{ $typeIcons[$notification->type] }} text-{{ $typeColors[$notification->type] }} mr-2"></i>
                                    <h6 class="mb-0 {{ $notification->is_read ? '' : 'font-weight-bold' }}"
                                        style="font-size: 0.9rem;">
                                        {{ Str::limit($notification->title, 30) }}
                                    </h6>
                                </div>
                                <p class="mb-1 text-muted" style="font-size: 0.8rem;">
                                    {{ Str::limit($notification->message, 45) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    @if (!$notification->is_read)
                                        <button wire:click="markAsRead({{ $notification->id }})"
                                            class="btn btn-sm btn-outline-primary" style="font-size: 0.7rem;">
                                            {{ __('views.read_status') }}
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
                    <i class="fas fa-bell-slash text-muted" style="font-size: 2rem;"></i>
                    <p class="text-muted mb-0 mt-2">{{ __('views.no_notifications') }}</p>
                </div>
            @endif
        </div>
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
