<x-layouts.dash-admin active="chat">
    @include('components.language')
    <div class="container-fluid py-2">
        <div class="row">
            <!-- Sidebar - Danh sách người dùng và lớp học -->
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-chat-dots-fill mr-2"></i>
                            {{ __('general.chat_and_interaction') }}
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <!-- Search -->
                        <div class="p-3 border-bottom">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" wire:model.live="searchTerm" class="form-control"
                                    placeholder="{{ __('general.search') }}...">
                            </div>
                        </div>

                        <!-- Tabs -->
                        <ul class="nav nav-tabs nav-fill" id="chatTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if ($activeTab === 'users') active @endif"
                                    wire:click="setActiveTab('users')" id="users-tab" type="button" role="tab">
                                    <i class="bi bi-people-fill mr-1"></i>{{ __('general.users') }}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if ($activeTab === 'classes') active @endif"
                                    wire:click="setActiveTab('classes')" id="classes-tab" type="button" role="tab">
                                    <i class="bi bi-diagram-3-fill mr-1"></i>{{ __('general.classes') }}
                                </button>
                            </li>
                        </ul>

                        <!-- Tab content -->
                        <div class="tab-content" id="chatTabsContent">
                            <!-- Users tab -->
                            <div class="tab-pane fade @if ($activeTab === 'users') show active @endif"
                                id="users" role="tabpanel">
                                <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                                    @forelse($users as $user)
                                        <button wire:click="selectUser({{ $user->id }})"
                                            class="list-group-item list-group-item-action d-flex align-items-center {{ $selectedUser && $selectedUser->id === $user->id ? 'active' : '' }}">
                                            <div class="flex-shrink-0">
                                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                                    style="width: 40px; height: 40px;">
                                                    <span
                                                        class="text-white fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ml-3">
                                                <h6 class="mb-0">{{ $user->name }}</h6>
                                                <small>{{ $user->email }}</small>
                                            </div>
                                            @if ($user->unread_messages_count > 0)
                                                <span
                                                    class="badge bg-danger rounded-pill">{{ $user->unread_messages_count }}</span>
                                            @endif
                                        </button>
                                    @empty
                                        <div class="list-group-item text-center text-muted">
                                            <i class="bi bi-people-fill" style="font-size: 2rem; color: #dee2e6;"></i>
                                            <p class="mt-2">{{ __('general.no_users_found') }}</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Classes tab -->
                            <div class="tab-pane fade @if ($activeTab === 'classes') show active @endif"
                                id="classes" role="tabpanel">
                                <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                                    @forelse($classes as $class)
                                        <button wire:click="selectClass({{ $class->id }})"
                                            class="list-group-item list-group-item-action d-flex align-items-center {{ $selectedClass && $selectedClass->id === $class->id ? 'active' : '' }}">
                                            <div class="flex-shrink-0">
                                                @if (!empty($class->avatar))
                                                    <img src="{{ asset('storage/' . $class->avatar) }}" alt="Avatar"
                                                        class="rounded-circle"
                                                        style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-success d-flex align-items-center justify-content-center"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="bi bi-diagram-3-fill text-white"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1 ml-3">
                                                <h6 class="mb-0">{{ $class->name }}</h6>
                                            </div>
                                            <span class="badge bg-danger rounded-pill ml-auto" style="min-width: 28px;">
                                                {{ $class->unread_messages_count ?? 0 }}
                                            </span>
                                        </button>
                                    @empty
                                        <div class="list-group-item text-center text-muted">
                                            <i class="bi bi-diagram-3-fill"
                                                style="font-size: 2rem; color: #dee2e6;"></i>
                                            <p class="mt-2">Không có lớp học nào</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main chat area -->
            <div class="col-md-7 col-lg-8">
                <div class="card shadow-sm h-100">
                    @if ($selectedUser || $selectedClass)
                        <!-- Chat header -->
                        <div class="card-header bg-light d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                @if ($selectedUser)
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mr-3"
                                        style="width: 40px; height: 40px;">
                                        <span
                                            class="text-white fw-bold">{{ strtoupper(substr($selectedUser->name, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $selectedUser->name }}</h6>
                                        <small class="text-muted">{{ $selectedUser->email }}</small>
                                    </div>
                                @elseif($selectedClass)
                                    <div class="rounded-circle bg-success d-flex align-items-center justify-content-center mr-3"
                                        style="width: 40px; height: 40px;">
                                        <i class="bi bi-diagram-3-fill text-white"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $selectedClass->name }}</h6>
                                        <small class="text-muted">Lớp học</small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Messages area -->
                        <div class="card-body d-flex flex-column" style="height: 400px;">
                            <div class="flex-grow-1 overflow-auto mb-3" id="messagesContainer">
                                @forelse($messages->reverse() as $message)
                                    @php
                                        $isMine = $message->sender_id === auth()->id();
                                        $sender = $message->sender;
                                    @endphp
                                    <div
                                        class="d-flex mb-3 {{ $isMine ? 'justify-content-end' : 'justify-content-start' }}">
                                        <div
                                            class="d-flex {{ $isMine ? 'flex-row-reverse' : 'flex-row' }} align-items-end">
                                            <!-- Avatar -->
                                            <div class="flex-shrink-0">
                                                @if (!empty($sender->avatar))
                                                    <img src="{{ asset('storage/' . $sender->avatar) }}"
                                                        alt="Avatar" class="rounded-circle"
                                                        style="width: 35px; height: 35px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center {{ $isMine ? 'ml-3' : 'mr-3' }}"
                                                        style="width: 35px; height: 35px; background-color: {{ $isMine ? '#0d6efd' : '#6c757d' }};">
                                                        <span
                                                            class="text-white fw-bold">{{ strtoupper(substr($sender->name, 0, 1)) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <!-- Message content -->
                                            <div class="flex-grow-1" style="max-width: 70%;">
                                                <div
                                                    class="card {{ $isMine ? 'bg-primary text-white' : 'bg-dark text-white' }}">
                                                    <div class="card-body py-2 px-3">
                                                        <div class="d-flex align-items-center mb-1">
                                                            <span class="fw-bold"
                                                                style="font-size: 0.95rem;">{{ $sender->name }}</span>
                                                        </div>
                                                        <div class="message-content">
                                                            <div class="message-text">{{ $message->message }}</div>
                                                            @if ($message->attachment)
                                                                <div class="attachment mt-2">
                                                                    <i class="bi bi-paperclip"></i>
                                                                    <a href="{{ Storage::url($message->attachment) }}"
                                                                        target="_blank" class="text-decoration-none">
                                                                        {{ basename($message->attachment) }}
                                                                    </a>
                                                                    <small
                                                                        class="text-muted d-block">{{ number_format(Storage::size($message->attachment) / 1024, 1) }}
                                                                        KB</small>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <small class="text-white d-block mt-1"
                                                            style="font-size: 0.85rem;">
                                                            {{ $message->created_at->format('H:i d/m/Y') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted mt-5">
                                        <i class="bi bi-chat-dots" style="font-size: 3rem; color: #dee2e6;"></i>
                                        <p class="mt-3">Chưa có tin nhắn nào</p>
                                        <p>Bắt đầu cuộc trò chuyện ngay!</p>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Message input -->
                            <div class="border-top pt-3">
                                <form id="chatForm" enctype="multipart/form-data">
                                    <div class="row g-2">
                                        <div class="col">
                                            <div class="input-group">
                                                <input type="text" id="messageText" class="form-control"
                                                    placeholder="Nhập tin nhắn..." maxlength="1000">
                                                <input type="file" id="attachment" class="d-none"
                                                    accept="image/*,audio/*,video/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar,.7z">
                                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                                    onclick="document.getElementById('attachment').click()">
                                                    <i class="fas fa-paperclip"></i>
                                                </button>
                                                <button type="button" onclick="sendMessage()"
                                                    class="btn btn-primary">
                                                    <i class="bi bi-send"></i>
                                                </button>
                                            </div>
                                            @error('attachment')
                                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                                            @enderror
                                            @if ($attachment)
                                                <small class="text-muted">
                                                    <i class="bi bi-paperclip"></i>
                                                    {{ $attachment->getClientOriginalName() }}
                                                </small>
                                            @endif
                                            @error('messageText')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Welcome screen -->
                        <div class="card-body d-flex align-items-center justify-content-center"
                            style="height: 400px;">
                            <div class="text-center">
                                <i class="bi bi-chat-dots-fill" style="font-size: 4rem; color: #0dcaf0;"></i>
                                <h4 class="mt-3">Chào mừng đến với Chat & Tương tác</h4>
                                <p class="text-muted">Chọn một người dùng hoặc lớp học để bắt đầu cuộc trò chuyện</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @script
        <script>
            // Request notification permission
            if (Notification.permission === 'default') {
                Notification.requestPermission();
            }

            // File upload and message sending
            function sendMessage() {
                const messageText = document.getElementById('messageText').value;
                const fileInput = document.getElementById('attachment');
                const file = fileInput.files[0];
                
                if (!messageText.trim() && !file) {
                    alert('Vui lòng nhập tin nhắn hoặc chọn file');
                    return;
                }

                const formData = new FormData();
                if (file) {
                    formData.append('file', file);
                }
                if (messageText.trim()) {
                    formData.append('message_text', messageText);
                }

                // Add receiver or class info
                @if($selectedUser)
                    formData.append('receiver_id', {{ $selectedUser->id }});
                @elseif($selectedClass)
                    formData.append('class_id', {{ $selectedClass->id }});
                @endif

                // Show loading
                const sendButton = document.querySelector('button[onclick="sendMessage()"]');
                const originalText = sendButton.innerHTML;
                sendButton.innerHTML = '<i class="bi bi-hourglass-split"></i>';
                sendButton.disabled = true;

                fetch('/chat/upload', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Clear form
                        document.getElementById('messageText').value = '';
                        fileInput.value = '';
                        
                        // Add message to UI
                        addMessageToUI(data.message);
                        
                        // Show success notification
                        showNotification('Tin nhắn đã gửi', 'success');
                    } else {
                        alert('Lỗi: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi gửi tin nhắn');
                })
                .finally(() => {
                    // Restore button
                    sendButton.innerHTML = originalText;
                    sendButton.disabled = false;
                });
            }

            // Add message to UI
            function addMessageToUI(message) {
                const messagesContainer = document.getElementById('messagesContainer');
                if (!messagesContainer) return;

                const messageDiv = document.createElement('div');
                messageDiv.className = 'message-item mb-3';
                
                const isMine = message.sender_id == {{ auth()->id() }};
                const alignment = isMine ? 'text-end' : 'text-start';
                
                let attachmentHtml = '';
                if (message.attachment) {
                    attachmentHtml = `
                        <div class="attachment mt-2">
                            <i class="bi bi-paperclip"></i>
                            <a href="/storage/${message.attachment}" target="_blank" class="text-decoration-none">
                                ${message.attachment.split('/').pop()}
                            </a>
                        </div>
                    `;
                }

                messageDiv.innerHTML = `
                    <div class="${alignment}">
                        <div class="d-inline-block">
                            <div class="message-bubble ${isMine ? 'bg-primary text-white' : 'bg-light'} p-2 rounded">
                                <div class="message-content">
                                    <div class="message-text">${message.message || ''}</div>
                                    ${attachmentHtml}
                                </div>
                                <small class="text-muted d-block mt-1">
                                    ${new Date(message.created_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'})}
                                </small>
                            </div>
                        </div>
                    </div>
                `;

                messagesContainer.appendChild(messageDiv);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            // Show browser notification
            function showNotification(title, type = 'info') {
                if (Notification.permission === 'granted') {
                    new Notification(title, {
                        body: type === 'success' ? 'Thành công!' : 'Có tin nhắn mới',
                        icon: '/favicon.ico',
                        badge: '/favicon.ico'
                    });
                }
            }

            // Real-time chat with Pusher
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Pusher if available
                if (window.Echo) {
                    console.log('Pusher initialized');
                    
                    // Listen to private user channels
                    window.Echo.private(`chat-user-{{ auth()->id() }}`)
                        .listen('.message.sent', (e) => {
                            console.log('Received message:', e);
                            
                            // Add message to UI
                            addMessageToUI(e.message);
                            
                            // Show notification if not focused
                            if (!document.hasFocus()) {
                                showNotification('Tin nhắn mới từ ' + e.message.sender.name);
                            }
                            
                            // Auto scroll
                            const container = document.getElementById('messagesContainer');
                            if (container) {
                                container.scrollTop = container.scrollHeight;
                            }
                        });

                    // Listen to class channels
                    @if($selectedClass)
                        window.Echo.channel(`chat-class-{{ $selectedClass->id }}`)
                            .listen('.message.sent', (e) => {
                                console.log('Received class message:', e);
                                
                                // Add message to UI
                                addMessageToUI(e.message);
                                
                                // Show notification if not focused
                                if (!document.hasFocus()) {
                                    showNotification('Tin nhắn mới trong lớp ' + '{{ $selectedClass->name }}');
                                }
                                
                                // Auto scroll
                                const container = document.getElementById('messagesContainer');
                                if (container) {
                                    container.scrollTop = container.scrollHeight;
                                }
                            });
                    @endif
                } else {
                    console.log('Pusher not available');
                }

                // File input change handler
                const fileInput = document.getElementById('attachment');
                if (fileInput) {
                    fileInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            console.log('File selected:', file.name, file.size, file.type);
                            // Show selected file info
                            const fileInfo = document.createElement('div');
                            fileInfo.className = 'alert alert-info alert-sm mt-2';
                            fileInfo.innerHTML = `
                                <i class="bi bi-paperclip"></i> 
                                ${file.name} (${(file.size / 1024).toFixed(1)} KB)
                            `;
                            
                            // Remove previous file info
                            const prevInfo = document.querySelector('.alert-info');
                            if (prevInfo) prevInfo.remove();
                            
                            // Add new file info
                            fileInput.parentNode.parentNode.appendChild(fileInfo);
                        }
                    });
                }
            });

                // Auto refresh chat messages every 30 seconds as fallback using AJAX
                function fetchChatMessages() {
                    // Replace '#chat-messages' with the actual id/class of your chat messages container
                    fetch('/admin/chat/messages') // Adjust this URL to your actual endpoint
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.text();
                        })
                        .then(html => {
                            const chatContainer = document.querySelector('#chat-messages');
                            if (chatContainer) {
                                chatContainer.innerHTML = html;
                            }
                        })
                        .catch(error => {
                            console.error('Failed to fetch chat messages:', error);
                        });
                }

                setInterval(() => {
                    if (document.hasFocus()) {
                        fetchChatMessages();
                    }
                }, 30000);
            </script>
        @endscript

    @push('scripts')
        <script>
            document.addEventListener('livewire:load', function() {
                window.addEventListener('closeAddMemberModal', function() {
                    var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addMemberModal'));
                    if (modal) modal.hide();
                });
                // Hiển thị toast khi thêm thành viên
                Livewire.on('showToast', function(data) {
                    let type = data.type || 'info';
                    let message = data.message || '';
                    let toast = document.createElement('div');
                    toast.className = 'toast align-items-center text-bg-' + (type === 'success' ? 'success' : (
                            type === 'error' ? 'danger' : 'info')) +
                        ' border-0 position-fixed bottom-0 end-0 m-3';
                    toast.style.zIndex = 9999;
                    toast.innerHTML =
                        `<div class="d-flex"><div class="toast-body">${message}</div><button type="button" class="btn-close btn-close-white mr-2 m-auto" data-bs-dismiss="toast"></button></div>`;
                    document.body.appendChild(toast);
                    var bsToast = new bootstrap.Toast(toast, {
                        delay: 2500
                    });
                    bsToast.show();
                    toast.addEventListener('hidden.bs.toast', function() {
                        toast.remove();
                    });
                });
            });
        </script>
    @endpush

</x-layouts.dash-admin>
