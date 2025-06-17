{{-- Navbar Upper --}}
<div class="min-vh-100 d-flex flex-column">
    <div class="bg-[#23417e] text-white p-3 d-flex justify-content-between align-items-center">
        <a wire:navigate href="/">
            <div class="d-flex align-items-center">
                <img src="/educore-logo.png" alt="Logo" style="width: 50px; height: 50px;" class="me-2">
                <span class="fs-4 fw-bold text-white">Edu</span>
                <span class="fs-4 fw-bold text-warning">Core</span>
            </div>
        </a>
        <div class="dropdown">
            <i class="bi bi-bell fs-5 me-3"></i>
            <a href="#" class="fw-bold text-white text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Admin
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
        <div class="bg-dark text-white p-4" style="min-width: 260px;">
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a wire:navigate href="/dashboard/home" class="text-white text-decoration-none">
                        <i class="bi bi-house me-2"></i> Trang chủ
                    </a>
                </li>
            </ul>
            <div class="my-4">
                <div class="fw-bold text-uppercase small mb-2">Quản lý đào tạo</div>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a wire:navigate href="#" class="text-white text-decoration-none">
                            <i class="bi bi-calendar3 me-2"></i> Lịch học
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a wire:navigate href="#" class="text-white text-decoration-none">
                            <i class="bi bi-diagram-3 me-2"></i> Lộ trình học
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a wire:navigate href="#" class="text-white text-decoration-none">
                            <i class="bi bi-journal-text me-2"></i> Giao bài tập
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a wire:navigate href="#" class="text-white text-decoration-none">
                            <i class="bi bi-upload me-2"></i> Nộp bài & chấm bài
                        </a>
                    </li>
                </ul>
            </div>
            <div class="mb-4">
                <div class="fw-bold text-uppercase small mb-2">Quản lý học viên</div>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a wire:navigate href="#" class="text-white text-decoration-none">
                            <i class="bi bi-people me-2"></i> Danh sách học viên
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a wire:navigate href="#" class="text-white text-decoration-none">
                            <i class="bi bi-tags me-2"></i> Phân loại học viên
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a wire:navigate href="#" class="text-white text-decoration-none">
                            <i class="bi bi-bar-chart me-2"></i> Báo cáo học tập
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a wire:navigate href="#" class="text-white text-decoration-none">
                            <i class="bi bi-bell me-2"></i> Tin nhắn & thông báo
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Main Content -->
        <div class="flex-grow-1 p-4 flex flex-col" style="background: #f8fafc;">
            <div class="flex-1">
                {{ $slot }}
            </div>
            <footer class="text-center mt-4 text-muted small">
                ©2025 Trung tâm Hanxen Kim Bảng Hà Nam — Powered by EduCore
            </footer>
        </div>
    </div>
</div>
