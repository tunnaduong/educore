@props([
    'active' => null,
    'brandLogo' => asset('educore-logo.png'),
    'brandText' => __('general.app_name'),
    'brandUrl' => '/',
    'menuItems' => [],
    'darkMode' => true,
])

<!-- Main Sidebar Container -->
<aside class="main-sidebar {{ $darkMode ? 'sidebar-dark-primary' : 'sidebar-light-primary' }} elevation-4">
    <!-- Brand Logo -->
    <a href="{{ $brandUrl }}" class="brand-link d-flex align-items-center">
        <img src="{{ $brandLogo }}" alt="Brand Logo" class="brand-image mr-2" style="max-height: 33px;">
        <span class="brand-text font-weight-light">{{ $brandText }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @foreach ($menuItems as $item)
                    @if (isset($item['children']) && count($item['children']) > 0)
                        <!-- Menu với submenu -->
                        <li
                            class="nav-item {{ $active === $item['key'] || in_array($active, collect($item['children'])->pluck('key')->toArray()) ? 'menu-open' : '' }}">
                            <a href="#"
                                class="nav-link {{ $active === $item['key'] || in_array($active, collect($item['children'])->pluck('key')->toArray()) ? 'active' : '' }}">
                                <i class="nav-icon {{ $item['icon'] ?? 'fas fa-circle' }}"></i>
                                <p>
                                    {{ $item['label'] }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @foreach ($item['children'] as $child)
                                    @if (isset($child['visible']) && !$child['visible'])
                                        @continue
                                    @endif
                                    <li class="nav-item">
                                        <a href="{{ $child['route'] }}"
                                            class="nav-link {{ $active === $child['key'] ? 'active' : '' }}">
                                            <i class="nav-icon {{ $child['icon'] ?? 'far fa-circle' }}"></i>
                                            <p>{{ $child['label'] }}</p>
                                            @if (isset($child['badge']))
                                                <span
                                                    class="badge badge-{{ $child['badge']['type'] ?? 'info' }} right">
                                                    {{ $child['badge']['text'] }}
                                                </span>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <!-- Menu đơn giản -->
                        @if (isset($item['visible']) && !$item['visible'])
                            @continue
                        @endif
                        <li class="nav-item">
                            <a href="{{ $item['route'] }}"
                                class="nav-link {{ $active === $item['key'] ? 'active' : '' }}">
                                <i class="nav-icon {{ $item['icon'] ?? 'fas fa-circle' }}"></i>
                                <p>
                                    {{ $item['label'] }}
                                    @if (isset($item['badge']))
                                        <span class="badge badge-{{ $item['badge']['type'] ?? 'info' }} right">
                                            {{ $item['badge']['text'] }}
                                        </span>
                                    @endif
                                </p>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
