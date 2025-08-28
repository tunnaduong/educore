<x-layouts.dash-teacher active="notifications">
    @include('components.language')
    
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-bell mr-2"></i>{{ __('general.notifications_reminders') }}
                    </h4>
                    <p class="text-muted mb-0">{{ __('general.create_and_manage_notifications') }}</p>
                </div>
                <div class="d-flex">
                    <button wire:click="markAllAsRead" class="btn btn-outline-secondary mr-2">
                        <i class="bi bi-check-all mr-2"></i><span class="d-none d-md-inline">{{ __('general.mark_all_as_read') }}</span>
                    </button>
                    <button wire:click="deleteExpired" class="btn btn-outline-warning mr-2">
                        <i class="bi bi-trash mr-2"></i><span class="d-none d-md-inline">{{ __('general.delete_expired') }}</span>
                    </button>
                    <button wire:click="create" class="btn btn-primary">
                        <i class="bi bi-plus-circle mr-2"></i><span class="d-none d-md-inline">{{ __('general.create_notification') }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.search') }}</label>
                        <input wire:model.live="search" type="text" class="form-control"
                            placeholder="{{ __('general.search_by_title_or_content') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.notification_type') }}</label>
                        <select wire:model.live="filterType" class="form-control">
                            <option value="">{{ __('general.all') }}</option>
                            <option value="info">{{ __('general.info') }}</option>
                            <option value="warning">{{ __('general.warning') }}</option>
                            <option value="success">{{ __('general.success') }}</option>
                            <option value="danger">{{ __('general.danger') }}</option>
                            <option value="reminder">{{ __('general.reminder') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.status') }}</label>
                        <select wire:model.live="filterStatus" class="form-control">
                            <option value="">{{ __('general.all') }}</option>
                            <option value="unread">{{ __('general.unread') }}</option>
                            <option value="read">{{ __('general.read') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button wire:click="$set('search', '')" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-clockwise mr-1"></i><span class="d-none d-md-inline">{{ __('general.reset') }}</span>
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
                                    <th>{{ __('general.title') }}</th>
                                    <th>{{ __('general.type') }}</th>
                                    <th>{{ __('general.classroom') }}</th>
                                    <th>{{ __('general.schedule') }}</th>
                                    <th>{{ __('general.status') }}</th>
                                    <th>{{ __('general.created_at') }}</th>
                                    <th>{{ __('general.actions') }}</th>
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
                                                    'info' => __('general.info'),
                                                    'warning' => __('general.warning'),
                                                    'success' => __('general.success'),
                                                    'danger' => __('general.danger'),
                                                    'reminder' => __('general.reminder'),
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $typeColors[$notification->type] }}">
                                                {{ $typeLabels[$notification->type] }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($notification->classroom)
                                                <span
                                                    class="badge bg-secondary">{{ $notification->classroom?->name ?? __('general.not_available') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($notification->scheduled_at)
                                                <div>{{ $notification->scheduled_at->format('d/m/Y H:i') }}</div>
                                                @if ($notification->scheduled_at->isPast())
                                                    <small class="text-warning fw-bold">{{ __('general.sent') }}</small>
                                                @else
                                                    <small class="text-muted">{{ __('general.scheduled') }}</small>
                                                @endif
                                            @else
                                                <span class="text-success fw-bold">{{ __('general.sent') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($notification->is_read)
                                                <span class="badge bg-success">{{ __('general.read') }}</span>
                                            @else
                                                <span class="badge bg-warning">{{ __('general.unread') }}</span>
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
                                                        class="btn btn-sm btn-outline-success" title="{{ __('general.mark_as_read') }}">
                                                        <i class="bi bi-check"></i>
                                                    </button>
                                                @endif
                                                <button wire:click="edit({{ $notification->id }})"
                                                    class="btn btn-sm btn-outline-primary" title="{{ __('general.edit') }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button wire:click="duplicate({{ $notification->id }})"
                                                    class="btn btn-sm btn-outline-info" title="{{ __('general.duplicate') }}">
                                                    <i class="bi bi-files"></i>
                                                </button>
                                                @if ($notification->scheduled_at && $notification->scheduled_at->isFuture())
                                                    <button wire:click="sendNow({{ $notification->id }})"
                                                        class="btn btn-sm btn-outline-success" title="{{ __('general.send_now') }}">
                                                        <i class="bi bi-send"></i>
                                                    </button>
                                                @endif
                                                <button wire:click="delete({{ $notification->id }})"
                                                    class="btn btn-sm btn-outline-danger" title="{{ __('general.delete') }}"
                                                    wire:confirm="{{ __('general.confirm_delete_notification') }}">
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
                        <h5 class="mt-3 text-muted">{{ __('general.no_notifications') }}</h5>
                        <p class="text-muted">{{ __('general.create_first_notification') }}</p>
                        <button wire:click="create" class="btn btn-primary">
                            <i class="bi bi-plus-circle mr-2"></i><span class="d-none d-md-inline">{{ __('general.create_notification') }}</span>
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
                        <h5 class="modal-title">{{ __('general.create_notification') }}</h5>
                        <button type="button" class="btn-close"
                            wire:click="$set('showCreateModal', false)"></button>
                    </div>
                    <form wire:submit="store">
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">{{ __('general.title') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        wire:model="title" placeholder="{{ __('general.enter_notification_title') }}">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">{{ __('general.content') }} <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" wire:model="message" rows="4"
                                        placeholder="{{ __('general.enter_notification_content') }}"></textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('general.notification_type') }} <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror"
                                        wire:model="type">
                                        <option value="info">{{ __('general.info') }}</option>
                                        <option value="warning">{{ __('general.warning') }}</option>
                                        <option value="success">{{ __('general.success') }}</option>
                                        <option value="danger">{{ __('general.danger') }}</option>
                                        <option value="reminder">{{ __('general.reminder') }}</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('general.classroom') }}</label>
                                    <select class="form-control @error('class_id') is-invalid @enderror"
                                        wire:model="class_id">
                                        <option value="">{{ __('general.all_classes') }}</option>
                                        @foreach ($classrooms as $classroom)
                                            <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('general.schedule') }}</label>
                                    <input type="datetime-local"
                                        class="form-control @error('scheduled_at') is-invalid @enderror"
                                        wire:model="scheduled_at">
                                    @error('scheduled_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('general.leave_empty_to_send_now') }}</small>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" wire:model="is_urgent"
                                            id="is_urgent">
                                        <label class="form-check-label" for="is_urgent">
                                            {{ __('general.urgent_notification') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="$set('showCreateModal', false)">{{ __('general.cancel') }}</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle mr-2"></i><span class="d-none d-md-inline">{{ __('general.create') }}</span>
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
                        <h5 class="modal-title">{{ __('general.edit_notification') }}</h5>
                        <button type="button" class="btn-close" wire:click="$set('showEditModal', false)"></button>
                    </div>
                    <form wire:submit="update">
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">{{ __('general.title') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        wire:model="title" placeholder="{{ __('general.enter_notification_title') }}">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">{{ __('general.content') }} <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" wire:model="message" rows="4"
                                        placeholder="{{ __('general.enter_notification_content') }}"></textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('general.notification_type') }} <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror"
                                        wire:model="type">
                                        <option value="info">{{ __('general.info') }}</option>
                                        <option value="warning">{{ __('general.warning') }}</option>
                                        <option value="success">{{ __('general.success') }}</option>
                                        <option value="danger">{{ __('general.danger') }}</option>
                                        <option value="reminder">{{ __('general.reminder') }}</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('general.classroom') }}</label>
                                    <select class="form-control @error('class_id') is-invalid @enderror"
                                        wire:model="class_id">
                                        <option value="">{{ __('general.all_classes') }}</option>
                                        @foreach ($classrooms as $classroom)
                                            <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('general.schedule') }}</label>
                                    <input type="datetime-local"
                                        class="form-control @error('scheduled_at') is-invalid @enderror"
                                        wire:model="scheduled_at">
                                    @error('scheduled_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('general.leave_empty_to_send_now') }}</small>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" wire:model="is_urgent"
                                            id="is_urgent_edit">
                                        <label class="form-check-label" for="is_urgent_edit">
                                            {{ __('general.urgent_notification') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="$set('showEditModal', false)">{{ __('general.cancel') }}</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle mr-2"></i><span class="d-none d-md-inline">{{ __('general.update_notification') }}</span>
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
