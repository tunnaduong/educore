{{-- Navbar Upper --}}
@props(['active' => null])

<div class="min-vh-100 d-flex flex-column">
    <div class="bg-[#23417e] text-white p-3 d-flex justify-content-between align-items-center"
        style="background-color: #23417e">
        <a wire:navigate href="/" class="text-decoration-none">
            <div class="d-flex align-items-center">
                <img src="/educore-logo.png" alt="Logo" style="width: 50px; height: 50px;" class="me-2">
                <span class="fs-4 fw-bold text-white">Edu</span>
                <span class="fs-4 fw-bold text-warning">Core</span>
            </div>
        </a>
        <div class="d-flex align-items-center">
            <livewire:components.notification-bell />
            <livewire:components.logout />
        </div>
    </div>
    <div class="d-flex flex-grow-1" style="min-height: 0;">
        <!-- Sidebar -->
        <div class="bg-dark text-white p-4" style="min-width: 260px;width:100%;max-width: 290px;">
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a wire:navigate href="{{ route('dashboard') }}"
                        class="text-white text-decoration-none d-block {{ $active === 'home' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                        <i class="bi bi-house me-2"></i> Trang chủ
                    </a>
                </li>
            </ul>
            <div class="my-4">
                <div class="fw-bold text-uppercase small mb-2">Quản lý lớp học</div>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a wire:navigate href="{{ route('teacher.my-class.index') }}"
                            class="text-white text-decoration-none d-block {{ $active === 'my-class' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                            <i class="bi bi-diagram-3 me-2"></i> Lớp học của tôi
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a wire:navigate href="{{ route('teacher.lessons.index') }}"
                            class="text-white text-decoration-none d-block {{ $active === 'schedules' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                            <i class="bi bi-calendar3 me-2"></i> Lịch giảng dạy
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a wire:navigate href="{{ route('teacher.grading.index') }}"
                            class="text-white text-decoration-none d-block {{ $active === 'attendances' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                            <i class="bi bi-calendar-check me-2"></i> Điểm danh
                        </a>
                    </li>
                </ul>
            </div>
            <div class="mb-4">
                <div class="fw-bold text-uppercase small mb-2">Nội dung giảng dạy</div>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a wire:navigate href="{{ route('teacher.lessons.index') }}"
                            class="text-white text-decoration-none d-block {{ $active === 'lessons' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                            <i class="bi bi-book me-2"></i> Bài học
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a wire:navigate href="{{ route('teacher.assignments.index') }}"
                            class="text-white text-decoration-none d-block {{ $active === 'assignments' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                            <i class="bi bi-journal-text me-2"></i> Bài tập
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a wire:navigate href="{{ route('teacher.quizzes.index') }}"
                            class="text-white text-decoration-none d-block {{ $active === 'quizzes' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                            <i class="bi bi-patch-question me-2"></i> Kiểm tra & Quiz
                        </a>
                    </li>
                </ul>
            </div>
            <div class="mb-4">
                <div class="fw-bold text-uppercase small mb-2">Đánh giá & Báo cáo</div>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a wire:navigate href="{{ route('teacher.grading.index') }}"
                            class="text-white text-decoration-none d-block {{ $active === 'grading' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                            <i class="bi bi-journal-check me-2"></i> Chấm bài
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a wire:navigate href="{{ route('teacher.assignments.index') }}"
                            class="text-white text-decoration-none d-block {{ $active === 'reports' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                            <i class="bi bi-bar-chart me-2"></i> Báo cáo lớp học
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a wire:navigate href="{{ route('teacher.notifications.index') }}"
                            class="text-white text-decoration-none d-block {{ $active === 'notifications' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                            <i class="bi bi-bell me-2"></i> Thông báo
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Main Content -->
        <div class="flex-grow-1 p-4 flex flex-col" style="background: #f8fafc;">
            <div>
                {{ $slot }}
            </div>
            <footer class="text-center mt-4 text-muted small">
                © 2025 Trung tâm Hanxian Kim Bảng Hà Nam — Powered by EduCore
            </footer>
        </div>
    </div>
    <!-- Loading overlay -->
    <div id="global-loading-overlay" wire:loading.delay.long x-data
        x-on:hide-loading.window="document.getElementById('global-loading-overlay')?.classList.add('d-none')"
        x-on:show-loading.window="document.getElementById('global-loading-overlay')?.classList.remove('d-none')"
        class="position-fixed top-0 start-0 w-100 h-100" style="z-index: 2000; background: rgba(0,0,0,0.3);">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
                <span class="visually-hidden">Đang tải...</span>
            </div>
        </div>
    </div>
</div>
