<x-layouts.dash-admin active="finance" title="{{ __('views.expense_management_title') }}">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="text-danger mb-1">
                    <i class="bi bi-wallet2 mr-2"></i>{{ __('views.expense_management_title') }}
                </h4>
                <p class="text-muted mb-0">{{ __('views.expense_management_description') }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.finance.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-arrow-left mr-2"></i>{{ __('views.back_to_finance_overview') }}
                </a>
                <button class="btn btn-danger btn-lg shadow-sm" wire:click="openCreateModal">
                    <i class="bi bi-plus-circle mr-2"></i>{{ __('views.add_expense') }}
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0 text-primary">
                    <i class="bi bi-funnel-fill mr-2"></i>{{ __('general.expense_filter') }}
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-tag-fill mr-1"></i>{{ __('general.expense_type') }}
                        </label>
                        <select wire:model="filterType" class="form-control">
                            <option value="">{{ __('views.all_types') }}</option>
                            <option value="salary">{{ __('views.expense_type_salary') }}</option>
                            <option value="utilities">{{ __('views.expense_type_utilities') }}</option>
                            <option value="maintenance">{{ __('views.expense_type_maintenance') }}</option>
                            <option value="supplies">{{ __('views.expense_type_supplies') }}</option>
                            <option value="marketing">{{ __('views.expense_type_marketing') }}</option>
                            <option value="training">{{ __('views.expense_type_training') }}</option>
                            <option value="other">{{ __('views.expense_type_other') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-calendar-month text-primary mr-1"></i>{{ __('views.month') }}
                        </label>
                        <input type="month" wire:model="filterMonth" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-person-fill text-success mr-1"></i>{{ __('general.employee') }}
                        </label>
                        <select wire:model="filterStaff" class="form-control">
                            <option value="">{{ __('views.all_staff') }}</option>
                            @foreach ($this->staffs as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="text-muted small">
                            <i class="bi bi-info-circle mr-1"></i>
                            {{ __('views.total_prefix') }} {{ number_format($expenses->sum('amount')) }}₫
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Expenses Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-gradient-danger text-white">
                <h6 class="mb-0">
                    <i class="bi bi-list-ul mr-2"></i>{{ __('views.expenses_list_title') }}
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="8%">#</th>
                            <th width="15%">
                                <i class="bi bi-tag-fill mr-1"></i>{{ __('general.expense_type') }}
                            </th>
                            <th width="15%">
                                <i class="bi bi-cash-stack mr-1"></i>{{ __('general.amount') }}
                            </th>
                            <th width="12%">
                                <i class="bi bi-person-fill mr-1"></i>{{ __('general.employee') }}
                            </th>
                            <th width="12%">
                                <i class="bi bi-building mr-1"></i>{{ __('general.class') }}
                            </th>
                            <th width="15%">
                                <i class="bi bi-calendar-event mr-1"></i>{{ __('general.expense_date') }}
                            </th>
                            <th width="13%">
                                <i class="bi bi-chat-left-text mr-1"></i>{{ __('general.notes') }}
                            </th>
                            <th class="text-center" width="10%">
                                <i class="bi bi-gear mr-1"></i>Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                            <tr class="align-middle">
                                <td class="text-center fw-bold text-primary">{{ $expense->id }}</td>
                                <td>
                                    @php
                                        $typeMap = [
                                            'salary' => [
                                                'label' => __('views.expense_type_salary'),
                                                'icon' => 'bi-people-fill',
                                                'color' => 'success',
                                            ],
                                            'utilities' => [
                                                'label' => __('views.expense_type_utilities'),
                                                'icon' => 'bi-lightning-fill',
                                                'color' => 'warning',
                                            ],
                                            'maintenance' => [
                                                'label' => __('views.expense_type_maintenance'),
                                                'icon' => 'bi-tools',
                                                'color' => 'info',
                                            ],
                                            'supplies' => [
                                                'label' => __('views.expense_type_supplies'),
                                                'icon' => 'bi-box-seam',
                                                'color' => 'secondary',
                                            ],
                                            'marketing' => [
                                                'label' => __('views.expense_type_marketing'),
                                                'icon' => 'bi-megaphone-fill',
                                                'color' => 'primary',
                                            ],
                                            'training' => [
                                                'label' => __('views.expense_type_training'),
                                                'icon' => 'bi-mortarboard-fill',
                                                'color' => 'info',
                                            ],
                                            'other' => [
                                                'label' => __('views.expense_type_other'),
                                                'icon' => 'bi-three-dots',
                                                'color' => 'secondary',
                                            ],
                                        ];
                                        $type = $typeMap[$expense->type] ?? [
                                            'label' => $expense->type,
                                            'icon' => 'bi-question',
                                            'color' => 'secondary',
                                        ];
                                    @endphp
                                    <span
                                        class="badge bg-{{ $type['color'] }} bg-opacity-10 text-{{ $type['color'] }} border border-{{ $type['color'] }}">
                                        <i class="bi {{ $type['icon'] }} mr-1"></i>{{ $type['label'] }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-danger">-{{ number_format($expense->amount) }}₫</span>
                                </td>
                                <td>
                                    @if ($expense->staff)
                                        <div class="d-flex align-items-center">
                                            <small class="fw-semibold">{{ $expense->staff->name }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($expense->classroom)
                                        <span class="badge bg-light text-dark border">
                                            <i
                                                class="bi bi-building-fill mr-1"></i>{{ $expense->classroom?->name ?? __('general.not_available') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small">{{ $expense->spent_at->format('d/m/Y') }}</div>
                                </td>
                                <td>
                                    @if ($expense->note)
                                        <i class="bi bi-chat-left-fill text-primary" title="{{ $expense->note }}"></i>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button wire:click="editExpense({{ $expense->id }})"
                                            class="btn btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button wire:click="deleteExpense({{ $expense->id }})"
                                            wire:confirm="Bạn có chắc muốn xóa khoản chi này?"
                                            class="btn btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-wallet text-muted" style="font-size: 3rem;"></i>
                                    <div class="mt-2 text-muted fs-5">{{ __('views.no_expenses') }}</div>
                                    <button class="btn btn-danger mt-3" wire:click="openCreateModal">
                                        <i class="bi bi-plus-circle mr-1"></i>{{ __('views.add_first_expense') }}
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        @if ($showCreateModal)
            <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">
                                <i class="bi {{ $editingExpenseId ? 'bi-pencil' : 'bi-plus-circle' }} mr-2"></i>
                                {{ $editingExpenseId ? __('views.edit_expense_title') : __('views.add_new_expense_title') }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white"
                                wire:click="closeCreateModal"></button>
                        </div>
                        <form wire:submit.prevent="{{ $editingExpenseId ? 'updateExpense' : 'createExpense' }}">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-semibold">{{ __('general.expense_type') }} *</label>
                                            <select wire:model="type" class="form-control" required>
                                                <option value="salary">💰 {{ __('views.expense_type_salary') }}</option>
                                                <option value="utilities">⚡ {{ __('views.expense_type_utilities') }}</option>
                                                <option value="maintenance">🔧 {{ __('views.expense_type_maintenance') }}</option>
                                                <option value="supplies">📦 {{ __('views.expense_type_supplies') }}</option>
                                                <option value="marketing">📢 {{ __('views.expense_type_marketing') }}</option>
                                                <option value="training">🎓 {{ __('views.expense_type_training') }}</option>
                                                <option value="other">➕ {{ __('views.expense_type_other') }}</option>
                                            </select>
                                            @error('type')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-semibold">{{ __('general.amount') }} *</label>
                                            <input type="number" wire:model="amount" class="form-control"
                                                min="1" required autocomplete="off">
                                            @error('amount')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-semibold">{{ __('general.employee') }}</label>
                                            <select wire:model="staff_id" class="form-control">
                                                <option value="">{{ __('views.select_employee_placeholder') }}</option>
                                                @foreach ($this->staffs as $staff)
                                                    <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('staff_id')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-semibold">{{ __('general.classroom') }}</label>
                                            <select wire:model="class_id" class="form-control">
                                                <option value="">{{ __('views.select_class_placeholder') }}</option>
                                                @foreach ($this->classrooms as $class)
                                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('class_id')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-semibold">{{ __('general.expense_date') }} *</label>
                                            <input type="date" wire:model="spent_at" class="form-control"
                                                required>
                                            @error('spent_at')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label fw-semibold">{{ __('general.notes') }}</label>
                                            <textarea wire:model="note" class="form-control" rows="2" placeholder="{{ __('views.expense_note_placeholder') }}"></textarea>
                                            @error('note')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    wire:click="closeCreateModal">{{ __('general.close') }}</button>
                                <button type="submit" class="btn btn-danger">
                                    <i
                                        class="bi {{ $editingExpenseId ? 'bi-check-circle' : 'bi-plus-circle' }} mr-1"></i>
                                    {{ $editingExpenseId ? __('general.update') : __('views.add_expense') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        .bg-gradient-danger {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%) !important;
        }
    </style>
</x-layouts.dash-admin>
