<div>
    <div class="p-4">
        <!-- Header -->
        <div class="text-center mb-4">
            <h4 class="text-primary fw-bold">
                <i class="bi bi-star-fill text-warning me-2"></i>
                Đánh giá chất lượng học tập
            </h4>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Lưu ý:</strong> Bạn cần hoàn thành đánh giá này để có thể tiếp tục sử dụng các tính năng của hệ thống.
            </div>
        </div>

        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($isSubmitted)
            <div class="alert alert-success">
                <i class="bi bi-check2-circle me-2"></i>
                <strong>Cảm ơn bạn!</strong> Bạn đã hoàn thành đánh giá cho học kỳ này.
                <div class="mt-3">
                    <a href="" class="btn btn-success">
                        <i class="bi bi-arrow-right me-2"></i>Tiếp tục sử dụng hệ thống
                    </a>
                </div>
            </div>
        @else
            <form wire:submit.prevent="saveEvaluation">
                <!-- Nhóm 1: Đánh giá về giáo viên -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-person-workspace me-2"></i>
                            Nhóm 1: Đánh giá về giáo viên
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach ($teacherQuestions as $key => $question)
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    {{ $key }}. {{ $question }}
                                </label>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Rất không đồng ý</span>
                                    <div class="btn-group" role="group">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <input type="radio" class="btn-check"
                                                   wire:model="teacher_ratings.{{ $key }}"
                                                   value="{{ $i }}"
                                                   id="teacher_{{ $key }}_{{ $i }}">
                                            <label class="btn btn-outline-primary" for="teacher_{{ $key }}_{{ $i }}">
                                                {{ $i }}
                                            </label>
                                        @endfor
                                    </div>
                                    <span class="text-muted small">Rất đồng ý</span>
                                </div>
                                @error("teacher_ratings.{$key}")
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Nhóm 2: Đánh giá về chất lượng khóa học -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-book me-2"></i>
                            Nhóm 2: Đánh giá về chất lượng khóa học
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach ($courseQuestions as $key => $question)
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    {{ $key }}. {{ $question }}
                                </label>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Rất không đồng ý</span>
                                    <div class="btn-group" role="group">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <input type="radio" class="btn-check"
                                                   wire:model="course_ratings.{{ $key }}"
                                                   value="{{ $i }}"
                                                   id="course_{{ $key }}_{{ $i }}">
                                            <label class="btn btn-outline-success" for="course_{{ $key }}_{{ $i }}">
                                                {{ $i }}
                                            </label>
                                        @endfor
                                    </div>
                                    <span class="text-muted small">Rất đồng ý</span>
                                </div>
                                @error("course_ratings.{$key}")
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Nhóm 3: Cảm nhận cá nhân -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="bi bi-emoji-smile me-2"></i>
                            Nhóm 3: Cảm nhận cá nhân
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                10. Bạn có hài lòng với trải nghiệm học kỳ này không?
                            </label>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Rất không hài lòng</span>
                                <div class="btn-group" role="group">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <input type="radio" class="btn-check"
                                               wire:model="personal_satisfaction"
                                               value="{{ $i }}"
                                               id="personal_{{ $i }}">
                                        <label class="btn btn-outline-warning" for="personal_{{ $i }}">
                                            {{ $i }}
                                        </label>
                                    @endfor
                                </div>
                                <span class="text-muted small">Rất hài lòng</span>
                            </div>
                            @error('personal_satisfaction')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="suggestions" class="form-label fw-bold">
                                11. Bạn có đề xuất gì để cải thiện chất lượng giảng dạy hoặc môn học không?
                            </label>
                            <textarea wire:model="suggestions" class="form-control" id="suggestions"
                                      rows="4" placeholder="Nhập đề xuất của bạn (không bắt buộc)..."></textarea>
                            @error('suggestions')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Lưu đánh giá
                    </button>
                    @php
                        $teacherAnswered = count($teacher_ratings) >= count($teacherQuestions);
                        $courseAnswered = count($course_ratings) >= count($courseQuestions);
                        $personalAnswered = !empty($personal_satisfaction);
                        $allAnswered = $teacherAnswered && $courseAnswered && $personalAnswered;
                    @endphp
                    <button type="button"
                            wire:click="submitEvaluation"
                            class="btn btn-success {{ !$allAnswered ? 'disabled' : '' }}"
                            {{ !$allAnswered ? 'disabled' : '' }}>
                        <i class="bi bi-send me-2"></i>Gửi đánh giá
                    </button>
                </div>

                @if (!$allAnswered)
                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Lưu ý:</strong> Bạn cần trả lời đầy đủ tất cả câu hỏi bắt buộc trước khi có thể gửi đánh giá.
                        @if (!$teacherAnswered)
                            <br><small class="text-muted">• Chưa trả lời đầy đủ câu hỏi về giáo viên</small>
                        @endif
                        @if (!$courseAnswered)
                            <br><small class="text-muted">• Chưa trả lời đầy đủ câu hỏi về khóa học</small>
                        @endif
                        @if (!$personalAnswered)
                            <br><small class="text-muted">• Chưa đánh giá mức độ hài lòng cá nhân</small>
                        @endif
                    </div>
                @endif
            </form>
        @endif
    </div>
</div>
