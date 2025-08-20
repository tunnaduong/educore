@php
    // Cấu hình menu mặc định cho giáo viên
    $teacherMenuItems = [
        [
            'key' => 'home',
            'label' => __('general.dashboard'),
            'route' => route('dashboard'),
            'icon' => 'bi bi-house',
            'visible' => true,
        ],
        [
            'key' => 'my-class',
            'label' => __('general.my_class'),
            'route' => route('teacher.my-class.index'),
            'icon' => 'bi bi-diagram-3',
            'visible' => true,
        ],
        [
            'key' => 'schedules',
            'label' => __('general.schedules'),
            'route' => route('teacher.schedules.index'),
            'icon' => 'bi bi-calendar3',
            'visible' => true,
        ],
        [
            'key' => 'attendances',
            'label' => __('general.attendance'),
            'route' => route('teacher.attendance.overview'),
            'icon' => 'bi bi-calendar-check',
            'visible' => true,
        ],
        [
            'key' => 'lessons',
            'label' => __('general.lessons'),
            'route' => route('teacher.lessons.index'),
            'icon' => 'bi bi-book',
            'visible' => true,
        ],
        [
            'key' => 'assignments',
            'label' => __('general.assignments'),
            'route' => route('teacher.assignments.index'),
            'icon' => 'bi bi-journal-text',
            'visible' => true,
        ],
        [
            'key' => 'quizzes',
            'label' => __('general.quizzes'),
            'route' => route('teacher.quizzes.index'),
            'icon' => 'bi bi-patch-question',
            'visible' => true,
        ],
        [
            'key' => 'grading',
            'label' => __('general.grading'),
            'route' => route('teacher.grading.index'),
            'icon' => 'bi bi-journal-check',
            'visible' => true,
        ],
        [
            'key' => 'ai',
            'label' => __('general.ai_assistant'),
            'route' => route('teacher.ai.index'),
            'icon' => 'bi bi-robot',
            'visible' => true,
        ],
        [
            'key' => 'notifications',
            'label' => __('general.notifications'),
            'route' => route('teacher.notifications.index'),
            'icon' => 'bi bi-bell',
            'visible' => true,
        ],
        [
            'key' => 'chat',
            'label' => __('general.chat'),
            'route' => route('teacher.chat.index'),
            'icon' => 'bi bi-chat-dots',
            'visible' => true,
        ],
        [
            'key' => 'reports',
            'label' => __('general.class_reports'),
            'route' => route('teacher.reports.index'),
            'icon' => 'bi bi-bar-chart',
            'visible' => true,
        ],
        [
            'key' => 'evaluations-report',
            'label' => 'Báo cáo đánh giá',
            'route' => route('teacher.evaluations.report'),
            'icon' => 'bi bi-star',
            'visible' => true,
        ],
    ];
@endphp

{{-- Component sidebar với cấu hình menu cho giáo viên --}}
<x-sidebar :active="$active ?? null" :menu-items="$menuItems ?? $teacherMenuItems" :brand-logo="asset('educore-logo.png')" :brand-text="__('general.app_name')" brand-url="/" :dark-mode="true" />
