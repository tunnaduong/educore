<div class="position-relative">
    <a href="{{ route('chat.index') }}" class="text-decoration-none text-dark">
        <i class="bi bi-chat-dots-fill" style="font-size: 1.2rem; color: #0dcaf0;"></i>
        @if($unreadCount > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
            style="font-size: 0.7rem;">{{ $unreadCount }}</span>
        @endif
    </a>
</div>
