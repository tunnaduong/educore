<x-layouts.dash-admin active="lessons">
    <div class="container-fluid">
        <div class="mb-4">
            <a href="{{ route('lessons.index') }}" wire:navigate class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
            <h4 class="mb-0 text-success fs-4">
                <i class="bi bi-folder-symlink-fill me-2"></i>Sửa bài học
            </h4>
        </div>
        <div class="card shadow-sm p-0">
            <div class="row g-0 align-items-stretch">
                <div class="col-md-7 p-4">
                    <form wire:submit.prevent="update">
                        <div class="mb-4">
                            <h5 class="text-success mb-3">Thông tin bài học</h5>
                            <div class="mb-3">
                                <label for="number" class="form-label">Bài số <span class="text-danger">*</span></label>
                                <input wire:model="number" type="text" class="form-control @error('number') is-invalid @enderror" id="number" placeholder="Ví dụ: 1, 2, 3...">
                                @error('number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                                <input wire:model="title" type="text" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Nhập tiêu đề bài học">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="classroom_id" class="form-label">Lớp học <span class="text-danger">*</span></label>
                                <select wire:model="classroom_id" class="form-select @error('classroom_id') is-invalid @enderror" id="classroom_id">
                                    <option value="">Chọn lớp học</option>
                                    @foreach($classrooms as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                @error('classroom_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea wire:model="description" class="form-control @error('description') is-invalid @enderror" id="description" rows="3" placeholder="Nhập mô tả về bài học..."></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="video" class="form-label">Link video (YouTube, Google Drive...)</label>
                                <input wire:model="video" type="text" class="form-control @error('video') is-invalid @enderror" id="video" placeholder="Dán link video bài học">
                                @error('video')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="attachment" class="form-label">Tài liệu/Slide (PDF, Word...)</label>
                                <input wire:model="attachment" type="file" class="form-control @error('attachment') is-invalid @enderror" id="attachment">
                                @if($oldAttachment)
                                    <div class="mt-2"><a href="{{ asset('storage/' . $oldAttachment) }}" target="_blank">Tài liệu hiện tại</a></div>
                                @endif
                                @error('attachment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('lessons.index') }}" wire:navigate class="btn btn-light">Hủy</a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save me-2"></i>Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-5 d-flex flex-column justify-content-center align-items-center bg-light border-start rounded-end p-4">
                    <img src="/educore-logo.png" alt="Sửa bài học" class="mb-3" style="max-width: 90px;">
                    <div class="text-center">
                        <h6 class="text-success fw-bold mb-2">Sửa bài học</h6>
                        <p class="text-muted small mb-0">Cập nhật thông tin bài học, tài liệu, video, slide cho học viên.</p>
                    </div>
                </div>
            </div>
        </div>
</div>
</x-layouts.dash-admin>
