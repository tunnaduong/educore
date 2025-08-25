<div class="dropdown position-relative" x-data="{ open: false }">
    <button class="btn btn-link font-weight-bold text-muted text-decoration-none dropdown-toggle" @click="open = !open"
        @click.away="open = false" type="button">
        <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
        <i class="fas fa-user-circle ml-2"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-right" :class="{ 'show': open }" style="z-index: 1050;" x-show="open"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item text-danger">
                <i class="fas fa-sign-out-alt mr-2"></i>{{ __('general.logout') }}
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
