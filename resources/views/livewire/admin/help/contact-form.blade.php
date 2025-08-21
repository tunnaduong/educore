<div>
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Form liên hệ nhanh -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-paper-plane"></i>
                        Gửi yêu cầu trợ giúp
                    </h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="submitTicket">
                        <div class="form-group">
                            <label for="name">Họ tên</label>
                            <input type="text" class="form-control" id="name" wire:model="name" readonly>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" wire:model="email" readonly>
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả vấn đề <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" wire:model="description" rows="5" 
                                placeholder="Mô tả chi tiết vấn đề bạn gặp phải..."></textarea>
                            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label for="attachment">Upload file ảnh/video lỗi (tối đa 10MB)</label>
                            <input type="file" class="form-control-file" id="attachment" wire:model="attachment">
                            <small class="form-text text-muted">Chỉ chấp nhận file .jpg, .png, .mp4</small>
                            @error('attachment') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        @if ($attachment)
                            <div class="form-group">
                                <div class="alert alert-info">
                                    <strong>File đã chọn:</strong> {{ $attachment->getClientOriginalName() }}
                                    <button type="button" class="btn btn-sm btn-outline-danger ml-2" wire:click="$set('attachment', null)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                <i class="fas fa-paper-plane"></i> Gửi yêu cầu
                            </span>
                            <span wire:loading>
                                <i class="fas fa-spinner fa-spin"></i> Đang gửi...
                            </span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Thông tin liên hệ trực tiếp -->
            <div class="card mt-3">
                <div class="card-header bg-success text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-headset"></i>
                        Liên hệ trực tiếp
                    </h6>
                </div>
                <div class="card-body">
                    <div class="contact-info">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-envelope text-primary mr-3"></i>
                            <div>
                                <strong>Email dev team:</strong><br>
                                <a href="mailto:dev@educore.com">dev@educore.com</a>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-phone text-primary mr-3"></i>
                            <div>
                                <strong>SĐT hỗ trợ:</strong><br>
                                <a href="tel:+84123456789">+84 123 456 789</a>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <i class="fab fa-slack text-primary mr-3"></i>
                            <div>
                                <strong>Slack:</strong><br>
                                <a href="https://educore.slack.com" target="_blank">#support-channel</a>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <i class="fab fa-discord text-primary mr-3"></i>
                            <div>
                                <strong>Discord:</strong><br>
                                <a href="https://discord.gg/educore" target="_blank">EduCore Support Server</a>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <i class="fab fa-telegram text-primary mr-3"></i>
                            <div>
                                <strong>Telegram:</strong><br>
                                <a href="https://t.me/educore_support" target="_blank">@educore_support</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lịch sử hỗ trợ -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history"></i>
                        Lịch sử yêu cầu hỗ trợ
                    </h5>
                </div>
                <div class="card-body">
                    @if (count($tickets) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tiêu đề</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                        <th>Cập nhật</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tickets as $ticket)
                                        <tr>
                                            <td>#{{ $ticket['id'] }}</td>
                                            <td>{{ $ticket['subject'] }}</td>
                                            <td>
                                                <span class="badge {{ $this->getStatusBadgeClass($ticket['status']) }}">
                                                    {{ $this->getStatusText($ticket['status']) }}
                                                </span>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($ticket['created_at'])->format('d/m/Y H:i') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($ticket['updated_at'])->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted"></i>
                            <p class="text-muted mt-2">Chưa có yêu cầu hỗ trợ nào</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Thống kê nhanh -->
            <div class="card mt-3">
                <div class="card-header bg-secondary text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-pie"></i>
                        Thống kê hỗ trợ
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="border-right">
                                <h4 class="text-warning">{{ count(array_filter($tickets, fn($t) => $t['status'] === 'pending')) }}</h4>
                                <small class="text-muted">Chờ xử lý</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-right">
                                <h4 class="text-info">{{ count(array_filter($tickets, fn($t) => $t['status'] === 'processing')) }}</h4>
                                <small class="text-muted">Đang xử lý</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <h4 class="text-success">{{ count(array_filter($tickets, fn($t) => $t['status'] === 'completed')) }}</h4>
                            <small class="text-muted">Hoàn thành</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
