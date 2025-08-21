<x-layouts.dash-teacher active="chat">
    @include('components.language')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-4 col-lg-3">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-comments me-2"></i>
                            Chat
                        </h5>
                    </div>

                    <div class="card-body p-0">
                        <!-- Tabs -->
                        <ul class="nav nav-tabs" id="chatTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeTab === 'classes' ? 'active' : '' }}"
                                    wire:click="setActiveTab('classes')" type="button" role="tab">
                                    <i class="fas fa-users me-1"></i>
                                    Lớp học
                                    @if ($unreadCount > 0)
                                        <span class="badge bg-danger ms-1">{{ $unreadCount }}</span>
                                    @endif
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeTab === 'users' ? 'active' : '' }}"
                                    wire:click="setActiveTab('users')" type="button" role="tab">
                                    <i class="fas fa-user me-1"></i>
                                    Người dùng
                                </button>
                            </li>
                        </ul>

                        <!-- Search -->
                        <div class="p-3 border-bottom">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control" placeholder="Tìm kiếm..."
                                    wire:model.live="searchTerm">
                            </div>
                        </div>

                        <!-- Tab Content -->
                        <div class="tab-content" id="chatTabContent">
                            <!-- Classes Tab -->
                            <div class="tab-pane fade {{ $activeTab === 'classes' ? 'show active' : '' }}"
                                id="classes-tab" role="tabpanel">
                                <div class="list-group list-group-flush">
                                    @forelse($classes as $class)
                                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $selectedClass && $selectedClass->id === $class->id ? 'active' : '' }}"
                                            wire:click="selectClass({{ $class->id }})" style="cursor: pointer;">
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <i class="fas fa-users text-white"></i>
                                                </div>
                                                <div>
                                                    <h6
                                                        class="mb-0 {{ $selectedClass && $selectedClass->id === $class->id ? 'text-white' : '' }}">
                                                        {{ $class->name }}
                                                    </h6>
                                                    <small
                                                        class="text-muted {{ $selectedClass && $selectedClass->id === $class->id ? 'text-white-50' : '' }}">
                                                        {{ $class->users->count() }} thành viên
                                                    </small>
                                                </div>
                                            </div>
                                            @if ($class->unread_messages_count > 0)
                                                <span class="badge bg-danger rounded-pill">
                                                    {{ $class->unread_messages_count }}
                                                </span>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="text-center p-4">
                                            <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                            <p class="text-muted">{{ __('views.no_classes') }}</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Users Tab -->
                            <div class="tab-pane fade {{ $activeTab === 'users' ? 'show active' : '' }}" id="users-tab"
                                role="tabpanel">
                                <div class="list-group list-group-flush">
                                    @forelse($users as $user)
                                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $selectedUser && $selectedUser->id === $user->id ? 'active' : '' }}"
                                            wire:click="selectUser({{ $user->id }})" style="cursor: pointer;">
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="avatar-sm bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                                <div>
                                                    <h6
                                                        class="mb-0 {{ $selectedUser && $selectedUser->id === $user->id ? 'text-white' : '' }}">
                                                        {{ $user->name }}
                                                    </h6>
                                                    <small
                                                        class="text-muted {{ $selectedUser && $selectedUser->id === $user->id ? 'text-white-50' : '' }}">
                                                        {{ ucfirst($user->role) }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center p-4">
                                            <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                            <p class="text-muted">{{ __('views.no_users') }}</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="col-md-8 col-lg-9">
                <div class="card h-100">
                    @if ($selectedUser || $selectedClass)
                        <!-- Chat Header -->
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    @if ($selectedUser)
                                        <div
                                            class="avatar-sm bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $selectedUser->name }}</h6>
                                            <small class="text-muted">{{ ucfirst($selectedUser->role) }}</small>
                                        </div>
                                    @elseif($selectedClass)
                                        <div
                                            class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class="fas fa-users text-white"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $selectedClass->name }}</h6>
                                            <small class="text-muted">{{ $selectedClass->users->count() }}
                                                {{ __('views.members') }}</small>
                                        </div>
                                    @endif
                                </div>

                                <!-- Typing Indicator -->
                                @if (count($typingUsers) > 0)
                                    <div class="text-muted">
                                        <small>
                                            <i class="fas fa-circle text-success me-1"></i>
                                            {{ implode(', ', $typingUsers) }} đang nhập...
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Messages -->
                        <div class="card-body p-0" style="height: 400px; overflow-y: auto;" id="messagesContainer">
                            <div class="p-3">
                                @forelse($messages->reverse() as $message)
                                    <div
                                        class="d-flex mb-3 {{ $message->sender_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                                        <div class="message-bubble {{ $message->sender_id === auth()->id() ? 'bg-primary text-white' : 'bg-light' }}"
                                            style="max-width: 70%;">

                                            @if ($message->sender_id !== auth()->id())
                                                <small class="text-muted d-block mb-1">
                                                    {{ $message->sender->name }}
                                                </small>
                                            @endif

                                            <div class="message-content">
                                                {{ $message->message }}
                                            </div>

                                            @if ($message->attachment)
                                                <div class="mt-2">
                                                    <a href="{{ route('chat.download', $message->id) }}"
                                                        class="btn btn-sm {{ $message->sender_id === auth()->id() ? 'btn-light' : 'btn-outline-primary' }}">
                                                        <i class="fas fa-paperclip me-1"></i>
                                                        Tải file đính kèm
                                                    </a>
                                                </div>
                                            @endif

                                            <small class="text-muted d-block mt-1">
                                                {{ $message->created_at->format('H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted">
                                        <i class="fas fa-comments fa-2x mb-2"></i>
                                        <p>Chưa có tin nhắn nào</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Message Input -->
                        <div class="card-footer">
                            <form wire:submit.prevent="sendMessage">
                                <div class="row g-2">
                                    <div class="col">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Nhập tin nhắn..."
                                                wire:model="messageText" wire:keydown="startTyping"
                                                wire:keyup.debounce.1000ms="stopTyping">

                                            <button class="btn btn-outline-secondary" type="button"
                                                onclick="document.getElementById('attachment').click()">
                                                <i class="fas fa-paperclip"></i>
                                            </button>

                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        </div>

                                        <!-- Hidden file input -->
                                        <input type="file" id="attachment" wire:model="attachment" class="d-none"
                                            accept="image/*,.pdf,.doc,.docx,.txt">

                                        <!-- File preview -->
                                        @if ($attachment)
                                            <div class="mt-2 p-2 bg-light rounded">
                                                <small class="text-muted">
                                                    <i class="fas fa-file me-1"></i>
                                                    {{ $attachment->getClientOriginalName() }}
                                                </small>
                                                <button type="button" class="btn btn-sm btn-outline-danger ms-2"
                                                    wire:click="$set('attachment', null)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="card-body d-flex align-items-center justify-content-center"
                            style="height: 400px;">
                            <div class="text-center text-muted">
                                <i class="fas fa-comments fa-3x mb-3"></i>
                                <h5>Chọn một cuộc trò chuyện để bắt đầu</h5>
                                <p>Chọn một lớp học hoặc người dùng từ danh sách bên trái</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Drag & Drop Zone -->
    <div id="dragDropZone" class="position-fixed top-0 start-0 w-100 h-100 d-none"
        style="background: rgba(0,123,255,0.1); z-index: 9999; pointer-events: none;">
        <div class="d-flex align-items-center justify-content-center h-100">
            <div class="text-center bg-white p-4 rounded shadow">
                <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                <h5>Thả file để đính kèm</h5>
            </div>
        </div>
    </div>

    <style>
        .message-bubble {
            padding: 10px 15px;
            border-radius: 18px;
            word-wrap: break-word;
        }

        .message-bubble.bg-primary {
            border-bottom-right-radius: 5px;
        }

        .message-bubble.bg-light {
            border-bottom-left-radius: 5px;
        }

        .avatar-sm {
            width: 40px;
            height: 40px;
        }

        #messagesContainer::-webkit-scrollbar {
            width: 6px;
        }

        #messagesContainer::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        #messagesContainer::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        #messagesContainer::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .nav-tabs .nav-link {
            border: none;
            border-bottom: 2px solid transparent;
            color: #6c757d;
        }

        .nav-tabs .nav-link.active {
            border-bottom-color: #0d6efd;
            color: #0d6efd;
            background: none;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
        }

        .list-group-item.active:hover {
            background-color: #0d6efd;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messagesContainer = document.getElementById('messagesContainer');
            const dragDropZone = document.getElementById('dragDropZone');
            const attachmentInput = document.getElementById('attachment');

            // Auto scroll to bottom
            function scrollToBottom() {
                if (messagesContainer) {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            }

            // Scroll to bottom when new messages arrive
            Livewire.on('messageSent', () => {
                setTimeout(scrollToBottom, 100);
            });

            // Initial scroll
            scrollToBottom();

            // Drag & Drop functionality
            if (dragDropZone) {
                document.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    dragDropZone.classList.remove('d-none');
                    dragDropZone.style.pointerEvents = 'auto';
                });

                document.addEventListener('dragleave', function(e) {
                    if (e.target === dragDropZone) {
                        dragDropZone.classList.add('d-none');
                        dragDropZone.style.pointerEvents = 'none';
                    }
                });

                document.addEventListener('drop', function(e) {
                    e.preventDefault();
                    dragDropZone.classList.add('d-none');
                    dragDropZone.style.pointerEvents = 'none';

                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        const file = files[0];

                        // Create a new FileList-like object
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);

                        // Set the file to the input
                        if (attachmentInput) {
                            attachmentInput.files = dataTransfer.files;
                            // Trigger Livewire update
                            attachmentInput.dispatchEvent(new Event('change'));
                        }
                    }
                });
            }

            // Typing indicator
            let typingTimer;
            const messageInput = document.querySelector('input[wire\\:model="messageText"]');

            if (messageInput) {
                messageInput.addEventListener('input', function() {
                    clearTimeout(typingTimer);
                    @this.startTyping();

                    typingTimer = setTimeout(function() {
                        @this.stopTyping();
                    }, 1000);
                });
            }
        });
    </script>

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

</x-layouts.dash-teacher>
