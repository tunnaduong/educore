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
            <div class="dropdown ms-3">
                <a href="#" class="fw-bold text-white text-decoration-none dropdown-toggle" id="userDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    {{ auth()->user()->name }}
                    <i class="bi bi-person-circle fs-5 ms-2"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
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
                <div class="fw-bold text-uppercase small mb-2">Quản lý đào tạo</div>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a wire:navigate href="{{ route('attendances.overview') }}"
                            class="text-white text-decoration-none d-block {{ $active === 'attendances' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                            <i class="bi bi-calendar-check me-2"></i> Điểm danh
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a wire:navigate href="{{ route('classrooms.index') }}"
                            class="text-white text-decoration-none d-block {{ $active === 'classrooms' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                            <i class="bi bi-diagram-3 me-2"></i> Quản lý lớp học
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a wire:navigate href="{{ route('schedules.index') }}"
                            class="text-white text-decoration-none d-block {{ $active === 'schedules' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                            <i class="bi bi-calendar3 me-2"></i> Lịch học
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a wire:navigate href="{{ route('assignments.overview') }}"
                            class="text-white text-decoration-none d-block {{ $active === 'assignments' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                            <i class="bi bi-journal-text me-2"></i> Giao bài tập
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a wire:navigate href="{{ route('grading.list') }}"
                            class="text-white text-decoration-none d-block {{ $active === 'submissions' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                            <i class="bi bi-journal-check me-2"></i> Chấm bài
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a wire:navigate href="{{ route('quizzes.index') }}"
                            class="text-white text-decoration-none d-block {{ $active === 'quizzes' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                            <i class="bi bi-patch-question me-2"></i> Kiểm tra & Quiz
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a wire:navigate href="{{ route('lessons.index') }}"
                            class="text-white text-decoration-none d-block {{ $active === 'lessons' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                            <i class="bi bi-folder-symlink me-2"></i> Bài học & Tài nguyên
                        </a>
                    </li>
                </ul>
            </div>
            <div class="mb-4">
                <div class="fw-bold text-uppercase small mb-2">Quản lý học viên</div>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a wire:navigate href="{{ route('students.index') }}"
                            class="text-white text-decoration-none d-block {{ $active === 'students' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                            <i class="bi bi-people me-2"></i> Danh sách học viên
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a wire:navigate href="{{ route('reports.index') }}"
                            class="text-white text-decoration-none d-block {{ $active === 'reports' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                            <i class="bi bi-bar-chart me-2"></i> Báo cáo học tập
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a wire:navigate href="{{ route('notifications.index') }}"
                            class="text-white text-decoration-none d-block {{ $active === 'notifications' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                            <i class="bi bi-bell me-2"></i> Thông báo & Nhắc lịch
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a wire:navigate href="{{ route('chat.index') }}"
                            class="text-white text-decoration-none d-block {{ $active === 'chat' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                            <i class="bi bi-chat-dots me-2"></i> Chat & Tương tác
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

{{-- @push('scripts')
    <script>
        function listenLoading() {
            if (window.Livewire) {
                Livewire.on('hideLoading', function() {
                    document.getElementById('global-loading-overlay')?.classList.add('d-none');
                });
                Livewire.on('showLoading', function() {
                    document.getElementById('global-loading-overlay')?.classList.remove('d-none');
                });
            }
        }
        if (window.Livewire) {
            listenLoading();
        } else {
            document.addEventListener('livewire:load', listenLoading);
        }
    </script>
@endpush --}}
