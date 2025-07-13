<div>
    @if($unreadCount > 0)
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-primary text-white">
                <i class="bi bi-chat-dots-fill me-2"></i>
                <strong class="me-auto">Tin nhắn mới</strong>
                <small>{{ now()->format('H:i') }}</small>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Bạn có {{ $unreadCount }} tin nhắn chưa đọc
                @if($latestMessage)
                <br>
                <small class="text-muted">
                    Tin nhắn mới nhất: {{ Str::limit($latestMessage->message, 50) }}
                </small>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

@script
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('showNotification', (data) => {
            // Hiển thị thông báo browser notification nếu được phép
            if (Notification.permission === 'granted') {
                new Notification(data.title, {
                    body: data.message,
                    icon: '/educore-logo.png'
                });
            }

            // Hiển thị toast notification
            const toast = new bootstrap.Toast(document.querySelector('.toast'));
            toast.show();
        });
    });

    // Yêu cầu quyền thông báo
    if (Notification.permission === 'default') {
        Notification.requestPermission();
    }
</script>
@endscript
