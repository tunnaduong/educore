<x-layouts.dash-admin>
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('students.index') }}" wire:navigate
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>Quay lại
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-person-plus mr-2"></i>Thêm học viên mới
            </h4>
        </div>

        <!-- Form Card Centered with Illustration -->
        <div class="card shadow-sm p-0">
            <div class="row g-0 align-items-stretch">
                <div class="col-md-7 p-4">
                    <form wire:submit="save">
                        <!-- Thông tin cá nhân -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">Thông tin cá nhân</h5>
                            <div class="mb-3">
                                <label for="name" class="form-label">Họ tên <span
                                        class="text-danger">*</span></label>
                                <input wire:model="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                    placeholder="Nhập họ tên học viên">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input wire:model="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" id="email"
                                            placeholder="Nhập email">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Số điện thoại <span
                                                class="text-danger">*</span></label>
                                        <input wire:model="phone" type="text"
                                            class="form-control @error('phone') is-invalid @enderror" id="phone"
                                            placeholder="Nhập số điện thoại">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="date_of_birth" class="form-label">Ngày sinh</label>
                                        <input wire:model="date_of_birth" type="date"
                                            class="form-control @error('date_of_birth') is-invalid @enderror"
                                            id="date_of_birth">
                                        @error('date_of_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="joined_at" class="form-label">Ngày vào học</label>
                                        <input wire:model="joined_at" type="date"
                                            class="form-control @error('joined_at') is-invalid @enderror"
                                            id="joined_at">
                                        @error('joined_at')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu <span
                                        class="text-danger">*</span></label>
                                <input wire:model="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" id="password"
                                    placeholder="Nhập mật khẩu">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Thông tin học tập -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">Thông tin học tập</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Trạng thái <span
                                                class="text-danger">*</span></label>
                                        <select wire:model="status"
                                            class="form-control @error('status') is-invalid @enderror" id="status">
                                            <option value="active">Đang học</option>
                                            <option value="paused">Nghỉ</option>
                                            <option value="dropped">Bảo lưu</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="level" class="form-label">Trình độ</label>
                                        <select wire:model="level"
                                            class="form-control @error('level') is-invalid @enderror" id="level">
                                            <option value="">Chọn trình độ</option>
                                            <option value="HSK1">HSK 1</option>
                                            <option value="HSK2">HSK 2</option>
                                            <option value="HSK3">HSK 3</option>
                                            <option value="HSK4">HSK 4</option>
                                            <option value="HSK5">HSK 5</option>
                                            <option value="HSK6">HSK 6</option>
                                            <option value="Sơ cấp 1">Sơ cấp 1</option>
                                            <option value="Sơ cấp 2">Sơ cấp 2</option>
                                            <option value="Trung cấp 1">Trung cấp 1</option>
                                            <option value="Trung cấp 2">Trung cấp 2</option>
                                            <option value="Cao cấp 1">Cao cấp 1</option>
                                            <option value="Cao cấp 2">Cao cấp 2</option>
                                        </select>
                                        @error('level')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Ghi chú</label>
                                <textarea wire:model="notes" class="form-control @error('notes') is-invalid @enderror" id="notes" rows="3"
                                    placeholder="Nhập ghi chú về học viên..."></textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('students.index') }}" wire:navigate class="btn btn-light">Hủy</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-person-plus mr-2"></i>Thêm học viên
                            </button>
                        </div>
                    </form>
                </div>
                <div
                    class="col-md-5 d-flex flex-column justify-content-center align-items-center bg-light border-start rounded-end p-4">
                    <img src="/educore-logo.png" alt="Thêm học viên mới" class="mb-3" style="max-width: 90px;">
                    <div class="text-center">
                        <h6 class="text-primary fw-bold mb-2">Thêm học viên mới</h6>
                        <p class="text-muted small mb-0">Tạo tài khoản học viên để quản lý thông tin, điểm danh, kết
                            quả học tập và phân lớp dễ dàng.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
