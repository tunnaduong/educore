<x-layouts.dash active="assignments">
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('dashboard') }}" wire:navigate
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-journal-text me-2"></i>Tạo bài tập mới
            </h4>
        </div>

        <!-- Form Card -->
        <div class="card shadow-sm max-w-xl">
            <div class="card-body">
                @if (session()->has('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form wire:submit.prevent="createAssignment">
                    <div class="mb-4">
                        <h5 class="text-primary mb-3">Thông tin bài tập</h5>
                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                            <input wire:model.defer="title" type="text" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Nhập tiêu đề bài tập">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="class_id" class="form-label">Lớp học <span class="text-danger">*</span></label>
                            <select wire:model.defer="class_id" class="form-select @error('class_id') is-invalid @enderror" id="class_id">
                                <option value="">Chọn lớp</option>
                                @foreach ($classrooms as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->level }})</option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="deadline" class="form-label">Hạn nộp <span class="text-danger">*</span></label>
                            <input wire:model.defer="deadline" type="datetime-local" class="form-control @error('deadline') is-invalid @enderror" id="deadline">
                            @error('deadline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Loại bài tập <span class="text-danger">*</span></label>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach ($allTypes as $key => $label)
                                    <div class="form-check me-3 mb-2">
                                        <input wire:model.defer="types" class="form-check-input" type="checkbox" value="{{ $key }}" id="type_{{ $key }}">
                                        <label class="form-check-label" for="type_{{ $key }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('types')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="attachment" class="form-label">Tệp đính kèm (tùy chọn, tối đa 10MB)</label>
                            <input wire:model="attachment" type="file" class="form-control @error('attachment') is-invalid @enderror" id="attachment">
                            @if ($attachment)
                                <div class="mt-1 text-success">Tệp: {{ $attachment->getClientOriginalName() }}</div>
                            @endif
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="video" class="form-label">Video (tùy chọn, mp4/avi/mpeg/mov, tối đa 50MB)</label>
                            <input wire:model="video" type="file" accept="video/*" class="form-control @error('video') is-invalid @enderror" id="video">
                            @if ($video)
                                <div class="mt-1 text-success">Video: {{ $video->getClientOriginalName() }}</div>
                            @endif
                            @error('video')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea wire:model.defer="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="3" placeholder="Nhập mô tả về bài tập..."></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('dashboard') }}" wire:navigate class="btn btn-light">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-journal-text me-2"></i>Tạo bài tập
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.dash>
