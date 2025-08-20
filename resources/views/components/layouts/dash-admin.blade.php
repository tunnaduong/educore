@props(['active' => null, 'title' => __('general.dashboard')])
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" data-enable-remember="true" href="#" role="button"><i
                        class="fas fa-bars"></i></a>
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
                    <a href="{{ route('lang.switch', 'vi') }}" class="dropdown-item {{ app()->getLocale() == 'vi' ? 'active' : '' }}">
                        <img src="https://twemoji.maxcdn.com/v/latest/svg/1f1fb-1f1f3.svg" width="16" height="16" alt="VN"> 
                        @lang('general.vietnamese')
                        @if(app()->getLocale() == 'vi')
                            <i class="fas fa-check ml-2 text-white"></i>
                        @endif
                    </a>
                    <a href="{{ route('lang.switch', 'en') }}" class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                        <img src="https://twemoji.maxcdn.com/v/latest/svg/1f1ec-1f1e7.svg" width="16" height="16" alt="GB"> 
                        @lang('general.english')
                        @if(app()->getLocale() == 'en')
                            <i class="fas fa-check ml-2 text-white"></i>
                        @endif
                    </a>
                    <a href="{{ route('lang.switch', 'zh') }}" class="dropdown-item {{ app()->getLocale() == 'zh' ? 'active' : '' }}">
                        <img src="https://twemoji.maxcdn.com/v/latest/svg/1f1e8-1f1f3.svg" width="16" height="16" alt="CN"> 
                        @lang('general.chinese')
                        @if(app()->getLocale() == 'zh')
                            <i class="fas fa-check ml-2 text-white"></i>
                        @endif
                    </a>
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
    <x-sidebar-config :active="$active" />

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
