<x-layouts.dash-teacher active="home">
    @include('components.language')
    <div class="container py-4">
        <div class="row g-4 gy-5">
            <!-- Lớp học của tôi -->
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('teacher.my-class.index') }}" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-diagram-3-fill" style="font-size:2.5rem; color:#0d6efd;"></i>
                    </div>
                    <div>@lang('general.my_class')</div>
                </a>
            </div>
            <!-- Lịch giảng dạy -->
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('teacher.schedules.index') }}" class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-calendar3" style="font-size:2.5rem; color:#fd7e14;"></i>
                    </div>
                    <div>@lang('general.teaching_schedule')</div>
                </a>
            </div>
            <!-- Điểm danh -->
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('teacher.attendance.overview') }}" wire:navigate
                    class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-calendar-check" style="font-size:2.5rem; color:#6f42c1;"></i>
                    </div>
                    <div>@lang('general.attendance')</div>
                </a>
            </div>
            <!-- Bài học -->
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('teacher.lessons.index') }}" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-book" style="font-size:2.5rem; color:#20c997;"></i>
                    </div>
                    <div>@lang('general.lessons')</div>
                </a>
            </div>
            <!-- Bài tập -->
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('teacher.assignments.index') }}" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-journal-text" style="font-size:2.5rem; color:#ffc107;"></i>
                    </div>
                    <div>@lang('general.assignments')</div>
                </a>
            </div>
            <!-- Kiểm tra & Quiz -->
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('teacher.quizzes.index') }}" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-patch-question-fill" style="font-size:2.5rem; color:#fd5e53;"></i>
                    </div>
                    <div>@lang('general.quizzes')</div>
                </a>
            </div>
            <!-- Chấm bài -->
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('teacher.grading.index') }}" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-journal-check" style="font-size:2.5rem; color:#6f42c1;"></i>
                    </div>
                    <div>@lang('general.grading')</div>
                </a>
            </div>
            <!-- Báo cáo lớp học -->
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('teacher.reports.index') }}" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-bar-chart-fill" style="font-size:2.5rem; color:#28a745;"></i>
                    </div>
                    <div>@lang('general.class_reports')</div>
                </a>
            </div>
            <!-- Thông báo -->
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('teacher.notifications.index') }}" wire:navigate
                    class="text-decoration-none text-dark">
                    <div class="mb-2 position-relative d-inline-block">
                        <i class="bi bi-bell-fill" style="font-size:2.5rem; color:#f59e42;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            style="font-size:0.8rem;">2</span>
                    </div>
                    <div>@lang('general.notifications')</div>
                </a>
            </div>
            <!-- Tin nhắn -->
            <div class="col-6 col-md-3 text-center">
                <a href="#" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2 position-relative d-inline-block">
                        <i class="bi bi-chat-dots-fill" style="font-size:2.5rem; color:#30c495;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            style="font-size:0.8rem;">2</span>
                    </div>
                    <div>@lang('general.chat')</div>
                </a>
            </div>
            <!-- Báo cáo đánh giá SV -->
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('teacher.evaluations.report') }}" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="fas fa-star" style="font-size:2.5rem; color:#e91e63;"></i>
                    </div>
                    <div>Báo cáo đánh giá</div>
                </a>
            </div>
        </div>
    </div>
</x-layouts.dash-teacher>
