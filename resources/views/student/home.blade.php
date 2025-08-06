<x-layouts.dash-student active="home">
    <div class="container py-4">
        <div class="row g-5">
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('student.schedules') }}" class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-calendar3" style="font-size:2.5rem; color:#ffc107;"></i>
                    </div>
                    <div>@lang('general.schedules')</div>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('student.lessons.index') }}" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-book" style="font-size:2.5rem; color:#0d6efd;"></i>
                    </div>
                    <div>@lang('general.lessons')</div>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('student.assignments.overview') }}" wire:navigate
                    class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-journal-text" style="font-size:2.5rem; color:#fd7e14;"></i>
                    </div>
                    <div>@lang('general.assignments')</div>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('student.quizzes.index') }}" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-clipboard-check-fill" style="font-size:2.5rem; color:#6f42c1;"></i>
                    </div>
                    <div>@lang('general.quizzes')</div>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('student.reports.index') }}" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-bar-chart-fill" style="font-size:2.5rem; color:#20c997;"></i>
                    </div>
                    <div>@lang('general.results')</div>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('student.notifications.index') }}" wire:navigate
                    class="text-decoration-none text-dark">
                    <div class="position-relative d-inline-block">
                        <i class="bi bi-bell-fill" style="font-size:2.5rem; color:#fd5e53;"></i>
                        @if ($unreadNotification > 0)
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                style="font-size:0.8rem;">{{ $unreadNotification }}</span>
                        @endif
                        <div class="mt-2">@lang('general.notifications')</div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('student.chat.index') }}" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2 position-relative d-inline-block">
                        <i class="bi bi-chat-dots-fill" style="font-size:2.5rem; color:#3372a2;"></i>
                        @if ($unreadCount > 0)
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                style="font-size:0.8rem;">{{ $unreadCount }}</span>
                        @endif
                    </div>
                    <div>@lang('general.chat')</div>
                </a>
            </div>
        </div>
    </div>
</x-layouts.dash-student>
