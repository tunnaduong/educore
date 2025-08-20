<x-layouts.dash-student active="chat">
    @include('components.language')
    <div class="container-fluid py-2">
        <div class="row">
            <!-- Sidebar - Danh sách giáo viên và lớp học -->
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
                                <button class="nav-link @if ($activeTab === 'classes') active @endif"
                                    wire:click="setActiveTab('classes')" id="classes-tab" type="button" role="tab">
                                    <i class="bi bi-diagram-3-fill mr-1"></i>Lớp học
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if ($activeTab === 'users') active @endif"
                                    wire:click="setActiveTab('users')" id="users-tab" type="button" role="tab">
                                    <i class="bi bi-people-fill mr-1"></i>Giáo viên
                                </button>
                            </li>
                        </ul>

                        <!-- Tab content -->
                        <div class="tab-content" id="chatTabsContent">
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
                                                    <div class="rounded-circle bg-info d-flex align-items-center justify-content-center"
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
                                            <p class="mt-2">Không có giáo viên nào</p>
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
                                    <div class="rounded-circle bg-info d-flex align-items-center justify-content-center mr-3"
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
                                                    class="card {{ $isMine ? 'bg-primary text-white' : 'bg-light' }}">
                                                    <div class="card-body py-2 px-3">
                                                        <div class="d-flex align-items-center mb-1">
                                                            <span class="fw-bold"
                                                                style="font-size: 0.95rem;">{{ $sender->name }}</span>
                                                        </div>
                                                        <p class="mb-1">{{ $message->message }}</p>
                                                        @if ($message->attachment)
                                                            <div class="mt-2">
                                                                <a href="{{ Storage::url($message->attachment) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm {{ $isMine ? 'btn-light' : 'btn-outline-primary' }}">
                                                                    <i class="bi bi-paperclip mr-1"></i>
                                                                    Tệp đính kèm
                                                                </a>
                                                            </div>
                                                        @endif
                                                        <small
                                                            class="{{ $isMine ? 'text-white' : 'text-muted' }} d-block mt-1"
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

                            <!-- Message input with drag & drop -->
                            <div class="border-top pt-3">
                                <form wire:submit.prevent="sendMessage">
                                    <div class="row g-2">
                                        <div class="col">
                                            <div class="input-group">
                                                <input type="text" wire:model="messageText" class="form-control"
                                                    placeholder="Nhập tin nhắn..." maxlength="1000">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    onclick="document.getElementById('attachment').click()">
                                                    <i class="bi bi-paperclip"></i>
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-send"></i>
                                                </button>
                                            </div>
                                            <input type="file" wire:model="attachment" id="attachment"
                                                class="d-none" accept="image/*,audio/*,video/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar,.7z">
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

                                <!-- Drag & Drop Zone -->
                                <div id="dragDropZone"
                                    class="mt-2 p-3 border-2 border-dashed border-secondary rounded text-center"
                                    style="display: none; background-color: rgba(0,123,255,0.1);">
                                    <i class="bi bi-cloud-upload fs-1 text-primary"></i>
                                    <p class="mb-0 mt-2">Kéo thả file vào đây để đính kèm</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Welcome screen -->
                        <div class="card-body d-flex align-items-center justify-content-center"
                            style="height: 400px;">
                            <div class="text-center">
                                <i class="bi bi-chat-dots-fill" style="font-size: 4rem; color: #0dcaf0;"></i>
                                <h4 class="mt-3">Chào mừng đến với Chat & Tương tác</h4>
                                <p class="text-muted">Chọn một lớp học hoặc giáo viên để bắt đầu cuộc trò chuyện</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @script
        <script>
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

            // Drag & Drop functionality
            document.addEventListener('DOMContentLoaded', function() {
                const dragDropZone = document.getElementById('dragDropZone');
                const messageInput = document.querySelector('input[wire\\:model="messageText"]');
                const fileInput = document.getElementById('attachment');

                if (dragDropZone && messageInput) {
                    // Show drag zone when hovering over input area
                    messageInput.addEventListener('dragenter', function(e) {
                        e.preventDefault();
                        dragDropZone.style.display = 'block';
                    });

                    messageInput.addEventListener('dragover', function(e) {
                        e.preventDefault();
                        dragDropZone.style.display = 'block';
                    });

                    messageInput.addEventListener('dragleave', function(e) {
                        e.preventDefault();
                        if (!dragDropZone.contains(e.relatedTarget)) {
                            dragDropZone.style.display = 'none';
                        }
                    });

                    // Handle file drop
                    dragDropZone.addEventListener('dragover', function(e) {
                        e.preventDefault();
                        dragDropZone.style.background = 'rgba(0,123,255,0.2)';
                    });

                    dragDropZone.addEventListener('dragleave', function(e) {
                        e.preventDefault();
                        dragDropZone.style.background = 'rgba(0,123,255,0.1)';
                    });

                    dragDropZone.addEventListener('drop', function(e) {
                        e.preventDefault();
                        dragDropZone.style.display = 'none';
                        dragDropZone.style.background = 'rgba(0,123,255,0.1)';

                        const files = e.dataTransfer.files;
                        if (files.length > 0) {
                            // Create a new FileList-like object
                            const dt = new DataTransfer();
                            dt.items.add(files[0]);
                            fileInput.files = dt.files;

                            // Trigger Livewire file upload
                            fileInput.dispatchEvent(new Event('change', {
                                bubbles: true
                            }));
                        }
                    });

                    // Hide drag zone when clicking outside
                    document.addEventListener('click', function(e) {
                        if (!dragDropZone.contains(e.target) && !messageInput.contains(e.target)) {
                            dragDropZone.style.display = 'none';
                        }
                    });
                }
            });

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
                // Hiển thị toast khi có thông báo
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

</x-layouts.dash-student>
