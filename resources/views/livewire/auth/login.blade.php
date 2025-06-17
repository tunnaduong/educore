<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card shadow-lg border-0 rounded-4">
                    <!-- Header with Logo -->
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="flex justify-center">
                                <img src="/educore-logo.png" alt="Logo" style="width: 60px; height: 60px;">
                            </div>
                            <h3 class="fw-bold text-[25px] mb-2 -mt-3">
                                <span class="text-primary">Edu</span><span class="text-warning">Core</span>
                            </h3>
                            <p class="text-muted">Đăng nhập vào hệ thống quản lý học tập</p>
                        </div>

                        <form wire:submit.prevent="login">
                            <!-- Phone Input -->
                            <div class="mb-3">
                                <label for="phone" class="form-label fw-semibold text-dark">
                                    <i class="bi bi-phone me-2 text-primary"></i>Số điện thoại
                                </label>
                                <input type="text" id="phone"
                                    class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                    wire:model.defer="phone" placeholder="Nhập số điện thoại của bạn">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password Input -->
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold text-dark">
                                    <i class="bi bi-lock me-2 text-primary"></i>Mật khẩu
                                </label>
                                <input type="password" id="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    wire:model.defer="password" placeholder="Nhập mật khẩu của bạn">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="remember">
                                <label class="form-check-label text-muted" for="remember">
                                    Ghi nhớ đăng nhập
                                </label>
                            </div>

                            <!-- Login Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg fw-semibold py-3 rounded-3">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>
                                    Đăng nhập
                                </button>
                            </div>
                        </form>

                        <!-- Footer -->
                        <div class="text-center mt-4">
                            <small class="text-muted">
                                © 2025 EduCore<br>Hệ thống quản lý học tập trung tâm tiếng Trung
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
