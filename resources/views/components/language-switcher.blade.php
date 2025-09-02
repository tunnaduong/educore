<style>
    .dropdown-menu .dropdown-item.active,
    .dropdown-menu .dropdown-item.active:hover,
    .dropdown-menu .dropdown-item.active:focus {
        background-color: #007bff !important;
        /* giữ màu xanh Bootstrap primary */
        color: #fff !important;
    }

    /* Không làm mờ màu active khi hover chung */
    .dropdown-menu .dropdown-item:hover {
        color: inherit;
    }
</style>

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
        @switch(app()->getLocale())
            @case('vi')
                <img src="https://twemoji.maxcdn.com/v/latest/svg/1f1fb-1f1f3.svg" class="mb-1" width="16" height="16"
                    alt="VN">
                <span class="d-none d-md-inline">@lang('general.vietnamese')</span>
            @break

            @case('en')
                <img src="https://twemoji.maxcdn.com/v/latest/svg/1f1ec-1f1e7.svg" class="mb-1" width="16" height="16"
                    alt="GB">
                <span class="d-none d-md-inline">@lang('general.english')</span>
            @break

            @case('zh')
                <img src="https://twemoji.maxcdn.com/v/latest/svg/1f1e8-1f1f3.svg" class="mb-1" width="16" height="16"
                    alt="CN">
                <span class="d-none d-md-inline">@lang('general.chinese')</span>
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
