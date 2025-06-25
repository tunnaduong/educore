<x-layouts.dash active="home">
    <div class="container py-4">
        <div class="row g-4">
            <!-- Quản lý người dùng & phân quyền -->
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('users.index') }}" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-people-fill" style="font-size:2.5rem; color:#0d6efd;"></i>
                    </div>
                    <div>Quản lý người dùng</div>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('schedules.index') }}" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-calendar3" style="font-size:2.5rem; color:#6f42c1;"></i>
                    </div>
                    <div>Lịch học</div>
                </a>
            </div>
            <!-- Quản lý lớp học -->
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('classrooms.index') }}" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-diagram-3-fill" style="font-size:2.5rem; color:#fd7e14;"></i>
                    </div>
                    <div>Quản lý lớp học</div>
                </a>
            </div>
            <!-- Quản lý học viên -->
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('students.index') }}" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-person-lines-fill" style="font-size:2.5rem; color:#20c997;"></i>
                    </div>
                    <div>Quản lý học viên</div>
                </a>
            </div>
            <!-- Điểm danh -->
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('attendances.overview') }}" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-clipboard-check-fill" style="font-size:2.5rem; color:#ffc107;"></i>
                    </div>
                    <div>Điểm danh</div>
                </a>
            </div>
            <!-- Giao bài tập -->
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('assignments.overview') }}" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-journal-text" style="font-size:2.5rem; color:#fd5e53;"></i>
                    </div>
                    <div>Giao bài tập</div>
                </a>
            </div>
            <!-- Chấm bài -->
            <div class="col-6 col-md-3 text-center">
                <a href="#" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-journal-check" style="font-size:2.5rem; color:#6f42c1;"></i>
                    </div>
                    <div>Chấm bài</div>
                </a>
            </div>
            <!-- Kiểm tra & Quiz -->
            <div class="col-6 col-md-3 text-center">
                <a href="#" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-patch-question-fill" style="font-size:2.5rem; color:#b23cfd;"></i>
                    </div>
                    <div>Kiểm tra & Quiz</div>
                </a>
            </div>
            <!-- Xem lại bài học & tài nguyên -->
            <div class="col-6 col-md-3 text-center">
                <a href="#" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-folder-symlink-fill" style="font-size:2.5rem; color:#28a745;"></i>
                    </div>
                    <div>Bài học & Tài nguyên</div>
                </a>
            </div>
            <!-- Thống kê - báo cáo -->
            <div class="col-6 col-md-3 text-center">
                <a href="#" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-bar-chart-fill" style="font-size:2.5rem; color:#ff9800;"></i>
                    </div>
                    <div>Thống kê - Báo cáo</div>
                </a>
            </div>
            <!-- Thông báo & nhắc lịch -->
            <div class="col-6 col-md-3 text-center">
                <a href="#" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2 position-relative d-inline-block">
                        <i class="bi bi-bell-fill" style="font-size:2.5rem; color:#f59e42;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            style="font-size:0.8rem;">3</span>
                    </div>
                    <div>Thông báo & Nhắc lịch</div>
                </a>
            </div>
            <!-- Chat & tương tác -->
            <div class="col-6 col-md-3 text-center">
                <a href="#" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-chat-dots-fill" style="font-size:2.5rem; color:#0dcaf0;"></i>
                    </div>
                    <div>Chat & Tương tác</div>
                </a>
            </div>
        </div>
    </div>
</x-layouts.dash>
