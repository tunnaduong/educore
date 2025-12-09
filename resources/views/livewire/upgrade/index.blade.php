<div>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-crown mr-2"></i>Nâng cấp gói của bạn
                        </h4>
                    </div>
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Thông tin license hiện tại -->
                        @if ($currentLicense)
                            <div class="alert alert-info">
                                <h5><i class="fas fa-info-circle mr-2"></i>License hiện tại</h5>
                                <p class="mb-0">
                                    <strong>Gói:</strong>
                                    @if ($currentLicense->plan_type === 'free_trial')
                                        <span class="badge badge-success">Dùng thử (Free Trial)</span>
                                    @elseif ($currentLicense->plan_type === 'vip_monthly')
                                        <span class="badge badge-warning">VIP Monthly</span>
                                    @elseif ($currentLicense->plan_type === 'vip_yearly')
                                        <span class="badge badge-warning">VIP Yearly</span>
                                    @endif
                                    <br>
                                    <strong>Trạng thái:</strong>
                                    <span class="badge badge-{{ $currentLicense->isActive() ? 'success' : 'danger' }}">
                                        {{ $currentLicense->isActive() ? 'Active' : 'Expired' }}
                                    </span>
                                    <br>
                                    @if ($currentLicense->expires_at)
                                        <strong>Hết hạn:</strong> {{ $currentLicense->expires_at->format('d/m/Y H:i') }}
                                        @if ($currentLicense->daysRemaining() !== null)
                                            <br>
                                            <strong>Còn lại:</strong> {{ $currentLicense->daysRemaining() }} ngày
                                        @endif
                                    @endif
                                </p>
                            </div>
                        @endif

                        <!-- Các gói -->
                        <div class="row mt-4">
                            <!-- Free Trial -->
                            @if (!$currentLicense || !$currentLicense->isFreeTrial())
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100 border-success">
                                        <div class="card-header bg-success text-white text-center">
                                            <h5 class="mb-0">Dùng thử</h5>
                                            <p class="mb-0">Free Trial</p>
                                        </div>
                                        <div class="card-body text-center">
                                            <h3 class="text-success mb-3">Miễn phí</h3>
                                            <p>{{ config('license.free_trial_duration_days', 7) }} ngày dùng thử</p>
                                            <ul class="list-unstyled text-left">
                                                <li><i class="fas fa-check text-success mr-2"></i>Truy cập đầy đủ tính
                                                    năng</li>
                                                <li><i class="fas fa-check text-success mr-2"></i>Không cần thanh toán
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-success btn-block" disabled>
                                                @if ($currentLicense && $currentLicense->isFreeTrial())
                                                    Đang sử dụng
                                                @else
                                                    Đã sử dụng
                                                @endif
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- VIP Monthly -->
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 border-warning">
                                    <div class="card-header bg-warning text-dark text-center">
                                        <h5 class="mb-0">VIP Monthly</h5>
                                        <p class="mb-0">Gói theo tháng</p>
                                    </div>
                                    <div class="card-body text-center">
                                        <h3 class="text-warning mb-3">Liên hệ</h3>
                                        <p>Thanh toán hàng tháng</p>
                                        <ul class="list-unstyled text-left">
                                            <li><i class="fas fa-check text-success mr-2"></i>Tất cả tính năng VIP</li>
                                            <li><i class="fas fa-check text-success mr-2"></i>Gia hạn hàng tháng</li>
                                        </ul>
                                    </div>
                                    <div class="card-footer">
                                        <div class="mb-3">
                                            <strong>Mã thanh toán:</strong>
                                            <code class="d-block mt-2 p-2 bg-light">{{ $paymentCode }}MONTHLY</code>
                                        </div>
                                        <button class="btn btn-warning btn-block" disabled>
                                            Chuyển khoản theo hướng dẫn
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- VIP Yearly -->
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 border-warning">
                                    <div class="card-header bg-warning text-dark text-center">
                                        <h5 class="mb-0">VIP Yearly</h5>
                                        <p class="mb-0">Gói theo năm</p>
                                    </div>
                                    <div class="card-body text-center">
                                        <h3 class="text-warning mb-3">Liên hệ</h3>
                                        <p>Thanh toán hàng năm</p>
                                        <ul class="list-unstyled text-left">
                                            <li><i class="fas fa-check text-success mr-2"></i>Tất cả tính năng VIP</li>
                                            <li><i class="fas fa-check text-success mr-2"></i>Tiết kiệm hơn</li>
                                        </ul>
                                    </div>
                                    <div class="card-footer">
                                        <div class="mb-3">
                                            <strong>Mã thanh toán:</strong>
                                            <code class="d-block mt-2 p-2 bg-light">{{ $paymentCode }}YEARLY</code>
                                        </div>
                                        <button class="btn btn-warning btn-block" disabled>
                                            Chuyển khoản theo hướng dẫn
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hướng dẫn thanh toán -->
                        @if (!empty($bankAccount['account_number']))
                            <div class="card mt-4">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Hướng dẫn thanh toán</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Bước 1:</strong> Chuyển khoản đến tài khoản:</p>
                                    <ul>
                                        <li><strong>Số tài khoản:</strong> {{ $bankAccount['account_number'] }}</li>
                                        <li><strong>Chủ tài khoản:</strong> {{ $bankAccount['account_name'] ?? 'N/A' }}
                                        </li>
                                        <li><strong>Ngân hàng:</strong> {{ $bankAccount['bank_name'] ?? 'N/A' }}</li>
                                    </ul>
                                    <p><strong>Bước 2:</strong> Nội dung chuyển khoản phải chứa mã thanh toán của bạn
                                        (ví dụ: <code>{{ $paymentCode }}MONTHLY</code> hoặc
                                        <code>{{ $paymentCode }}YEARLY</code>)</p>
                                    <p><strong>Bước 3:</strong> Sau khi chuyển khoản, hệ thống sẽ tự động kích hoạt
                                        license trong vòng vài phút.</p>
                                    <div class="alert alert-warning mt-3">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        <strong>Lưu ý:</strong> Vui lòng chuyển đúng số tiền và ghi đúng mã thanh toán
                                        để hệ thống có thể xử lý tự động.
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
