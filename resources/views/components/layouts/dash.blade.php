{{-- Navbar Upper --}}
<div>
    <div class="bg-[#23417e] text-white p-3 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <img src="/educore-logo.png" alt="Logo" style="width: 36px; height: 36px;" class="me-2">
            <span class="fs-4 fw-bold text-white">Edu</span>
            <span class="fs-4 fw-bold text-warning">core</span>
        </div>
        <div>
            <i class="bi bi-bell fs-5 me-3"></i>
            <span class="fw-bold">Admin</span>
            <i class="bi bi-person-circle ms-2"></i>
        </div>
    </div>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="bg-dark text-white p-4" style="width: 260px; min-height: 100vh;">
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="/dashboard/home" wire:navigate class="text-white text-decoration-none">
                        <i class="bi bi-house me-2"></i> Trang chủ
                    </a>
                </li>
            </ul>
            <div class="my-4">
                <div class="fw-bold text-uppercase small mb-2">Quản lý đào tạo</div>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a href="#" class="text-white text-decoration-none">
                            <i class="bi bi-calendar3 me-2"></i> Lịch học
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="#" class="text-white text-decoration-none">
                            <i class="bi bi-diagram-3 me-2"></i> Lộ trình học
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="#" class="text-white text-decoration-none">
                            <i class="bi bi-journal-text me-2"></i> Giao bài tập
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="#" class="text-white text-decoration-none">
                            <i class="bi bi-upload me-2"></i> Nộp bài & chấm bài
                        </a>
                    </li>
                </ul>
            </div>
            <div class="mb-4">
                <div class="fw-bold text-uppercase small mb-2">Quản lý học viên</div>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a href="#" class="text-white text-decoration-none">
                            <i class="bi bi-people me-2"></i> Danh sách học viên
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="#" class="text-white text-decoration-none">
                            <i class="bi bi-tags me-2"></i> Phân loại học viên
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="#" class="text-white text-decoration-none">
                            <i class="bi bi-bar-chart me-2"></i> Báo cáo học tập
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="#" class="text-white text-decoration-none">
                            <i class="bi bi-bell me-2"></i> Tin nhắn & thông báo
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Main Content -->
        <div class="flex-grow-1 p-4" style="background: #f8fafc;">
            {{ $slot }}
            <footer class="text-center mt-4 text-muted small">
                ©2025 Trung tâm Hanxen Kim Bảng Hà Nam — Powered by Educore
            </footer>
        </div>
    </div>
</div>
