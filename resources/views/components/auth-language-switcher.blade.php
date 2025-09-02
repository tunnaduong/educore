<style>
    .language-selector {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
    }

    @media (max-width: 768px) {
        .language-selector {
            top: 10px;
            right: 10px;
        }

        .language-btn {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
        }
    }

    .language-dropdown {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .language-btn {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        color: #333;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .language-btn:hover {
        background: rgba(204, 204, 204, 0.9);
        transform: translateY(-1px);
    }

    .language-btn.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .dropdown-item:hover {
        background-color: rgba(102, 126, 234, 0.1) !important;
        color: #333 !important;
    }

    .dropdown-item {
        transition: all 0.2s ease;
    }

    /* Đảm bảo dropdown đóng khi click outside */
    .dropdown-menu.show {
        display: block !important;
    }

    .dropdown-menu {
        display: none;
    }
</style>

<!-- Language Selector -->
<div class="language-selector">
    <div class="dropdown">
        <button class="btn language-btn dropdown-toggle" type="button" id="languageDropdown" aria-expanded="false">
            @if (app()->getLocale() == 'vi')
                <img src="https://twemoji.maxcdn.com/v/latest/svg/1f1fb-1f1f3.svg" width="16" height="16"
                    alt="VN">
                &nbsp;@lang('general.vietnamese')
            @elseif(app()->getLocale() == 'zh')
                <img src="https://twemoji.maxcdn.com/v/latest/svg/1f1e8-1f1f3.svg" width="16" height="16"
                    alt="CN">
                &nbsp;@lang('general.chinese')
            @else
                <img src="https://twemoji.maxcdn.com/v/latest/svg/1f1ec-1f1e7.svg" width="16" height="16"
                    alt="GB">
                &nbsp;@lang('general.english')
            @endif
        </button>
        <ul class="dropdown-menu language-dropdown" id="languageDropdownMenu" aria-labelledby="languageDropdown">
            <li>
                <a class="dropdown-item d-flex align-items-center" href="{{ route('lang.switch', 'vi') }}">
                    <img src="https://twemoji.maxcdn.com/v/latest/svg/1f1fb-1f1f3.svg" width="16" height="16"
                        alt="VN">
                    &nbsp;@lang('general.vietnamese')
                </a>
            </li>
            <li>
                <a class="dropdown-item d-flex align-items-center" href="{{ route('lang.switch', 'zh') }}">
                    <img src="https://twemoji.maxcdn.com/v/latest/svg/1f1e8-1f1f3.svg" width="16" height="16"
                        alt="CN">
                    &nbsp;@lang('general.chinese')
                </a>
            </li>
            <li>
                <a class="dropdown-item d-flex align-items-center" href="{{ route('lang.switch', 'en') }}">
                    <img src="https://twemoji.maxcdn.com/v/latest/svg/1f1ec-1f1e7.svg" width="16" height="16"
                        alt="GB">
                    &nbsp;@lang('general.english')
                </a>
            </li>
        </ul>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý đóng dropdown khi click outside
        const dropdown = document.getElementById('languageDropdown');
        const dropdownMenu = document.getElementById('languageDropdownMenu');

        // Đóng dropdown khi click outside
        document.addEventListener('click', function(event) {
            const isClickInside = dropdown.contains(event.target) || dropdownMenu.contains(event
                .target);

            if (!isClickInside && dropdownMenu.classList.contains('show')) {
                dropdownMenu.classList.remove('show');
            }
        });

        // Toggle dropdown khi click vào button
        dropdown.addEventListener('click', function(event) {
            event.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });
    });
</script>
