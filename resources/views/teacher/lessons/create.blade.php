<x-layouts.dash-teacher active="lessons">
    @include('components.language')
    @php
        $t = function ($vi, $en, $zh) {
            $l = app()->getLocale();
            return $l === 'vi' ? $vi : ($l === 'zh' ? $zh : $en);
        };
    @endphp
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-success fs-4">
                        <i class="bi bi-plus-circle mr-2"></i>{{ $t('Thêm bài học mới', 'Add new lesson', '新增课程') }}
                    </h4>
                    <p class="text-muted mb-0">{{ $t('Tạo bài học và tài nguyên mới cho lớp học', 'Create new lesson and resources for classes', '为班级创建新的课程与资源') }}</p>
                </div>
                <div>
                    <a href="{{ route('teacher.lessons.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left mr-1"></i>{{ $t('Quay lại', 'Back', '返回') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-file-earmark-plus mr-2"></i>{{ $t('Thông tin bài học', 'Lesson information', '课程信息') }}
                </h6>
            </div>
            <div class="card-body">
                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif
                <form wire:submit="save">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="title" class="form-label">{{ $t('Tiêu đề bài học', 'Lesson title', '课程标题') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                id="title" wire:model="title" placeholder="{{ $t('Nhập tiêu đề bài học...', 'Enter lesson title...', '输入课程标题...') }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="number" class="form-label">{{ $t('Số bài học', 'Lesson number', '课程编号') }}</label>
                            <input type="text" class="form-control @error('number') is-invalid @enderror"
                                id="number" wire:model="number" placeholder="{{ $t('VD: Bài 1, Chương 2...', 'E.g., Lesson 1, Chapter 2...', '例如：第1课，第2章...') }}">
                            @error('number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="classroom_id" class="form-label">{{ $t('Lớp học', 'Classroom', '班级') }} <span
                                    class="text-danger">*</span></label>
                            <select class="form-control @error('classroom_id') is-invalid @enderror" id="classroom_id"
                                wire:model="classroom_id">
                                <option value="">{{ $t('Chọn lớp học...', 'Select a class...', '请选择班级...') }}</option>
                                @foreach ($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                @endforeach
                            </select>
                            @error('classroom_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if ($classrooms->isEmpty())
                                <div class="alert alert-warning mt-2">
                                    <i class="bi bi-exclamation-triangle mr-2"></i>
                                    {{ $t('Không có lớp học nào được gán cho bạn. Vui lòng liên hệ admin để được gán vào lớp học.', 'No classes are assigned to you. Please contact admin to be assigned.', '当前没有为您分配班级。请联系管理员进行分配。') }}
                                </div>
                            @else
                                <small class="form-text text-muted">{{ $t('Tìm thấy', 'Found', '已找到') }} {{ $classrooms->count() }} {{ $t('lớp học', 'classes', '个班级') }}</small>
                            @endif
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">{{ $t('Mô tả', 'Description', '描述') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" wire:model="description"
                                rows="4" placeholder="{{ $t('Mô tả chi tiết về bài học...', 'Detailed description of the lesson...', '课程的详细描述...') }}"></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="video" class="form-label">{{ $t('Link video', 'Video link', '视频链接') }}</label>
                            <input type="url" class="form-control @error('video') is-invalid @enderror"
                                id="video" wire:model="video" placeholder="https://youtube.com/...">
                            @error('video')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <strong>{{ $t('Hỗ trợ', 'Supported', '支持') }}:</strong><br>
                                • YouTube: https://youtube.com/watch?v=VIDEO_ID {{ $t('hoặc', 'or', '或') }} https://youtu.be/VIDEO_ID<br>
                                • Google Drive: https://drive.google.com/file/d/FILE_ID/view<br>
                                • {{ $t('Vimeo và các nền tảng video khác', 'Vimeo and other video platforms', 'Vimeo 及其他视频平台') }}
                            </small>
                        </div>
                        <div class="col-md-6">
                            <label for="attachment" class="form-label">{{ $t('Tài liệu đính kèm', 'Attachment', '附件') }}</label>
                            <input type="file" class="form-control @error('attachment') is-invalid @enderror"
                                id="attachment" wire:model="attachment"
                                accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt">
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">{{ $t('Hỗ trợ', 'Supported', '支持') }}: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT ({{ $t('Tối đa', 'Max', '最大') }} 10MB)</small>

                            @if ($attachment)
                                <div class="mt-2">
                                    <div class="alert alert-info">
                                        <i class="bi bi-file-earmark mr-2"></i>
                                        <strong>{{ $t('File đã chọn', 'Selected file', '已选择文件') }}:</strong> {{ $attachment->getClientOriginalName() }}
                                        <br>
                                        <small>{{ $t('Kích thước', 'Size', '大小') }}: {{ number_format($attachment->getSize() / 1024, 2) }}
                                            KB</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('teacher.lessons.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle mr-1"></i>{{ $t('Huỷ', 'Cancel', '取消') }}
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle mr-1"></i>{{ $t('Lưu bài học', 'Save lesson', '保存课程') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.dash-teacher>
