<x-layouts.dash-admin active="chat">
    <div class="container-fluid py-2">
        <div class="row">
            <!-- Sidebar - Danh sách người dùng và lớp học -->
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-chat-dots-fill mr-2"></i>
                            Chat & Tương tác
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
                                    placeholder="Tìm kiếm...">
                            </div>
                        </div>

                        <!-- Tabs -->
                        <ul class="nav nav-tabs nav-fill" id="chatTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if ($activeTab === 'users') active @endif"
                                    wire:click="setActiveTab('users')" id="users-tab" type="button" role="tab">
                                    <i class="bi bi-people-fill mr-1"></i>Người dùng
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if ($activeTab === 'classes') active @endif"
                                    wire:click="setActiveTab('classes')" id="classes-tab" type="button" role="tab">
                                    <i class="bi bi-diagram-3-fill mr-1"></i>Lớp học
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
                                            <p class="mt-2">Không có người dùng nào</p>
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
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center {{ $isMine ? 'ms-3' : 'mr-3' }}"
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
                                            @if($message->attachment)
                                                <div class="attachment mt-2">
                                                    <i class="bi bi-paperclip"></i>
                                                    <a href="{{ Storage::url($message->attachment) }}" target="_blank" class="text-decoration-none">
                                                        {{ basename($message->attachment) }}
                                                    </a>
                                                    <small class="text-muted d-block">{{ number_format(Storage::size($message->attachment) / 1024, 1) }} KB</small>
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
                                <form wire:submit.prevent="sendMessage" enctype="multipart/form-data">
                                    <div class="row g-2">
                                        <div class="col">
                                            <div class="input-group">
                                                <input type="text" wire:model="messageText" class="form-control"
                                                    placeholder="Nhập tin nhắn..." maxlength="1000">
                                                <input type="file" wire:model="attachment" id="attachment" class="d-none" accept="image/*,audio/*,video/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar,.7z">
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('attachment').click()">
                                                    <i class="fas fa-paperclip"></i>
                                                </button>
                                                <button type="button" wire:click="testUpload" class="btn btn-warning btn-sm">
                                                    Test Upload
                                                </button>
                                                <button type="submit" class="btn btn-primary">
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
            // File upload handling
            document.addEventListener('DOMContentLoaded', function() {
                const fileInput = document.getElementById('attachment');
                if (fileInput) {
                    fileInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            console.log('File selected:', file.name, file.size, file.type);
                            // Gửi file qua Livewire
                            @this.set('attachment', file);
                        }
                    });
                }
            });

            // Auto scroll to bottom when new messages arrive
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('messageSent', () => {
                    setTimeout(() => {
                        const container = document.getElementById('messagesContainer');
                        if (container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    }, 100);
                });

                // Real-time message updates
                Livewire.on('messageReceived', () => {
                    // Show notification
                    if (Notification.permission === 'granted') {
                        new Notification('Tin nhắn mới', {
                            body: 'Bạn có tin nhắn mới',
                            icon: '/favicon.ico'
                        });
                    }

                    // Auto scroll to bottom
                    setTimeout(() => {
                        const container = document.getElementById('messagesContainer');
                        if (container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    }, 100);
                });

                // Listen to Pusher events
                if (window.Echo) {
                    // Listen to private user channels
                    window.Echo.private(`chat-user-${@js(auth()->id())}`)
                        .listen('.message.sent', (e) => {
                            @this.call('handleNewMessage', e);
                        });

                    // Listen to class channels (nếu đang ở trong class chat)
                    @if ($selectedClass)
                        window.Echo.channel(`chat-class-${@js($selectedClass->id)}`)
                            .listen('.message.sent', (e) => {
                                @this.call('handleNewMessage', e);
                            });
                    @endif
                }
            });

            // Request notification permission
            if (Notification.permission === 'default') {
                Notification.requestPermission();
            }

            // Không cần auto refresh nữa vì đã dùng Pusher realtime

            // Real-time typing indicator
            let typingTimer;
            const messageInput = document.querySelector('input[wire\\:model="messageText"]');
            if (messageInput) {
                messageInput.addEventListener('input', () => {
                    clearTimeout(typingTimer);
                    // You can add typing indicator logic here
                    typingTimer = setTimeout(() => {
                        // Stop typing indicator
                    }, 1000);
                });
            }
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
