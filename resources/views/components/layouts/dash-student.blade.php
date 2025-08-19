@props(['active' => null, 'title' => __('general.dashboard')])
<div class="wrapper">
    @include('components.evaluation')
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" data-enable-remember="true" role="button"><i
                        class="bi bi-list"></i></a>
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
                    <a href="{{ route('lang.switch', 'vi') }}" class="dropdown-item"><img
                            src="https://twemoji.maxcdn.com/v/latest/svg/1f1fb-1f1f3.svg" width="16" height="16"
                            alt="VN"> @lang('general.vietnamese')</a>
                    <a href="{{ route('lang.switch', 'en') }}" class="dropdown-item"><img
                            src="https://twemoji.maxcdn.com/v/latest/svg/1f1ec-1f1e7.svg" width="16" height="16"
                            alt="GB"> @lang('general.english')</a>
                    <a href="{{ route('lang.switch', 'zh') }}" class="dropdown-item"><img
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

    <x-sidebar-student-config :active="$active" />

    <div class="content-wrapper">
        <section class="content pt-3">
            <div class="container-fluid">
                {{ $slot }}
            </div>
        </section>
    </div>

    <footer class="main-footer text-center">
        <strong>{{ __('views.copyright') }}</strong>
    </footer>
</div>
