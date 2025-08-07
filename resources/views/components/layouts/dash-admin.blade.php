@props(['active' => null, 'title' => __('general.dashboard')])
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
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
            <li class="nav-item">
                <livewire:components.notification-bell />
            </li>
            <li class="nav-item">
                <livewire:components.logout />
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="/" class="brand-link text-center" wire:navigate>
            <span class="brand-text font-weight-light">@lang('general.app_name')</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ $active === 'home' ? 'active' : '' }}"
                            wire:navigate>
                            <i class="nav-icon fas fa-home"></i>
                            <p>@lang('general.dashboard')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('attendances.overview') }}"
                            class="nav-link {{ $active === 'attendances' ? 'active' : '' }}" wire:navigate>
                            <i class="nav-icon fas fa-calendar-check"></i>
                            <p>@lang('general.attendance')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('classrooms.index') }}"
                            class="nav-link {{ $active === 'classrooms' ? 'active' : '' }}" wire:navigate>
                            <i class="nav-icon fas fa-graduation-cap"></i>
                            <p>@lang('general.classrooms')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('schedules.index') }}"
                            class="nav-link {{ $active === 'schedules' ? 'active' : '' }}" wire:navigate>
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>@lang('general.schedules')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('assignments.overview') }}"
                            class="nav-link {{ $active === 'assignments' ? 'active' : '' }}" wire:navigate>
                            <i class="nav-icon fas fa-tasks"></i>
                            <p>@lang('general.assignments')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('grading.list') }}"
                            class="nav-link {{ $active === 'grading' ? 'active' : '' }}" wire:navigate>
                            <i class="nav-icon fas fa-check-circle"></i>
                            <p>@lang('general.grading')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('quizzes.index') }}"
                            class="nav-link {{ $active === 'quizzes' ? 'active' : '' }}" wire:navigate>
                            <i class="nav-icon fas fa-question-circle"></i>
                            <p>@lang('general.quizzes')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('lessons.index') }}"
                            class="nav-link {{ $active === 'lessons' ? 'active' : '' }}" wire:navigate>
                            <i class="nav-icon fas fa-book"></i>
                            <p>@lang('general.lessons')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('students.index') }}"
                            class="nav-link {{ $active === 'students' ? 'active' : '' }}" wire:navigate>
                            <i class="nav-icon fas fa-users"></i>
                            <p>@lang('general.students')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}"
                            class="nav-link {{ $active === 'reports' ? 'active' : '' }}" wire:navigate>
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>@lang('general.reports')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('evaluation-management') }}"
                            class="nav-link {{ $active === 'evaluation-management' ? 'active' : '' }}" wire:navigate>
                            <i class="nav-icon fas fa-star"></i>
                            <p>Quản lý đánh giá</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('notifications.index') }}"
                            class="nav-link {{ $active === 'notifications' ? 'active' : '' }}" wire:navigate>
                            <i class="nav-icon fas fa-bell"></i>
                            <p>@lang('general.notifications')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('chat.index') }}"
                            class="nav-link {{ $active === 'chat' ? 'active' : '' }}" wire:navigate>
                            <i class="nav-icon fas fa-comments"></i>
                            <p>@lang('general.chat')</p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="py-4 container-fluid">
                {{ $slot }}
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer text-center">
        <strong>© 2025 Trung tâm Hanxian Kim Bảng Hà Nam - Powered by EduCore</strong>
    </footer>
</div>
<!-- ./wrapper -->
