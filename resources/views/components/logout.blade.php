<div class="dropdown ms-3 position-relative" x-data="{ open: false }">
    <button class="btn btn-link fw-bold text-white text-decoration-none dropdown-toggle" @click="open = !open"
        @click.away="open = false" type="button">
        {{ auth()->user()->name }}
        <i class="bi bi-person-circle fs-5 ms-2"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-end" :class="{ 'show': open }" style="z-index: 1050;" x-show="open"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item text-danger">
                <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
            </button>
        </form>
    </div>

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
