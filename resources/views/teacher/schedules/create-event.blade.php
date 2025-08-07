<div>
    @if ($showModal)
        <div class="modal fade show" style="display: block;" tabindex="-1" aria-labelledby="createEventModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createEventModalLabel">
                            <i class="bi bi-plus-circle mr-2"></i>Thêm sự kiện mới
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="save">
                        <div class="modal-body">
                            @if (session()->has('message'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if (session()->has('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="eventType" class="form-label">Loại sự kiện <span
                                                class="text-danger">*</span></label>
                                        <select wire:model.live="eventType"
                                            class="form-control @error('eventType') is-invalid @enderror"
                                            id="eventType">
                                            <option value="lesson">Bài học</option>
                                            <option value="assignment">Bài tập</option>
                                            <option value="quiz">Kiểm tra</option>
                                        </select>
                                        @error('eventType')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="classroomId" class="form-label">Lớp học <span
                                                class="text-danger">*</span></label>
                                        <select wire:model="classroomId"
                                            class="form-control @error('classroomId') is-invalid @enderror"
                                            id="classroomId">
                                            <option value="">Chọn lớp học</option>
                                            @foreach ($this->classrooms as $classroom)
                                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('classroomId')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label">Tiêu đề <span
                                        class="text-danger">*</span></label>
                                <input type="text" wire:model="title"
                                    class="form-control @error('title') is-invalid @enderror" id="title"
                                    placeholder="Nhập tiêu đề sự kiện">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea wire:model="description" class="form-control @error('description') is-invalid @enderror" id="description"
                                    rows="3" placeholder="Nhập mô tả sự kiện"></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if ($eventType === 'lesson' || $eventType === 'quiz')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="startTime" class="form-label">Thời gian bắt đầu <span
                                                    class="text-danger">*</span></label>
                                            <input type="datetimr-local" wire:model="startTime"
                                                class="form-control @error('startTime') is-invalid @enderror"
                                                id="startTime">
                                            @error('startTime')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="endTime" class="form-label">Thời gian kết thúc <span
                                                    class="text-danger">*</span></label>
                                            <input type="datetimr-local" wire:model="endTime"
                                                class="form-control @error('endTime') is-invalid @enderror"
                                                id="endTime">
                                            @error('endTime')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                @if ($eventType === 'lesson')
                                    <div class="mb-3">
                                        <label for="location" class="form-label">Địa điểm</label>
                                        <input type="text" wire:model="location"
                                            class="form-control @error('location') is-invalid @enderror" id="location"
                                            placeholder="Nhập địa điểm">
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                                @if ($eventType === 'quiz')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="duration" class="form-label">Thời gian làm bài (phút)
                                                    <span class="text-danger">*</span></label>
                                                <input type="number" wire:model="duration"
                                                    class="form-control @error('duration') is-invalid @enderror"
                                                    id="duration" min="1"
                                                    placeholder="Nhập thời gian làm bài">
                                                @error('duration')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="maxScore" class="form-label">Điểm tối đa</label>
                                                <input type="number" wire:model="maxScore"
                                                    class="form-control @error('maxScore') is-invalid @enderror"
                                                    id="maxScore" min="0" max="10" step="0.1"
                                                    placeholder="Nhập điểm tối đa"
                                                    oninput="if(this.value > 10) this.value = 10; if(this.value < 0) this.value = 0;"
                                                    onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46 || event.charCode === 8 || event.charCode === 9">
                                                @error('maxScore')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            @if ($eventType === 'assignment')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="dueDate" class="form-label">Hạn nộp <span
                                                    class="text-danger">*</span></label>
                                            <input type="datetimr-local" wire:model="dueDate"
                                                class="form-control @error('dueDate') is-invalid @enderror"
                                                id="dueDate">
                                            @error('dueDate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="maxScore" class="form-label">Điểm tối đa</label>
                                            <input type="number" wire:model="maxScore"
                                                class="form-control @error('maxScore') is-invalid @enderror"
                                                id="maxScore" min="0" max="10" step="0.1"
                                                placeholder="Nhập điểm tối đa"
                                                oninput="if(this.value > 10) this.value = 10; if(this.value < 0) this.value = 0;"
                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46 || event.charCode === 8 || event.charCode === 9">
                                            @error('maxScore')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($eventType === 'lesson')
                                <div class="mb-3">
                                    <label for="maxScore" class="form-label">Điểm tối đa</label>
                                    <input type="number" wire:model="maxScore"
                                        class="form-control @error('maxScore') is-invalid @enderror" id="maxScore"
                                        min="0" max="10" step="0.1" placeholder="Nhập điểm tối đa"
                                        oninput="if(this.value > 10) this.value = 10; if(this.value < 0) this.value = 0;"
                                        onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46 || event.charCode === 8 || event.charCode === 9">
                                    @error('maxScore')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">Hủy</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle mr-2"></i>Tạo sự kiện
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
