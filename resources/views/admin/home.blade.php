<x-layouts.dash-admin active="home" title="@lang('general.dashboard')">
    @include('components.language')
    <div class="row">
        <div class="col-12">
            <div class="row">
                <!-- Quản lý người dùng & phân quyền -->
                <div class="col-6 col-md-3 text-center mb-4">
                    <a href="{{ route('users.index') }}" wire:navigate class="text-decoration-none text-dark">
                        <div class="mb-2">
                            <i class="fas fa-users" style="font-size:2.5rem; color:#0d6efd;"></i>
                        </div>
                        <div>@lang('general.manage_users')</div>
                    </a>
                </div>
                <!-- Quản lý lớp học -->
                <div class="col-6 col-md-3 text-center mb-4">
                    <a href="{{ route('classrooms.index') }}" wire:navigate class="text-decoration-none text-dark">
                        <div class="mb-2">
                            <i class="fas fa-graduation-cap" style="font-size:2.5rem; color:#fd7e14;"></i>
                        </div>
                        <div>@lang('general.manage_classrooms')</div>
                    </a>
                </div>
                <!-- Lịch học -->
                <div class="col-6 col-md-3 text-center mb-4">
                    <a href="{{ route('schedules.index') }}" wire:navigate class="text-decoration-none text-dark">
                        <div class="mb-2">
                            <i class="fas fa-calendar-alt" style="font-size:2.5rem; color:#6f42c1;"></i>
                        </div>
                        <div>@lang('general.schedules')</div>
                    </a>
                </div>
                <!-- Quản lý học viên -->
                <div class="col-6 col-md-3 text-center mb-4">
                    <a href="{{ route('students.index') }}" wire:navigate class="text-decoration-none text-dark">
                        <div class="mb-2">
                            <i class="fas fa-user-graduate" style="font-size:2.5rem; color:#20c997;"></i>
                        </div>
                        <div>@lang('general.manage_students')</div>
                    </a>
                </div>
                <!-- Điểm danh -->
                <div class="col-6 col-md-3 text-center mb-4">
                    <a href="{{ route('attendances.overview') }}" wire:navigate class="text-decoration-none text-dark">
                        <div class="mb-2">
                            <i class="fas fa-clipboard-check" style="font-size:2.5rem; color:#ffc107;"></i>
                        </div>
                        <div>@lang('general.attendance')</div>
                    </a>
                </div>
                <!-- Giao bài tập -->
                <div class="col-6 col-md-3 text-center mb-4">
                    <a href="{{ route('assignments.overview') }}" wire:navigate class="text-decoration-none text-dark">
                        <div class="mb-2">
                            <i class="fas fa-tasks" style="font-size:2.5rem; color:#fd5e53;"></i>
                        </div>
                        <div>@lang('general.assign_homework')</div>
                    </a>
                </div>
                <!-- Chấm bài -->
                <div class="col-6 col-md-3 text-center mb-4">
                    <a href="{{ route('grading.list') }}" wire:navigate class="text-decoration-none text-dark">
                        <div class="mb-2">
                            <i class="fas fa-check-circle" style="font-size:2.5rem; color:#6f42c1;"></i>
                        </div>
                        <div>@lang('general.grading')</div>
                    </a>
                </div>
                <!-- Kiểm tra & Quiz -->
                <div class="col-6 col-md-3 text-center mb-4">
                    <a href="{{ route('quizzes.index') }}" wire:navigate class="text-decoration-none text-dark">
                        <div class="mb-2">
                            <i class="fas fa-question-circle" style="font-size:2.5rem; color:#b23cfd;"></i>
                        </div>
                        <div>@lang('general.quizzes')</div>
                    </a>
                </div>
                <!-- Xem lại bài học & tài nguyên -->
                <div class="col-6 col-md-3 text-center mb-4">
                    <a href="{{ route('lessons.index') }}" wire:navigate class="text-decoration-none text-dark">
                        <div class="mb-2">
                            <i class="fas fa-book" style="font-size:2.5rem; color:#28a745;"></i>
                        </div>
                        <div>@lang('general.lessons')</div>
                    </a>
                </div>
                <!-- Thống kê - báo cáo -->
                <div class="col-6 col-md-3 text-center mb-4">
                    <a href="{{ route('reports.index') }}" wire:navigate class="text-decoration-none text-dark">
                        <div class="mb-2">
                            <i class="fas fa-chart-bar" style="font-size:2.5rem; color:#ff9800;"></i>
                        </div>
                        <div>@lang('general.statistics_reports')</div>
                    </a>
                </div>
                <!-- Thống kê thu chi -->
                <div class="col-6 col-md-3 text-center mb-4">
                    <a href="{{ route('admin.finance.index') }}" wire:navigate class="text-decoration-none text-dark">
                        <div class="mb-2">
                            <i class="fas fa-coins" style="font-size:2.5rem; color:#ffc107;"></i>
                        </div>
                        <div>Thống kê thu chi</div>
                    </a>
                </div>
                <!-- Quản lý đánh giá -->
                <div class="col-6 col-md-3 text-center mb-4">
                    <a href="{{ route('evaluation-management') }}" wire:navigate class="text-decoration-none text-dark">
                        <div class="mb-2">
                            <i class="fas fa-star" style="font-size:2.5rem; color:#e91e63;"></i>
                        </div>
                        <div>Quản lý đánh giá</div>
                    </a>
                </div>
                <!-- Thông báo & nhắc lịch -->
                <div class="col-6 col-md-3 text-center mb-4">
                    <a href="{{ route('notifications.index') }}" wire:navigate class="text-decoration-none text-dark">
                        <div class="mb-2 position-relative d-inline-block">
                            <i class="fas fa-bell" style="font-size:2.5rem; color:#f59e42;"></i>
                        </div>
                        <div>@lang('general.notifications_reminders')</div>
                    </a>
                </div>
                <!-- Chat -->
                <div class="col-6 col-md-3 text-center mb-4">
                    <a href="{{ route('chat.index') }}" wire:navigate class="text-decoration-none text-dark">
                        <div class="mb-2">
                            <i class="fas fa-comments" style="font-size:2.5rem; color:#17a2b8;"></i>
                        </div>
                        <div>@lang('general.chat')</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
