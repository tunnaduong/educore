<x-layouts.dash-admin active="schedules">
    @include('components.language')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 fs-4">Chỉnh sửa lịch học</h4>
                            <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left mr-2"></i>Quay lại
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="text-primary">{{ $classroom->name }}</h5>
                            <p class="text-muted mb-0">
                                <i class="bi bi-person-circle mr-2"></i>
                                Giáo viên:
                                @if ($classroom->teachers->count())
                                    {{ $classroom->teachers->pluck('name')->join(', ') }}
                                @else
                                    Chưa phân công
                                @endif
                            </p>
                        </div>

                        <form wire:submit="save">
                            <!-- Chọn ngày trong tuần -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Chọn ngày học trong tuần <span
                                        class="text-danger">*</span></label>
                                <div class="row g-2">
                                    @foreach ($availableDays as $dayKey => $dayName)
                                        <div class="col-md-3 col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    wire:model="selectedDays" value="{{ $dayKey }}"
                                                    id="day_{{ $dayKey }}">
                                                <label class="form-check-label" for="day_{{ $dayKey }}">
                                                    {{ $dayName }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('selectedDays')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Thời gian học -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="startTime" class="form-label fw-bold">Giờ bắt đầu <span
                                            class="text-danger">*</span></label>
                                    <input type="time" wire:model="startTime"
                                        class="form-control @error('startTime') is-invalid @enderror" id="startTime">
                                    @error('startTime')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="endTime" class="form-label fw-bold">Giờ kết thúc <span
                                            class="text-danger">*</span></label>
                                    <input type="time" wire:model="endTime"
                                        class="form-control @error('endTime') is-invalid @enderror" id="endTime">
                                    @error('endTime')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Ghi chú -->
                            <div class="mb-4">
                                <label for="notes" class="form-label fw-bold">Ghi chú</label>
                                <textarea wire:model="notes" class="form-control @error('notes') is-invalid @enderror" id="notes" rows="3"
                                    placeholder="Nhập ghi chú về lịch học (nếu có)..."></textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Preview lịch học -->
                            @if ($selectedDays && $startTime && $endTime)
                                <div class="alert alert-info mb-4">
                                    <h6 class="alert-heading">
                                        <i class="bi bi-calendar-check mr-2"></i>Xem trước lịch học
                                    </h6>
                                    <p class="mb-0">
                                        <strong>Ngày học:</strong>
                                        @foreach ($selectedDays as $day)
                                            <span class="badge bg-primary mr-1">{{ $availableDays[$day] }}</span>
                                        @endforeach
                                    </p>
                                    <p class="mb-0">
                                        <strong>Thời gian:</strong> {{ $startTime }} - {{ $endTime }}
                                    </p>
                                </div>
                            @endif

                            <!-- Nút thao tác -->
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('schedules.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle mr-2"></i>Hủy
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle mr-2"></i>Lưu thay đổi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
