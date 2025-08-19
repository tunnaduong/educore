@php
    // Cấu hình menu mặc định cho học sinh
    $studentMenuItems = [
        [
            'key' => 'home',
            'label' => __('general.dashboard'),
            'route' => route('dashboard'),
            'icon' => 'bi bi-house',
            'visible' => true,
        ],
        [
            'key' => 'lessons',
            'label' => __('general.lessons'),
            'route' => route('student.lessons.index'),
            'icon' => 'bi bi-book',
            'visible' => true,
        ],
        [
            'key' => 'assignments',
            'label' => __('general.assignments'),
            'route' => route('student.assignments.overview'),
            'icon' => 'bi bi-journal-text',
            'visible' => true,
        ],
        [
            'key' => 'quizzes',
            'label' => __('general.quizzes'),
            'route' => route('student.quizzes.index'),
            'icon' => 'bi bi-patch-question',
            'visible' => true,
        ],
        [
            'key' => 'schedules',
            'label' => __('general.schedules'),
            'route' => route('student.schedules'),
            'icon' => 'bi bi-calendar3',
            'visible' => true,
        ],
        [
            'key' => 'reports',
            'label' => __('general.reports'),
            'route' => route('student.reports.index'),
            'icon' => 'bi bi-bar-chart',
            'visible' => true,
        ],
        [
            'key' => 'notifications',
            'label' => __('general.notifications'),
            'route' => route('student.notifications.index'),
            'icon' => 'bi bi-bell',
            'visible' => true,
        ],
        [
            'key' => 'chat',
            'label' => __('general.chat'),
            'route' => route('student.chat.index'),
            'icon' => 'bi bi-chat-dots',
            'visible' => true,
        ],
    ];
@endphp

{{-- Component sidebar với cấu hình menu cho học sinh --}}
<x-sidebar :active="$active ?? null" :menu-items="$menuItems ?? $studentMenuItems" :brand-logo="asset('educore-logo.png')" :brand-text="__('general.app_name')" brand-url="/" :dark-mode="true" />
