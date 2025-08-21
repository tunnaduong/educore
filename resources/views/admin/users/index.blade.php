<x-layouts.dash-admin>
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-people-fill mr-2"></i>{{ __('general.user_management') }}
            </h4>
            <a href="{{ route('users.create') ?? '#' }}" class="btn btn-primary">
                <i class="bi bi-plus-circle mr-2"></i>{{ __('general.add_user') }}
            </a>
        </div>

        <!-- Search Bar & Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-2 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-search"></i>
                            </span>
                            <input wire:model.live="search" type="text" class="form-control"
                                placeholder="{{ __('general.search_by_name_phone_email') }}">
                        </div>
                    </div>
                    <div class="col">
                        <select wire:model.live="filterRole" class="form-control">
                            <option value="">{{ __('general.all_roles') }}</option>
                            <option value="admin">{{ __('general.administrator') }}</option>
                            <option value="teacher">{{ __('general.instructor') }}</option>
                            <option value="student">{{ __('general.learner') }}</option>
                        </select>
                    </div>
                    <div class="col">
                        <select wire:model.live="filterStatus" class="form-control">
                            <option value="">{{ __('general.all_statuses') }}</option>
                            <option value="active">{{ __('general.active_status') }}</option>
                            <option value="inactive">{{ __('general.inactive_status') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill mr-2"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Users Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                                                            <tr>
                                    <th class="text-center">#</th>
                                    <th>{{ __('general.full_name') }}</th>
                                    <th>Email</th>
                                    <th class="text-center">{{ __('general.role') }}</th>
                                    <th class="text-center">{{ __('general.status') }}</th>
                                    <th class="text-end">{{ __('general.actions') }}</th>
                                </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $user)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm mr-3">
                                                <i class="bi bi-person-circle fs-4"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $user->name }}</div>
                                                <small class="text-muted">{{ $user->phone }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'teacher' ? 'success' : 'info') }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                            {{ $user->is_active ? __('general.active_status') : __('general.inactive_status') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('users.edit', $user->id) ?? '#' }}"
                                                class="btn btn-sm btn-outline-primary" title="{{ __('general.edit') }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <button type="button" data-toggle="modal"
                                                data-target="#deleteModal{{ $user->id }}"
                                                class="btn btn-sm btn-outline-danger" title="{{ __('general.delete') }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1"
                                    role="dialog" aria-labelledby="deleteModalLabel{{ $user->id }}"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered"
                                        role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">
                                                    <i class="bi bi-exclamation-triangle text-danger mr-2"></i>
                                                    {{ __('general.confirm_delete_user') }}
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center mb-3">
                                                    <i class="bi bi-person-x text-danger" style="font-size: 3rem;"></i>
                                                </div>
                                                <p class="text-center">
                                                    {{ __('general.confirm_delete_user_message') }}
                                                    <strong>"{{ $user->name }}"</strong>?
                                                </p>
                                                <div class="alert alert-warning">
                                                    <i class="bi bi-exclamation-triangle mr-2"></i>
                                                    <strong>{{ __('general.warning_action_irreversible') }}</strong>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">
                                                    <i class="bi bi-x-circle mr-1"></i>{{ __('general.cancel') }}
                                                </button>
                                                <button type="button" class="btn btn-danger"
                                                    wire:click="delete({{ $user->id }})" data-dismiss="modal">
                                                    <i class="bi bi-trash mr-1"></i>{{ __('general.delete_user') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $users->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
