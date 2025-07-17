<x-layouts.dash-student active="home">
    <div class="container py-4">
        <div class="row g-5">
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('student.lessons.index') }}" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-book" style="font-size:2.5rem; color:#0d6efd;"></i>
                    </div>
                    <div>Bài học</div>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('student.assignments.overview') }}" wire:navigate
                    class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-journal-text" style="font-size:2.5rem; color:#fd7e14;"></i>
                    </div>
                    <div>Bài tập</div>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('student.quizzes.index') }}" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-clipboard-check-fill" style="font-size:2.5rem; color:#6f42c1;"></i>
                    </div>
                    <div>Kiểm tra</div>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="#" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-bar-chart-fill" style="font-size:2.5rem; color:#20c997;"></i>
                    </div>
                    <div>Kết quả</div>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="#" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-calendar3" style="font-size:2.5rem; color:#ffc107;"></i>
                    </div>
                    <div>Lịch học</div>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="{{ route('student.notifications.index') }}" wire:navigate
                    class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-bell-fill" style="font-size:2.5rem; color:#fd5e53;"></i>
                    </div>
                    <div>Thông báo</div>
                </a>
            </div>
            <div class="col-6 col-md-3 text-center">
                <a href="#" wire:navigate class="text-decoration-none text-dark">
                    <div class="mb-2">
                        <i class="bi bi-chat-dots-fill" style="font-size:2.5rem; color:#3372a2;"></i>
                    </div>
                    <div>Tin nhắn</div>
                </a>
            </div>
        </div>
    </div>
</x-layouts.dash-student>
