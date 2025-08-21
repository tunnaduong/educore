@php
    // Cấu hình menu mặc định cho hệ thống EduCore
    $defaultMenuItems = [
        [
            'key' => 'home',
            'label' => __('general.dashboard'),
            'route' => route('dashboard'),
            'icon' => 'fas fa-home',
            'visible' => true,
        ],
        [
            'key' => 'attendances',
            'label' => __('general.attendance'),
            'route' => route('attendances.overview'),
            'icon' => 'fas fa-calendar-check',
            'visible' => true,
        ],
        [
            'key' => 'classrooms',
            'label' => __('general.classrooms'),
            'route' => route('classrooms.index'),
            'icon' => 'fas fa-graduation-cap',
            'visible' => true,
        ],
        [
            'key' => 'schedules',
            'label' => __('general.schedules'),
            'route' => route('schedules.index'),
            'icon' => 'fas fa-calendar-alt',
            'visible' => true,
        ],
        [
            'key' => 'assignments',
            'label' => __('general.assignments'),
            'route' => route('assignments.overview'),
            'icon' => 'fas fa-tasks',
            'visible' => true,
        ],
        [
            'key' => 'grading',
            'label' => __('general.grading'),
            'route' => route('grading.list'),
            'icon' => 'fas fa-check-circle',
            'visible' => true,
        ],
        [
            'key' => 'quizzes',
            'label' => __('general.quizzes'),
            'route' => route('quizzes.index'),
            'icon' => 'fas fa-question-circle',
            'visible' => true,
        ],
        [
            'key' => 'lessons',
            'label' => __('general.lessons'),
            'route' => route('lessons.index'),
            'icon' => 'fas fa-book',
            'visible' => true,
        ],
        [
            'key' => 'students',
            'label' => __('general.students'),
            'route' => route('students.index'),
            'icon' => 'fas fa-users',
            'visible' => true,
        ],
        [
            'key' => 'reports',
            'label' => __('general.reports'),
            'route' => route('reports.index'),
            'icon' => 'fas fa-chart-bar',
            'visible' => true,
        ],
        [
            'key' => 'finance',
            'label' => __('general.financial_statistics'),
            'route' => route('admin.finance.index'),
            'icon' => 'fas fa-coins',
            'visible' => true,
        ],
        [
            'key' => 'evaluation-management',
            'label' => __('general.evaluation_management'),
            'route' => route('evaluation-management'),
            'icon' => 'fas fa-star',
            'visible' => true,
        ],
        [
            'key' => 'ai',
            'label' => __('general.ai_assistant'),
            'route' => route('ai.index'),
            'icon' => 'fas fa-robot',
            'visible' => true,
        ],
        [
            'key' => 'notifications',
            'label' => __('general.notifications'),
            'route' => route('notifications.index'),
            'icon' => 'fas fa-bell',
            'visible' => true,
        ],
        [
            'key' => 'chat',
            'label' => __('general.chat'),
            'route' => route('chat.index'),
            'icon' => 'fas fa-comments',
            'visible' => true,
        ],
    ];
@endphp

{{-- Component sidebar với cấu hình menu mặc định --}}
<x-sidebar :active="$active ?? null" :menu-items="$menuItems ?? $defaultMenuItems" :brand-logo="asset('educore-logo.png')" :brand-text="__('general.app_name')" brand-url="/" :dark-mode="true" />
