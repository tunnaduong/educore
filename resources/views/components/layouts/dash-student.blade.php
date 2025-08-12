@props(['active' => null, 'title' => __('general.dashboard')])

@persist('chrome')
<div class="wrapper">
    @include('components.evaluation')
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" data-enable-remember="true" href="#" role="button"><i class="bi bi-list"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                    @switch(app()->getLocale())
                        @case('vi')
                            <img src="https://twemoji.maxcdn.com/v/latest/svg/1f1fb-1f1f3.svg" width="16" height="16"
                                alt="VN"> @lang('general.vietnamese')
                        @break

                        @case('en')
                            <img src="https://twemoji.maxcdn.com/v/latest/svg/1f1ec-1f1e7.svg" width="16" height="16"
                                alt="GB"> @lang('general.english')
                        @break

                        @case('zh')
                            <img src="https://twemoji.maxcdn.com/v/latest/svg/1f1e8-1f1f3.svg" width="16" height="16"
                                alt="CN"> @lang('general.chinese')
                        @break

                        @default
                            @lang('general.language')
                    @endswitch
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ route('lang.switch', 'vi') }}" class="dropdown-item" wire:navigate><img
                            src="https://twemoji.maxcdn.com/v/latest/svg/1f1fb-1f1f3.svg" width="16" height="16"
                            alt="VN"> @lang('general.vietnamese')</a>
                    <a href="{{ route('lang.switch', 'en') }}" class="dropdown-item" wire:navigate><img
                            src="https://twemoji.maxcdn.com/v/latest/svg/1f1ec-1f1e7.svg" width="16" height="16"
                            alt="GB"> @lang('general.english')</a>
                    <a href="{{ route('lang.switch', 'zh') }}" class="dropdown-item" wire:navigate><img
                            src="https://twemoji.maxcdn.com/v/latest/svg/1f1e8-1f1f3.svg" width="16" height="16"
                            alt="CN"> @lang('general.chinese')</a>
                </div>
            </li>
            <li class="nav-item"><livewire:components.notification-bell /></li>
            <li class="nav-item">
                <livewire:components.logout />
            </li>
        </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="/" class="brand-link d-flex align-items-center" wire:navigate>
            <img src="{{ asset('educore-logo.png') }}" alt="EduCore Logo" class="brand-image mr-2"
                style="max-height: 33px;">
            <span class="brand-text font-weight-light">@lang('general.app_name')</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" data-accordion="false" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ $active === 'home' ? 'active' : '' }}"
                            wire:navigate>
                            <i class="nav-icon bi bi-house"></i>
                            <p>@lang('general.dashboard')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('student.lessons.index') }}"
                            class="nav-link {{ $active === 'lessons' ? 'active' : '' }}" wire:navigate>
                            <i class="nav-icon bi bi-book"></i>
                            <p>@lang('general.lessons')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('student.assignments.overview') }}"
                            class="nav-link {{ $active === 'assignments' ? 'active' : '' }}" wire:navigate>
                            <i class="nav-icon bi bi-journal-text"></i>
                            <p>@lang('general.assignments')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('student.quizzes.index') }}"
                            class="nav-link {{ $active === 'quizzes' ? 'active' : '' }}" wire:navigate>
                            <i class="nav-icon bi bi-patch-question"></i>
                            <p>@lang('general.quizzes')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('student.schedules') }}"
                            class="nav-link {{ $active === 'schedules' ? 'active' : '' }}" wire:navigate>
                            <i class="nav-icon bi bi-calendar3"></i>
                            <p>@lang('general.schedules')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('student.reports.index') }}"
                            class="nav-link {{ $active === 'reports' ? 'active' : '' }}" wire:navigate>
                            <i class="nav-icon bi bi-bar-chart"></i>
                            <p>@lang('general.reports')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('student.evaluation.index') }}"
                            class="nav-link {{ $active === 'evaluation' ? 'active' : '' }}" wire:navigate>
                            <i class="nav-icon bi bi-star"></i>
                            <p>@lang('general.evaluation')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('student.notifications.index') }}"
                            class="nav-link {{ $active === 'notifications' ? 'active' : '' }}" wire:navigate>
                            <i class="nav-icon bi bi-bell"></i>
                            <p>@lang('general.notifications')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('student.chat.index') }}"
                            class="nav-link {{ $active === 'chat' ? 'active' : '' }}" wire:navigate>
                            <i class="nav-icon bi bi-chat-dots"></i>
                            <p>@lang('general.chat')</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
@endpersist

    <div class="content-wrapper">
        <section class="content pt-3">
            <div class="container-fluid">
                {{ $slot }}
            </div>
        </section>
    </div>

    <footer class="main-footer text-center">
        <strong>© 2025 Trung tâm Hanxian Kim Bảng Hà Nam - Powered by EduCore</strong>
    </footer>
</div>

<script>
function setActiveMenu() {
    // Remove "active" from all .nav-link
    document.querySelectorAll('.nav-sidebar .nav-link').forEach(link => {
        link.classList.remove('active');
    });
    
    // Remove "menu-open" from all .has-treeview
    document.querySelectorAll('.nav-sidebar .has-treeview').forEach(item => {
        item.classList.remove('menu-open');
    });
    
    // Find first link whose href path matches location.pathname
    const currentPath = window.location.pathname;
    const activeLink = document.querySelector(`.nav-sidebar .nav-link[href*="${currentPath}"]`);
    
    if (activeLink) {
        // Add "active" to that link
        activeLink.classList.add('active');
        
        // If inside a treeview, add "menu-open" to its parent .has-treeview
        const treeviewParent = activeLink.closest('.has-treeview');
        if (treeviewParent) {
            treeviewParent.classList.add('menu-open');
        }
    }
}

// Hook it on DOMContentLoaded and livewire:navigated
document.addEventListener('DOMContentLoaded', setActiveMenu);
document.addEventListener('livewire:navigated', setActiveMenu);
</script>
