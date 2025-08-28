<x-layouts.dash-teacher active="chat">
    @include('components.language')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-vial mr-2"></i>
                            Test Chat Functionality
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6>{{ __('views.test_user') }}</h6>
                                @if ($selectedTestUser)
                                    <div class="alert alert-info">
                                        <strong>{{ $selectedTestUser->name }}</strong><br>
                                        <small>{{ $selectedTestUser->email }}
                                            ({{ ucfirst($selectedTestUser->role) }})</small>
                                    </div>
                                @else
                                    <div class="alert alert-warning">{{ __('views.no_users_to_test') }}</div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6>{{ __('views.test_class') }}</h6>
                                @if ($selectedTestClass)
                                    <div class="alert alert-info">
                                        <strong>{{ $selectedTestClass->name }}</strong><br>
                                        <small>{{ $selectedTestClass->users->count() }}
                                            {{ __('views.members') }}</small>
                                    </div>
                                @else
                                    <div class="alert alert-warning">{{ __('views.no_classes_to_test') }}</div>
                                @endif
                            </div>
                        </div>

                        <form wire:submit.prevent="sendTestMessage">
                            <div class="mb-3">
                                <label for="testMessage" class="form-label">{{ __('views.test_message') }}</label>
                                <textarea id="testMessage" class="form-control" rows="3" wire:model="testMessage"
                                    placeholder="Nhập tin nhắn test..."></textarea>
                                @error('testMessage')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="testAttachment"
                                    class="form-label">{{ __('views.test_attachment') }}</label>
                                <input type="file" id="testAttachment" class="form-control"
                                    wire:model="testAttachment" accept="image/*,.pdf,.doc,.docx,.txt">
                                @error('testAttachment')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Send Test Message
                                </button>
                            </div>
                        </form>

                        <hr>

                        <div class="mt-4">
                            <h6>{{ __('views.test_instructions') }}</h6>
                            <ol>
                                <li>{{ __('views.test_instruction_1') }}</li>
                                <li>{{ __('views.test_instruction_2') }}</li>
                                <li>{{ __('views.test_instruction_3') }}</li>
                                <li>{{ __('views.test_instruction_4') }}</li>
                            </ol>
                        </div>

                        <div class="mt-4">
                            <h6>{{ __('views.debug_information') }}</h6>
                            <div class="alert alert-secondary">
                                <strong>{{ __('views.current_user_id') }}</strong> {{ Auth::id() }}<br>
                                <strong>{{ __('views.broadcast_driver') }}</strong>
                                {{ config('broadcasting.default') }}<br>
                                <strong>{{ __('views.pusher_app_key') }}</strong>
                                {{ config('broadcasting.connections.pusher.key') ? 'Set' : 'Not Set' }}<br>
                                <strong>{{ __('views.echo_available') }}</strong> <span
                                    id="echoStatus">{{ __('views.checking') }}</span>
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
