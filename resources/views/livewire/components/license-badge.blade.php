@if ($license && $license->isActive())
    <li class="nav-item d-flex align-items-center mr-3">
        <!-- Badge gói -->
        <span class="badge badge-{{ $badgeColor }} px-3 py-2 mr-2" style="border-radius: 6px;">
            <i class="fas fa-gem mr-1"></i>{{ $planTypeLabel }}
        </span>

        <!-- Thông tin hết hạn -->
        <div class="d-flex align-items-center">
            <span class="text-muted small mr-2">Expire on:</span>
            @if ($license->is_lifetime)
                <span class="badge badge-warning">Lifetime</span>
            @elseif ($expiresAt)
                <span class="text-warning font-weight-bold">
                    {{ $expiresAt->format('d/m/Y') }}
                    @if ($daysRemaining !== null && $daysRemaining > 0)
                        <span class="text-muted">({{ $daysRemaining }} ngày)</span>
                    @endif
                </span>
            @endif
        </div>

        <!-- Link Renewal nếu sắp hết hạn -->
        @if ($daysRemaining !== null && $daysRemaining <= 7 && !$license->is_lifetime)
            <a href="{{ route('upgrade.index') }}" class="btn btn-sm btn-success ml-2">
                Renewal
            </a>
        @endif
    </li>
@else
    <li class="nav-item">
        <a href="{{ route('upgrade.index') }}" class="btn btn-sm btn-warning mr-2">
            <i class="fas fa-crown mr-1"></i>Nâng cấp
        </a>
    </li>
@endif
