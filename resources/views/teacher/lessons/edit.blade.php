<x-layouts.dash-teacher active="lessons">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-warning fs-4">
                        <i class="bi bi-pencil-square me-2"></i>Chỉnh sửa bài học
                    </h4>
                    <p class="text-muted mb-0">Cập nhật thông tin bài học và tài nguyên</p>
                </div>
                <div>
                    <a href="{{ route('teacher.lessons.index') }}" wire:navigate class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-file-earmark-text me-2"></i>Thông tin bài học
                </h6>
            </div>
            <div class="card-body">
                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif
                <form wire:submit="save">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="title" class="form-label">Tiêu đề bài học <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                id="title" wire:model="title" placeholder="Nhập tiêu đề bài học...">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="number" class="form-label">Số bài học</label>
                            <input type="text" class="form-control @error('number') is-invalid @enderror" 
                                id="number" wire:model="number" placeholder="VD: Bài 1, Chương 2...">
                            @error('number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="classroom_id" class="form-label">Lớp học <span class="text-danger">*</span></label>
                            <select class="form-select @error('classroom_id') is-invalid @enderror" 
                                id="classroom_id" wire:model="classroom_id">
                                <option value="">Chọn lớp học...</option>
                                @foreach($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                @endforeach
                            </select>
                            @error('classroom_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" wire:model="description" rows="4" 
                                placeholder="Mô tả chi tiết về bài học..."></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="video" class="form-label">Link video</label>
                            <input type="url" class="form-control @error('video') is-invalid @enderror" 
                                id="video" wire:model="video" placeholder="https://youtube.com/...">
                            @error('video')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <strong>Hỗ trợ:</strong><br>
                                • YouTube: https://youtube.com/watch?v=VIDEO_ID hoặc https://youtu.be/VIDEO_ID<br>
                                • Google Drive: https://drive.google.com/file/d/FILE_ID/view<br>
                                • Vimeo và các nền tảng video khác
                            </small>
                        </div>
                        <div class="col-md-6">
                            <label for="attachment" class="form-label">Tài liệu đính kèm</label>
                            <input type="file" class="form-control @error('attachment') is-invalid @enderror" 
                                id="attachment" wire:model="attachment" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt">
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Hỗ trợ: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT (Tối đa 10MB)</small>
                            
                            @if($currentAttachment)
                                <div class="mt-2">
                                    <small class="text-info">
                                        <i class="bi bi-file-earmark me-1"></i>
                                        File hiện tại: <a href="{{ asset('storage/' . $currentAttachment) }}" target="_blank">{{ basename($currentAttachment) }}</a>
                                    </small>
                                </div>
                            @endif
                            
                            @if($attachment)
                                <div class="mt-2">
                                    <div class="alert alert-info">
                                        <i class="bi bi-file-earmark me-2"></i>
                                        <strong>File mới đã chọn:</strong> {{ $attachment->getClientOriginalName() }}
                                        <br>
                                        <small>Kích thước: {{ number_format($attachment->getSize() / 1024, 2) }} KB</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('teacher.lessons.index') }}" wire:navigate class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i>Huỷ
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-circle me-1"></i>Cập nhật bài học
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.dash-teacher> 