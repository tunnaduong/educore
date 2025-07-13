<x-layouts.dash-admin active="lessons">
    <div class="container py-4">
        <a href="{{ route('lessons.index') }}" wire:navigate class="text-decoration-none text-secondary d-inline-block mb-3">
            <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
        </a>
        <div class="card shadow-lg border-0">
            <div class="card-body p-4">
                <div class="row g-4 align-items-start">
                    <div class="col-md-8">
                        <h3 class="mb-3 text-success fw-bold">
                            <i class="bi bi-journal-richtext me-2"></i>{{ $lesson->title }}
                        </h3>
                        <div class="mb-2">
                            <span class="badge bg-info me-2"><i class="bi bi-hash"></i> Số bài: {{ $lesson->number }}</span>
                            <span class="badge bg-secondary"><i class="bi bi-calendar-event"></i> {{ $lesson->created_at?->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="mb-3">
                            <strong class="text-muted">Mô tả:</strong>
                            <div class="border rounded p-2 bg-light mt-1">{{ $lesson->description ?: 'Không có mô tả.' }}</div>
                        </div>
                        @if($lesson->content)
                        <div class="mb-3">
                            <strong class="text-muted">Nội dung chi tiết:</strong>
                            <div class="border rounded p-3 bg-white mt-1">{!! nl2br(e($lesson->content)) !!}</div>
                        </div>
                        @endif
                        <div class="d-flex gap-2 mt-4">
                            <a href="{{ route('lessons.edit', $lesson->id) }}" wire:navigate class="btn btn-warning"><i class="bi bi-pencil me-1"></i>Sửa</a>
                            <button type="button" class="btn btn-danger" wire:click="$emitUp('confirmDelete', {{ $lesson->id }}, '{{ addslashes($lesson->title) }}')"><i class="bi bi-trash me-1"></i>Xóa</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <strong class="text-muted">Video bài học:</strong><br>
                            @if($lesson->video)
                                @php
                                    $isYoutube = Str::contains($lesson->video, ['youtube.com', 'youtu.be']);
                                @endphp
                                @if($isYoutube)
                                    <div class="ratio ratio-16x9 rounded overflow-hidden mb-2">
                                        <iframe src="https://www.youtube.com/embed/{{ Str::afterLast($lesson->video, 'v=') }}" frameborder="0" allowfullscreen></iframe>
                                    </div>
                                @else
                                    <a href="{{ $lesson->video }}" target="_blank" class="btn btn-outline-primary"><i class="bi bi-play-circle"></i> Xem video</a>
                                @endif
                            @else
                                <span class="text-muted">Không có video</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <strong class="text-muted">Tài liệu/Slide:</strong><br>
                            @if($lesson->attachment)
                                <a href="{{ asset('storage/' . $lesson->attachment) }}" target="_blank" class="btn btn-outline-success"><i class="bi bi-download"></i> Tải tài liệu</a>
                            @else
                                <span class="text-muted">Không có tài liệu</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
