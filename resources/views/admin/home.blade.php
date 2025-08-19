<x-layouts.dash-admin title="Dashboard" active="home">
    @include('components.language')

    <!-- Stats Cards Row -->
    <div class="row">
        <!-- Unread Messages -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $unreadCount }}</h3>
                    <p>Tin nhắn chưa đọc</p>
                </div>
                <div class="icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <a href="{{ route('chat.index') }}" class="small-box-footer">
                    Xem thêm <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Unread Notifications -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $unreadNotification }}</h3>
                    <p>Thông báo chưa đọc</p>
                </div>
                <div class="icon">
                    <i class="fas fa-bell"></i>
                </div>
                <a href="{{ route('notifications.index') }}" class="small-box-footer">
                    Xem thêm <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Total Students -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ \App\Models\User::where('role', 'student')->count() }}</h3>
                    <p>Tổng số học sinh</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('students.index') }}" class="small-box-footer">
                    Xem thêm <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Total Teachers -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ \App\Models\User::where('role', 'teacher')->count() }}</h3>
                    <p>Tổng số giáo viên</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <a href="{{ route('users.index') }}" class="small-box-footer">
                    Xem thêm <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions Row -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tachometer-alt mr-1"></i>
                        Thao tác nhanh
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Quản lý người dùng & phân quyền -->
                        <div class="col-6 col-md-3 text-center mb-4">
                            <a href="{{ route('users.index') }}" class="text-decoration-none text-dark">
                                <div class="mb-2">
                                    <i class="fas fa-users" style="font-size:2.5rem; color:#0d6efd;"></i>
                                </div>
                                <div>@lang('general.manage_users')</div>
                            </a>
                        </div>
                        <!-- Quản lý lớp học -->
                        <div class="col-6 col-md-3 text-center mb-4">
                            <a href="{{ route('classrooms.index') }}" class="text-decoration-none text-dark">
                                <div class="mb-2">
                                    <i class="fas fa-graduation-cap" style="font-size:2.5rem; color:#fd7e14;"></i>
                                </div>
                                <div>@lang('general.manage_classrooms')</div>
                            </a>
                        </div>
                        <!-- Lịch học -->
                        <div class="col-6 col-md-3 text-center mb-4">
                            <a href="{{ route('schedules.index') }}" class="text-decoration-none text-dark">
                                <div class="mb-2">
                                    <i class="fas fa-calendar-alt" style="font-size:2.5rem; color:#6f42c1;"></i>
                                </div>
                                <div>@lang('general.schedules')</div>
                            </a>
                        </div>
                        <!-- Quản lý học viên -->
                        <div class="col-6 col-md-3 text-center mb-4">
                            <a href="{{ route('students.index') }}" class="text-decoration-none text-dark">
                                <div class="mb-2">
                                    <i class="fas fa-user-graduate" style="font-size:2.5rem; color:#20c997;"></i>
                                </div>
                                <div>@lang('general.manage_students')</div>
                            </a>
                        </div>
                        <!-- Điểm danh -->
                        <div class="col-6 col-md-3 text-center mb-4">
                            <a href="{{ route('attendances.overview') }}" class="text-decoration-none text-dark">
                                <div class="mb-2">
                                    <i class="fas fa-clipboard-check" style="font-size:2.5rem; color:#ffc107;"></i>
                                </div>
                                <div>@lang('general.attendance')</div>
                            </a>
                        </div>
                        <!-- Giao bài tập -->
                        <div class="col-6 col-md-3 text-center mb-4">
                            <a href="{{ route('assignments.overview') }}" class="text-decoration-none text-dark">
                                <div class="mb-2">
                                    <i class="fas fa-tasks" style="font-size:2.5rem; color:#fd5e53;"></i>
                                </div>
                                <div>@lang('general.assign_homework')</div>
                            </a>
                        </div>
                        <!-- Chấm bài -->
                        <div class="col-6 col-md-3 text-center mb-4">
                            <a href="{{ route('grading.list') }}" class="text-decoration-none text-dark">
                                <div class="mb-2">
                                    <i class="fas fa-check-circle" style="font-size:2.5rem; color:#6f42c1;"></i>
                                </div>
                                <div>@lang('general.grading')</div>
                            </a>
                        </div>
                        <!-- Kiểm tra & Quiz -->
                        <div class="col-6 col-md-3 text-center mb-4">
                            <a href="{{ route('quizzes.index') }}" class="text-decoration-none text-dark">
                                <div class="mb-2">
                                    <i class="fas fa-question-circle" style="font-size:2.5rem; color:#b23cfd;"></i>
                                </div>
                                <div>@lang('general.quizzes')</div>
                            </a>
                        </div>
                        <!-- Xem lại bài học & tài nguyên -->
                        <div class="col-6 col-md-3 text-center mb-4">
                            <a href="{{ route('lessons.index') }}" class="text-decoration-none text-dark">
                                <div class="mb-2">
                                    <i class="fas fa-book" style="font-size:2.5rem; color:#28a745;"></i>
                                </div>
                                <div>@lang('general.lessons')</div>
                            </a>
                        </div>
                        <!-- Thống kê - báo cáo -->
                        <div class="col-6 col-md-3 text-center mb-4">
                            <a href="{{ route('reports.index') }}" class="text-decoration-none text-dark">
                                <div class="mb-2">
                                    <i class="fas fa-chart-bar" style="font-size:2.5rem; color:#ff9800;"></i>
                                </div>
                                <div>@lang('general.statistics_reports')</div>
                            </a>
                        </div>
                        <!-- Thống kê thu chi -->
                        <div class="col-6 col-md-3 text-center mb-4">
                            <a href="{{ route('admin.finance.index') }}" class="text-decoration-none text-dark">
                                <div class="mb-2">
                                    <i class="fas fa-coins" style="font-size:2.5rem; color:#ffc107;"></i>
                                </div>
                                <div>Thống kê thu chi</div>
                            </a>
                        </div>
                        <!-- Quản lý đánh giá -->
                        <div class="col-6 col-md-3 text-center mb-4">
                            <a href="{{ route('evaluation-management') }}" class="text-decoration-none text-dark">
                                <div class="mb-2">
                                    <i class="fas fa-star" style="font-size:2.5rem; color:#e91e63;"></i>
                                </div>
                                <div>Quản lý đánh giá</div>
                            </a>
                        </div>
                        <!-- Thông báo & nhắc lịch -->
                        <div class="col-6 col-md-3 text-center mb-4">
                            <a href="{{ route('notifications.index') }}" class="text-decoration-none text-dark">
                                <div class="mb-2 position-relative d-inline-block">
                                    <i class="fas fa-bell" style="font-size:2.5rem; color:#f59e42;"></i>
                                </div>
                                <div>@lang('general.notifications_reminders')</div>
                            </a>
                        </div>
                        <!-- Chat -->
                        <div class="col-6 col-md-3 text-center mb-4">
                            <a href="{{ route('chat.index') }}" class="text-decoration-none text-dark">
                                <div class="mb-2">
                                    <i class="fas fa-comments" style="font-size:2.5rem; color:#17a2b8;"></i>
                                </div>
                                <div>@lang('general.chat')</div>
                            </a>
                        </div>
                        <!-- AI -->
                        <div class="col-6 col-md-3 text-center mb-4">
                            <a href="{{ route('ai.index') }}" class="text-decoration-none text-dark">
                                <div class="mb-2">
                                    <i class="fas fa-robot" style="font-size:2.5rem; color:#0d6efd;"></i>
                                </div>
                                <div>@lang('general.ai_assistant')</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Thống kê điểm danh
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="attendanceChart"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Hoạt động gần đây
                    </h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <!-- Timeline items would go here -->
                        <div class="time-label">
                            <span class="bg-red">Hôm nay</span>
                        </div>
                        <div>
                            <i class="fas fa-envelope bg-blue"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> 2 phút trước</span>
                                <h3 class="timeline-header">Tin nhắn mới từ giáo viên</h3>
                                <div class="timeline-body">
                                    Có {{ $unreadCount }} tin nhắn chưa đọc cần xem xét.
                                </div>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-bell bg-yellow"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> 5 phút trước</span>
                                <h3 class="timeline-header">Thông báo mới</h3>
                                <div class="timeline-body">
                                    Có {{ $unreadNotification }} thông báo chưa đọc.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Chart.js for attendance statistics
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('attendanceChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Có mặt', 'Vắng mặt', 'Trễ'],
                        datasets: [{
                            data: [75, 15, 10],
                            backgroundColor: ['#28a745', '#dc3545', '#ffc107'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
</x-layouts.dash-admin>
