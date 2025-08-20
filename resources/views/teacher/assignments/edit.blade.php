<x-layouts.dash-teacher active="assignments">
    <div class="container py-4">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('teacher.assignments.index') }}" class="text-decoration-none text-secondary">
                <i class="bi bi-arrow-left mr-1"></i>Quay lại
            </a>
            <h4 class="mt-2 text-primary fs-4"><i class="bi bi-journal-text mr-2"></i>Chỉnh sửa bài tập</h4>
        </div>

        <!-- Form -->
        <div class="card shadow-sm">
            <div class="card-body">
                @if (session()->has('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form wire:submit.prevent="updateAssignment">
                    <!-- Bài tập -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label for="title" class="form-label fw-semibold">Tiêu đề *</label>
                            <input wire:model.defer="title" type="text"
                                class="form-control @error('title') is-invalid @enderror" id="title"
                                placeholder="VD: Bài luyện viết Hán tự - Bài 3">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="class_id" class="form-label fw-semibold">Lớp học *</label>
                            <select wire:model.defer="class_id"
                                class="form-control @error('class_id') is-invalid @enderror" id="class_id">
                                <option value="">Chọn lớp</option>
                                @foreach ($classrooms as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->level }})
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Hạn nộp & điểm -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="deadline" class="form-label fw-semibold">Hạn nộp *</label>
                            <input wire:model.defer="deadline" type="datetime-local"
                                class="form-control @error('deadline') is-invalid @enderror" id="deadline">
                            @error('deadline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="max_score" class="form-label fw-semibold">Điểm tối đa (tuỳ chọn)</label>
                            <input wire:model.defer="max_score" type="number" class="form-control" id="max_score"
                                placeholder="VD: 10" min="0" max="10" step="0.1"
                                oninput="if(this.value > 10) this.value = 10; if(this.value < 0) this.value = 0;"
                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46 || event.charCode === 8 || event.charCode === 9">
                        </div>
                    </div>

                    <!-- Loại bài tập -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Loại bài tập *</label>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach ($allTypes as $key => $label)
                                <div class="form-check">
                                    <input wire:model.defer="types" class="form-check-input" type="checkbox"
                                        value="{{ $key }}" id="type_{{ $key }}">
                                    <label class="form-check-label"
                                        for="type_{{ $key }}">{{ $label }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('types')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- File & mô tả -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="attachment" class="form-label">Tệp đính kèm</label>
                            @if ($old_attachment_path)
                                <div class="small text-success mt-1">
                                    <a href="{{ asset('storage/' . $old_attachment_path) }}" target="_blank"
                                        class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-file-earmark-arrow-down"></i> File hiện tại
                                    </a>
                                </div>
                            @endif
                            <input wire:model="attachment" type="file"
                                class="form-control @error('attachment') is-invalid @enderror" id="attachment">
                            @if ($attachment)
                                <div class="small text-success mt-1">Tệp: {{ $attachment->getClientOriginalName() }}
                                </div>
                            @endif
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="video" class="form-label">Video (tối đa 100MB)</label>
                            @if ($old_video_path)
                                <div class="small text-success mt-1">
                                    <video width="240" height="135" controls>
                                        <source src="{{ asset('storage/' . $old_video_path) }}" type="video/mp4">
                                        Trình duyệt không hỗ trợ video.
                                    </video>
                                </div>
                            @endif
                            <input wire:model="video" type="file" accept="video/*"
                                class="form-control @error('video') is-invalid @enderror" id="video">
                            @if ($video)
                                <div class="small text-success mt-1">Video: {{ $video->getClientOriginalName() }}</div>
                            @endif
                            @error('video')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Mô tả -->
                    <div class="mb-4">
                        <label for="description" class="form-label">Hướng dẫn / mô tả bài tập</label>
                        <textarea wire:model.defer="description" rows="4" class="form-control @error('description') is-invalid @enderror"
                            id="description" placeholder="VD: Viết 10 câu sử dụng từ vựng của bài 3, ghi âm phần đọc...">{{ $description }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('teacher.assignments.index') }}" class="btn btn-outline-secondary">Hủy</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send mr-1"></i> Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.dash-teacher>
