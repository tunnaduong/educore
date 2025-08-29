<x-layouts.dash-admin title="{{ __('views.tuition_payment_details') }}">
    @include('components.language')
    <div class="container py-4">
        <!-- {{ __('views.header_with_student_info') }} -->
        <div class="card border-0 shadow-sm mb-4 bg-gradient-info">
            <div class="card-body text-white p-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="mb-2 text-white">
                            <i class="bi bi-person-circle mr-2"></i>{{ $user->name }}
                        </h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="small text-white-50 mb-1">{{ __('general.email') }}</div>
                                <div class="fw-semibold">{{ $user->email }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="small text-white-50 mb-1">{{ __('views.student_code') }}</div>
                                <div class="fw-semibold">#{{ $user->id }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="small text-white-50 mb-1">{{ __('general.status') }}</div>
                                <span class="badge bg-light text-primary">{{ __('views.active_status') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-mortarboard-fill text-white-50" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- {{ __('views.payment_history_title') }} -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="bi bi-credit-card-2-front text-primary mr-2"></i>{{ __('views.payment_history_title') }}
            </h5>
            <button class="btn btn-primary btn-lg shadow-sm" wire:click="openCreateModal">
                <i class="bi bi-plus-circle mr-2"></i>{{ __('views.create_new_payment') }}
            </button>
        </div>

        <!-- Modal tạo payment -->
        @if ($showCreateModal)
            <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.3);">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('views.create_new_tuition_payment') }}</h5>
                            <button type="button" class="close"
                                wire:click="closeCreateModal"><span>&times;</span></button>
                        </div>
                        <form wire:submit.prevent="createPayment">
                            <div class="modal-body">
                                <div class="form-group mb-2">
                                    <label>{{ __('general.classroom') }}</label>
                                    <select wire:model.defer="newClassId" class="form-control" required>
                                        <option value="">{{ __('views.select_class_placeholder') }}</option>
                                        @foreach ($this->classrooms as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('newClassId')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-2">
                                    <label>{{ __('general.amount') }}</label>
                                    <input type="number" wire:model.defer="newAmount" class="form-control"
                                        min="0" required autocomplete="off">
                                    @error('newAmount')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-2">
                                    <label>{{ __('views.type') }}</label>
                                    <select wire:model.defer="newType" class="form-control">
                                        <option value="tuition">{{ __('views.finance_item_tuition') }}</option>
                                        <option value="material">{{ __('views.finance_item_material') }}</option>
                                        <option value="other">{{ __('views.finance_item_other') }}</option>
                                    </select>
                                    @error('newType')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-2">
                                    <label>{{ __('general.status') }}</label>
                                    <select wire:model.defer="newStatus" class="form-control">
                                        <option value="unpaid">{{ __('views.unpaid') }}</option>
                                        <option value="partial">{{ __('views.partial') }}</option>
                                        <option value="paid">{{ __('views.paid_full') }}</option>
                                    </select>
                                    @error('newStatus')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-2">
                                    <label>{{ __('views.payment_date') }}</label>
                                    <input type="datetime-local" wire:model.defer="newPaidAt" class="form-control">
                                    @error('newPaidAt')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-2">
                                    <label>{{ __('general.notes') }}</label>
                                    <textarea wire:model.defer="newNotes" class="form-control" rows="3" placeholder="{{ __('views.optional_note_placeholder') }}"></textarea>
                                    @error('newNotes')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    wire:click="closeCreateModal">{{ __('general.close') }}</button>
                                <button type="submit" class="btn btn-primary">{{ __('views.create_payment') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-center">#</th>
                            <th><i class="bi bi-building mr-1"></i>{{ __('general.classroom') }}</th>
                            <th><i class="bi bi-cash mr-1"></i>{{ __('general.amount') }}</th>
                            <th><i class="bi bi-tag mr-1"></i>{{ __('views.type') }}</th>
                            <th class="text-center"><i class="bi bi-check-circle mr-1"></i>{{ __('general.status') }}</th>
                            <th><i class="bi bi-calendar mr-1"></i>{{ __('views.payment_date') }}</th>
                            <th class="text-center"><i class="bi bi-image mr-1"></i>{{ __('views.proof') }}</th>
                            <th class="text-center"><i class="bi bi-gear mr-1"></i>{{ __('general.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr class="align-middle">
                                <td class="text-center fw-bold text-primary">{{ $payment->id }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $payment->classroom->name ?? '-' }}</div>
                                </td>
                                <td>
                                    <span
                                        class="fw-bold text-success">{{ number_format($payment->amount, 0, ',', '.') }}₫</span>
                                </td>
                                <td>
                                    @php
                                        $typeMap = [
                                            'tuition' => [
                                                'label' => 'Học phí',
                                                'icon' => 'bi-mortarboard',
                                                'color' => 'primary',
                                            ],
                                            'material' => [
                                                'label' => 'Tài liệu',
                                                'icon' => 'bi-book',
                                                'color' => 'info',
                                            ],
                                            'other' => [
                                                'label' => 'Khác',
                                                'icon' => 'bi-three-dots',
                                                'color' => 'secondary',
                                            ],
                                        ];
                                        $type = $typeMap[$payment->type] ?? [
                                            'label' => $payment->type,
                                            'icon' => 'bi-question',
                                            'color' => 'secondary',
                                        ];
                                    @endphp
                                    <span
                                        class="badge bg-{{ $type['color'] }} bg-opacity-10 text-{{ $type['color'] }} border border-{{ $type['color'] }}">
                                        <i class="bi {{ $type['icon'] }} mr-1"></i>{{ $type['label'] }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if ($payment->status === 'paid')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle-fill mr-1"></i>{{ __('views.paid_full') }}
                                        </span>
                                    @elseif($payment->status === 'partial')
                                        <span class="badge bg-warning">
                                            <i class="bi bi-exclamation-triangle-fill mr-1"></i>{{ __('views.partial') }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle-fill mr-1"></i>{{ __('views.unpaid') }}
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : '-' }}</td>
                                <td>
                                    @if ($payment->proof_path)
                                        <a href="{{ Storage::url($payment->proof_path) }}" target="_blank">
                                            <img src="{{ Storage::url($payment->proof_path) }}" width="80">
                                        </a>
                                    @endif
                                    <form wire:submit.prevent="uploadProof({{ $payment->id }})" class="mt-2">
                                        <input type="file" wire:model="proof" class="form-control-file mb-1">
                                        <button type="submit" class="btn btn-sm btn-primary">{{ __('views.upload_proof') }}</button>
                                    </form>
                                    @error('proof')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-primary" data-toggle="modal"
                                            data-target="#editStatusModal{{ $payment->id }}">{{ __('general.edit') }}</button>
                                        <button class="btn btn-outline-danger" data-toggle="modal"
                                            data-target="#confirmDeleteModal{{ $payment->id }}">{{ __('general.delete') }}</button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal sửa trạng thái -->
                            <div class="modal fade" id="editStatusModal{{ $payment->id }}" tabindex="-1"
                                role="dialog" aria-labelledby="editStatusLabel{{ $payment->id }}"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered"
                                    role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editStatusLabel{{ $payment->id }}">{{ __('views.edit_payment_status') }}</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Đóng">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>{{ __('views.payment_transaction_hash', ['id' => $payment->id]) }}</strong></p>
                                            <p>{{ __('general.amount') }}:
                                                <strong>{{ number_format($payment->amount, 0, ',', '.') }}₫</strong>
                                            </p>
                                            <p>{{ __('general.classroom') }}: <strong>{{ $payment->classroom->name ?? '-' }}</strong></p>
                                            <p>{{ __('general.notes') }}: <strong>{{ $payment->note ?? '-' }}</strong></p>

                                            <div class="form-group">
                                                <label>{{ __('views.choose_new_status') }}:</label>
                                                <div class="mt-2">
                                                    <button type="button" class="btn btn-success btn-block mb-2"
                                                        data-dismiss="modal"
                                                        wire:click="updateStatus({{ $payment->id }}, 'paid')">
                                                        <i class="fas fa-check-circle"></i> {{ __('views.paid_full') }}
                                                    </button>
                                                    <button type="button" class="btn btn-warning btn-block mb-2"
                                                        data-dismiss="modal"
                                                        wire:click="updateStatus({{ $payment->id }}, 'partial')">
                                                        <i class="fas fa-exclamation-triangle"></i> {{ __('views.partial') }}
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-block"
                                                        data-dismiss="modal"
                                                        wire:click="updateStatus({{ $payment->id }}, 'unpaid')">
                                                        <i class="fas fa-times-circle"></i> {{ __('views.unpaid') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">{{ __('general.close') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal xác nhận xóa -->
                            <div class="modal fade" id="confirmDeleteModal{{ $payment->id }}" tabindex="-1"
                                role="dialog" aria-labelledby="confirmDeleteLabel{{ $payment->id }}"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered"
                                    role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmDeleteLabel{{ $payment->id }}">{{ __('views.confirm_delete_payment_title') }}</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Đóng">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            {{ __('views.confirm_delete_payment_message') }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">{{ __('general.cancel') }}</button>
                                            <button type="button" data-dismiss="modal" class="btn btn-danger"
                                                wire:click="deletePayment({{ $payment->id }})">{{ __('general.delete') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                    <div class="mt-2 text-muted">{{ __('views.no_tuition_payments') }}</div>
                                    <button class="btn btn-primary mt-3" wire:click="openCreateModal">
                                        <i class="bi bi-plus-circle mr-1"></i>{{ __('views.create_first_payment') }}
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('admin.finance.index') }}" class="btn btn-outline-secondary btn-lg">
                <i class="bi bi-arrow-left mr-2"></i>{{ __('views.back_to_finance_overview') }}
            </a>
        </div>
    </div>
    <style>
        .bg-gradient-info {
            background: linear-gradient(135deg, #0dcaf0 0%, #6f42c1 100%) !important;
        }
    </style>

</x-layouts.dash-admin>
