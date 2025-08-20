{{-- Ví dụ sử dụng component sidebar với menu tùy chỉnh --}}

@php
    // Ví dụ menu có submenu và badge
    $customMenuItems = [
        [
            'key' => 'dashboard',
            'label' => 'Trang chủ',
            'route' => route('dashboard'),
            'icon' => 'fas fa-tachometer-alt',
            'visible' => true,
        ],
        [
            'key' => 'users',
            'label' => 'Quản lý người dùng',
            'icon' => 'fas fa-users',
            'visible' => true,
            'children' => [
                [
                    'key' => 'users.list',
                    'label' => 'Danh sách',
                    'route' => '#',
                    'icon' => 'far fa-circle',
                    'visible' => true,
                ],
                [
                    'key' => 'users.create',
                    'label' => 'Thêm mới',
                    'route' => '#',
                    'icon' => 'far fa-plus-square',
                    'visible' => true,
                    'badge' => [
                        'text' => 'Mới',
                        'type' => 'success',
                    ],
                ],
            ],
        ],
        [
            'key' => 'settings',
            'label' => 'Cài đặt',
            'route' => '#',
            'icon' => 'fas fa-cog',
            'visible' => true,
            'badge' => [
                'text' => '3',
                'type' => 'warning',
            ],
        ],
        [
            'key' => 'hidden-menu',
            'label' => 'Menu ẩn',
            'route' => '#',
            'icon' => 'fas fa-eye-slash',
            'visible' => false, // Menu này sẽ không hiển thị
        ],
    ];
@endphp

{{-- Cách sử dụng 1: Sidebar với menu tùy chỉnh --}}
<x-sidebar active="users.create" :menu-items="$customMenuItems" brand-logo="{{ asset('custom-logo.png') }}" brand-text="Hệ thống ABC"
    brand-url="/admin" :dark-mode="false" />

{{-- Cách sử dụng 2: Sidebar với cấu hình mặc định --}}
<x-sidebar-config active="home" />

{{-- Cách sử dụng 3: Sidebar với menu được truyền từ controller/component --}}
{{-- 
Trong controller hoặc Livewire component:

public function render()
{
    $menuItems = [
        [
            'key' => 'products',
            'label' => 'Sản phẩm',
            'route' => route('products.index'),
            'icon' => 'fas fa-box',
            'visible' => auth()->user()->can('view_products')
        ],
        // ... thêm menu items khác
    ];
    
    return view('your-view', compact('menuItems'));
}

Trong view:
<x-sidebar :active="$active" :menu-items="$menuItems" />
--}}
