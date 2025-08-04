@props(['active' => null, 'title' => __('general.dashboard')])
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="bi bi-list"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-bs-toggle="dropdown" href="#">
                    @lang('general.language')
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a href="{{ route('lang.switch','vi') }}" class="dropdown-item">🇻🇳 @lang('general.vietnamese')</a></li>
                    <li><a href="{{ route('lang.switch','en') }}" class="dropdown-item">🇬🇧 @lang('general.english')</a></li>
                    <li><a href="{{ route('lang.switch','zh') }}" class="dropdown-item">🇨🇳 @lang('general.chinese')</a></li>
                </ul>
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
        <a href="/" class="brand-link text-center">
            <span class="brand-text font-weight-light">@lang('general.app_name')</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ $active === 'home' ? 'active' : '' }}">
                            <i class="nav-icon bi bi-house"></i>
                            <p>@lang('general.dashboard')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('attendances.overview') }}" class="nav-link {{ $active === 'attendances' ? 'active' : '' }}">
                            <i class="nav-icon bi bi-calendar-check"></i>
                            <p>@lang('general.attendance')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('classrooms.index') }}" class="nav-link {{ $active === 'classrooms' ? 'active' : '' }}">
                            <i class="nav-icon bi bi-diagram-3"></i>
                            <p>@lang('general.classrooms')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('schedules.index') }}" class="nav-link {{ $active === 'schedules' ? 'active' : '' }}">
                            <i class="nav-icon bi bi-calendar3"></i>
                            <p>@lang('general.schedules')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('assignments.overview') }}" class="nav-link {{ $active === 'assignments' ? 'active' : '' }}">
                            <i class="nav-icon bi bi-journal-text"></i>
                            <p>@lang('general.assignments')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('grading.list') }}" class="nav-link {{ $active === 'grading' ? 'active' : '' }}">
                            <i class="nav-icon bi bi-journal-check"></i>
                            <p>@lang('general.grading')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('quizzes.index') }}" class="nav-link {{ $active === 'quizzes' ? 'active' : '' }}">
                            <i class="nav-icon bi bi-patch-question"></i>
                            <p>@lang('general.quizzes')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('lessons.index') }}" class="nav-link {{ $active === 'lessons' ? 'active' : '' }}">
                            <i class="nav-icon bi bi-folder-symlink"></i>
                            <p>@lang('general.lessons')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('students.index') }}" class="nav-link {{ $active === 'students' ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people"></i>
                            <p>@lang('general.students')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}" class="nav-link {{ $active === 'reports' ? 'active' : '' }}">
                            <i class="nav-icon bi bi-bar-chart"></i>
                            <p>@lang('general.reports')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('notifications.index') }}" class="nav-link {{ $active === 'notifications' ? 'active' : '' }}">
                            <i class="nav-icon bi bi-bell"></i>
                            <p>@lang('general.notifications')</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('chat.index') }}" class="nav-link {{ $active === 'chat' ? 'active' : '' }}">
                            <i class="nav-icon bi bi-chat-dots"></i>
                            <p>@lang('general.chat')</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content pt-3">
            <div class="container-fluid">
                {{ $slot }}
            </div>
        </section>
    </div>

    <!-- Main Footer -->
    <footer class="main-footer text-center">
        <strong>© 2025 EduCore</strong>
    </footer>
</div>
