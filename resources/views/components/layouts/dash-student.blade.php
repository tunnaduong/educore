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
        <div class="dropdown">
            <i class="bi bi-bell fs-5 me-3"></i>
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
                <li class="nav-item mb-2">
                    <a wire:navigate href="{{ route('student.lessons.index') }}"
                        class="text-white text-decoration-none d-block {{ $active === 'courses' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                        <i class="bi bi-book me-2"></i> Bài học
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a wire:navigate href="{{ route('student.assignments.overview') }}"
                        class="text-white text-decoration-none d-block {{ $active === 'assignments' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                        <i class="bi bi-journal-text me-2"></i> Bài tập
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a wire:navigate href="{{ route('student.quizzes.index') }}"
                        class="text-white text-decoration-none d-block {{ $active === 'tests' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                        <i class="bi bi-journal-check me-2"></i> Kiểm tra
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a wire:navigate href="#"
                        class="text-white text-decoration-none d-block {{ $active === 'results' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                        <i class="bi bi-bar-chart me-2"></i> Kết quả
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a wire:navigate href="{{ route('student.schedules') }}"
                        class="text-white text-decoration-none d-block {{ $active === 'schedules' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                        <i class="bi bi-calendar3 me-2"></i> Lịch học
                    </a>
                </li>


                <li class="nav-item mb-2">
                    <a wire:navigate href="#"
                        class="text-white text-decoration-none d-block {{ $active === 'messages' ? 'active bg-primary rounded px-4 py-2' : 'px-4 py-2' }}">
                        <i class="bi bi-bell me-2"></i> Tin nhắn & thông báo
                    </a>
                </li>
            </ul>
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
</div>
