<x-layouts.dash-admin active="lessons">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('lessons.index') }}" class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>Quay lại
            </a>
            <h4 class="mb-0 text-success fs-4">
                <i class="bi bi-folder-symlink-fill mr-2"></i>Thêm bài học mới
            </h4>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <i class="bi bi-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Form Card Centered with Illustration -->
        <div class="card shadow-sm p-0">
            <div class="row g-0 align-items-stretch">
                <div class="col-md-7 p-4">
                    <form wire:submit.prevent="save">
                        <!-- Thông tin bài học -->
                        <div class="mb-4">
                            <h5 class="text-success mb-3">Thông tin bài học</h5>
                            <div class="mb-3">
                                <label for="number" class="form-label">Bài số <span
                                        class="text-danger">*</span></label>
                                <input wire:model="number" type="text"
                                    class="form-control @error('number') is-invalid @enderror" id="number"
                                    placeholder="Ví dụ: 1, 2, 3, 1a, 2b, 3c...">
                                @error('number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Tiêu đề <span
                                        class="text-danger">*</span></label>
                                <input wire:model="title" type="text"
                                    class="form-control @error('title') is-invalid @enderror" id="title"
                                    placeholder="Nhập tiêu đề bài học">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="classroom_id" class="form-label">Lớp học <span
                                        class="text-danger">*</span></label>
                                <select wire:model="classroom_id"
                                    class="form-control @error('classroom_id') is-invalid @enderror" id="classroom_id">
                                    <option value="">Chọn lớp học</option>
                                    @foreach ($classrooms as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                @error('classroom_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea wire:model="description" class="form-control @error('description') is-invalid @enderror" id="description"
                                    rows="3" placeholder="Nhập mô tả về bài học..."></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="video" class="form-label">Link video (YouTube, Google Drive...)</label>
                                <input wire:model="video" type="text"
                                    class="form-control @error('video') is-invalid @enderror" id="video"
                                    placeholder="Dán link video bài học">
                                <small class="text-danger d-block mb-2">* Hãy nhớ để chế độ chia sẻ công khai khi chèn
                                    link từ Google Drive</small>
                                @error('video')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="attachment" class="form-label">Tài liệu/Slide (PDF, Word...)</label>
                                <input wire:model="attachment" type="file"
                                    class="form-control @error('attachment') is-invalid @enderror" id="attachment">
                                @error('attachment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('lessons.index') }}" class="btn btn-light">Hủy</a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-folder-plus mr-2"></i>Lưu bài học
                            </button>
                        </div>
                    </form>
                </div>
                <div
                    class="col-md-5 d-flex flex-column justify-content-center align-items-center bg-light border-start rounded-end p-4">
                    <img src="/educore-logo.png" alt="Thêm bài học mới" class="mb-3" style="max-width: 90px;">
                    <div class="text-center">
                        <h6 class="text-success fw-bold mb-2">Thêm bài học mới</h6>
                        <p class="text-muted small mb-0">Lưu trữ bài học, tài liệu, video, slide để học viên có thể tra
                            cứu lại bất kỳ lúc nào.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
