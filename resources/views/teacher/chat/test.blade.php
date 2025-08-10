<x-layouts.dash-teacher active="chat">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-vial me-2"></i>
                            Test Chat Functionality
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6>Test User:</h6>
                                @if($selectedTestUser)
                                    <div class="alert alert-info">
                                        <strong>{{ $selectedTestUser->name }}</strong><br>
                                        <small>{{ $selectedTestUser->email }} ({{ ucfirst($selectedTestUser->role) }})</small>
                                    </div>
                                @else
                                    <div class="alert alert-warning">Không có user để test</div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6>Test Class:</h6>
                                @if($selectedTestClass)
                                    <div class="alert alert-info">
                                        <strong>{{ $selectedTestClass->name }}</strong><br>
                                        <small>{{ $selectedTestClass->users->count() }} thành viên</small>
                                    </div>
                                @else
                                    <div class="alert alert-warning">Không có lớp để test</div>
                                @endif
                            </div>
                        </div>

                        <form wire:submit.prevent="sendTestMessage">
                            <div class="mb-3">
                                <label for="testMessage" class="form-label">Test Message:</label>
                                <textarea 
                                    id="testMessage"
                                    class="form-control" 
                                    rows="3"
                                    wire:model="testMessage"
                                    placeholder="Nhập tin nhắn test..."></textarea>
                                @error('testMessage')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="testAttachment" class="form-label">Test Attachment:</label>
                                <input 
                                    type="file" 
                                    id="testAttachment"
                                    class="form-control" 
                                    wire:model="testAttachment"
                                    accept="image/*,.pdf,.doc,.docx,.txt">
                                @error('testAttachment')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Send Test Message
                                </button>
                            </div>
                        </form>

                        <hr>

                        <div class="mt-4">
                            <h6>Test Instructions:</h6>
                            <ol>
                                <li>Mở tab chat chính trong trình duyệt khác hoặc cửa sổ ẩn danh</li>
                                <li>Đăng nhập với tài khoản khác</li>
                                <li>Gửi tin nhắn test từ đây</li>
                                <li>Kiểm tra xem tin nhắn có xuất hiện realtime không</li>
                            </ol>
                        </div>

                        <div class="mt-4">
                            <h6>Debug Information:</h6>
                            <div class="alert alert-secondary">
                                <strong>Current User ID:</strong> {{ Auth::id() }}<br>
                                <strong>Broadcast Driver:</strong> {{ config('broadcasting.default') }}<br>
                                <strong>Pusher App Key:</strong> {{ config('broadcasting.connections.pusher.key') ? 'Set' : 'Not Set' }}<br>
                                <strong>Echo Available:</strong> <span id="echoStatus">Checking...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if Echo is available
        if (typeof window.Echo !== 'undefined') {
            document.getElementById('echoStatus').textContent = 'Available';
            document.getElementById('echoStatus').className = 'text-success';
        } else {
            document.getElementById('echoStatus').textContent = 'Not Available';
            document.getElementById('echoStatus').className = 'text-danger';
        }
    });
    </script>
</x-layouts.dash-teacher> 