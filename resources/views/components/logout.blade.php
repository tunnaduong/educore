<div class="dropdown ms-3 position-relative">
    <button class="btn btn-link fw-bold text-white text-decoration-none dropdown-toggle" wire:click="toggleDropdown"
        type="button">
        {{ auth()->user()->name }}
        <i class="bi bi-person-circle fs-5 ms-2"></i>
    </button>

    @if ($showDropdown)
        <div class="dropdown-menu dropdown-menu-end show" style="z-index: 1050;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                </button>
            </form>
        </div>
    @endif

    <style>
        .dropdown-menu {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(0, 0, 0, 0.1);
            right: 0 !important;
            left: auto !important;
            transform: none !important;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .btn-link:hover {
            text-decoration: none;
        }
    </style>
</div>
