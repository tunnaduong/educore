@props(['active' => null, 'title' => __('general.dashboard')])
<div class="wrapper">
    <style>
        /* Đặt lại vị trí cho các thông báo cố định để không bị navbar che */
        .alert.position-fixed.top-0 {
            top: 70px !important;
            z-index: 2000;
        }
    </style>
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
                            <img src="https://twemoji.maxcdn.com/v/latest/svg/1f1fb-1f1f3.svg" class="mb-1" width="16"
                                height="16" alt="VN"> <span class="d-none d-md-inline">@lang('general.vietnamese')</span>
                        @break

                        @case('en')
                            <img src="https://twemoji.maxcdn.com/v/latest/svg/1f1ec-1f1e7.svg" class="mb-1" width="16"
                                height="16" alt="GB"> <span class="d-none d-md-inline">@lang('general.english')</span>
                        @break

                        @case('zh')
                            <img src="https://twemoji.maxcdn.com/v/latest/svg/1f1e8-1f1f3.svg" class="mb-1" width="16"
                                height="16" alt="CN"> <span class="d-none d-md-inline">@lang('general.chinese')</span>
                        @break

                        @default
                            <span class="d-none d-md-inline">@lang('general.language')</span>
                    @endswitch
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ route('lang.switch', 'vi') }}"
                        class="dropdown-item {{ app()->getLocale() == 'vi' ? 'active' : '' }}">
                        <img src="https://twemoji.maxcdn.com/v/latest/svg/1f1fb-1f1f3.svg" class="mb-1" width="16"
                            height="16" alt="VN">
                        @lang('general.vietnamese')
                        @if (app()->getLocale() == 'vi')
                            <i class="fas fa-check ml-2 text-white"></i>
                        @endif
                    </a>
                    <a href="{{ route('lang.switch', 'en') }}"
                        class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                        <img src="https://twemoji.maxcdn.com/v/latest/svg/1f1ec-1f1e7.svg" class="mb-1" width="16"
                            height="16" alt="GB">
                        @lang('general.english')
                        @if (app()->getLocale() == 'en')
                            <i class="fas fa-check ml-2 text-white"></i>
                        @endif
                    </a>
                    <a href="{{ route('lang.switch', 'zh') }}"
                        class="dropdown-item {{ app()->getLocale() == 'zh' ? 'active' : '' }}">
                        <img src="https://twemoji.maxcdn.com/v/latest/svg/1f1e8-1f1f3.svg" class="mb-1" width="16"
                            height="16" alt="CN">
                        @lang('general.chinese')
                        @if (app()->getLocale() == 'zh')
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
        <strong>{{ __('views.copyright') }}</strong>
    </footer>
</div>
<!-- ./wrapper -->

@livewireScripts
