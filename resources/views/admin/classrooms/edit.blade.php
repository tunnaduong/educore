<x-layouts.dash-admin active="classrooms">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('classrooms.index') }}" wire:navigate
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>Quay lại
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-pencil-square mr-2"></i>Chỉnh sửa lớp học
            </h4>
        </div>

        <!-- Form Card Centered with Illustration -->
        <div class="card shadow-sm p-0">
            <div class="row g-0 align-items-stretch">
                <div class="col-md-7 p-4">
                    <form wire:submit="save">
                        <!-- Thông tin cơ bản -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">Thông tin lớp học</h5>
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên lớp học <span
                                        class="text-danger">*</span></label>
                                <input wire:model="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                    placeholder="Ví dụ: Lớp HSK3 - Khóa 1">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="level" class="form-label">Trình độ <span
                                        class="text-danger">*</span></label>
                                <select wire:model="level" class="form-control @error('level') is-invalid @enderror"
                                    id="level">
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
                            <div class="mb-3">
                                <label for="teacher_ids" class="form-label">Giảng viên <span
                                        class="text-danger">*</span></label>
                                <div class="dropdown">
                                    <button
                                        class="btn w-100 d-flex justify-content-between align-items-center border border-gray-300 bg-white text-start"
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                        style="height: 48px;">
                                        <span class="text-truncate">
                                            @if (count($teacher_ids))
                                                {{ collect($teachers)->whereIn('id', $teacher_ids)->pluck('name')->join(', ') }}
                                            @else
                                                Chọn giảng viên
                                            @endif
                                        </span>
                                        <span class="ms-2"><i class="bi bi-chevron-down"></i></span>
                                    </button>
                                    <ul class="dropdown-menu w-100" style="max-height: 300px; overflow-y: auto;">
                                        @foreach ($teachers as $teacher)
                                            <li>
                                                <label class="dropdown-item mb-0">
                                                    <input type="checkbox" value="{{ $teacher->id }}"
                                                        wire:model="teacher_ids">
                                                    {{ $teacher->name }}
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                    @error('teacher_ids')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Trạng thái <span
                                        class="text-danger">*</span></label>
                                <select wire:model="status" class="form-control @error('status') is-invalid @enderror"
                                    id="status">
                                    <option value="active">Đang hoạt động</option>
                                    <option value="completed">Đã kết thúc</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ngày học <span class="text-danger">*</span></label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ([
        'Monday' => 'Thứ 2',
        'Tuesday' => 'Thứ 3',
        'Wednesday' => 'Thứ 4',
        'Thursday' => 'Thứ 5',
        'Friday' => 'Thứ 6',
        'Saturday' => 'Thứ 7',
        'Sunday' => 'Chủ nhật',
    ] as $value => $label)
                                        <div class="form-check">
                                            <input wire:model="days" class="form-check-input" type="checkbox"
                                                value="{{ $value }}" id="day_{{ $value }}">
                                            <label class="form-check-label" for="day_{{ $value }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('days')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Thời gian học -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="startTime" class="form-label">Giờ bắt đầu <span
                                            class="text-danger">*</span></label>
                                    <input type="time" wire:model="startTime"
                                        class="form-control @error('startTime') is-invalid @enderror" id="startTime">
                                    @error('startTime')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="endTime" class="form-label">Giờ kết thúc <span
                                            class="text-danger">*</span></label>
                                    <input type="time" wire:model="endTime"
                                        class="form-control @error('endTime') is-invalid @enderror" id="endTime">
                                    @error('endTime')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Mô tả</label>
                                <textarea wire:model="notes" class="form-control @error('notes') is-invalid @enderror" id="notes" rows="3"
                                    placeholder="Nhập mô tả về lớp học..."></textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('classrooms.index') }}" wire:navigate class="btn btn-light">Hủy</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save mr-2"></i>Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
                <div
                    class="col-md-5 d-flex flex-column justify-content-center align-items-center bg-light border-start rounded-end p-4">
                    <img src="/educore-logo.png" alt="Chỉnh sửa lớp học" class="mb-3" style="max-width: 90px;">
                    <div class="text-center">
                        <h6 class="text-primary fw-bold mb-2">Chỉnh sửa lớp học</h6>
                        <p class="text-muted small mb-0">Cập nhật thông tin, thay đổi giảng viên, lịch học hoặc mô tả
                            cho lớp học một cách dễ dàng.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
