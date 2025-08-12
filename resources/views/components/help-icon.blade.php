<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" title="Trợ giúp">
        <i class="fas fa-lightbulb text-warning"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right help-dropdown">
        <h6 class="dropdown-header">
            <i class="fas fa-lightbulb text-warning"></i>
            @lang('general.quick_help')
        </h6>
        <a href="{{ route('admin.help.index') }}" class="dropdown-item" wire:navigate>
            <i class="fas fa-book mr-2"></i>
            @lang('general.user_guide')
        </a>
        <a href="mailto:support@educore.com?subject=Hỗ trợ EduCore" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i>
            @lang('general.contact_support')
        </a>
        <div class="dropdown-divider"></div>
        <a href="https://facebook.com/educore" target="_blank" class="dropdown-item">
            <i class="fab fa-facebook mr-2"></i>
            Facebook Support
        </a>
        <a href="https://t.me/educore_support" target="_blank" class="dropdown-item">
            <i class="fab fa-telegram mr-2"></i>
            Telegram Support
        </a>
    </div>
</li>
