<x-layouts.dash>
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('users.index') }}" wire:navigate
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-person-plus-fill me-2"></i>Thêm người dùng mới
            </h4>
        </div>

        <!-- Form Card -->
        <div class="card shadow-sm max-w-xl">
            <div class="card-body">
                <form wire:submit="save">
                    <!-- Thông tin cơ bản -->
                    <div class="mb-4">
                        <h5 class="text-primary mb-3">Thông tin cá nhân</h5>

                        <div class="mb-3">
                            <label for="name" class="form-label">Họ và tên <span
                                    class="text-danger">*</span></label>
                            <input wire:model="name" type="text"
                                class="form-control @error('name') is-invalid @enderror" id="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại <span
                                    class="text-danger">*</span></label>
                            <input wire:model="phone" type="text"
                                class="form-control @error('phone') is-invalid @enderror" id="phone">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input wire:model="email" type="email"
                                class="form-control @error('email') is-invalid @enderror" id="email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Thông tin tài khoản -->
                    <div class="mb-4">
                        <h5 class="text-primary mb-3">Thông tin tài khoản</h5>

                        <div class="mb-3">
                            <label for="role" class="form-label">Vai trò <span class="text-danger">*</span></label>
                            <select wire:model="role" class="form-select @error('role') is-invalid @enderror"
                                id="role">
                                <option value="">Chọn vai trò</option>
                                <option value="admin">Quản trị viên</option>
                                <option value="teacher">Giảng viên</option>
                                <option value="student">Học viên</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                            <input wire:model="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" id="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu <span
                                    class="text-danger">*</span></label>
                            <input wire:model="password_confirmation" type="password" class="form-control"
                                id="password_confirmation">
                        </div>

                        <div class="form-check mb-3">
                            <input wire:model="is_active" :checked="is_active" class="form-check-input" type="checkbox" id="is_active">
                            <label class="form-check-label" for="is_active">
                                Kích hoạt tài khoản
                            </label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('users.index') }}" wire:navigate class="btn btn-light">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-plus-fill me-2"></i>Tạo người dùng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.dash>
