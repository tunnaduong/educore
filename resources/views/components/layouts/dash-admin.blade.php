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
            <x-language-switcher />
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
