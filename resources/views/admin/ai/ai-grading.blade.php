<x-layouts.dash-admin active="ai">
    <div class="container-fluid">
        <style>
            /* Loading Button Styles */
            .loading-overlay {
                display: flex;
                align-items: center;
                justify-content: center;
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(13, 110, 253, 0.9);
                border-radius: 0.375rem;
                z-index: 10;
            }

            .loading-text {
                color: white;
                font-weight: 500;
            }

            /* AI Loading Animation */
            .ai-loading-animation {
                position: relative;
                min-height: 300px;
            }

            .ai-brain {
                animation: pulse 2s infinite;
            }

            .ai-particles {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 200px;
                height: 200px;
            }

            .particle {
                position: absolute;
                width: 8px;
                height: 8px;
                background: linear-gradient(45deg, #007bff, #6610f2);
                border-radius: 50%;
                animation: particle-float 3s infinite ease-in-out;
            }

            .particle:nth-child(1) {
                top: 20%;
                left: 20%;
                animation-delay: 0s;
            }

            .particle:nth-child(2) {
                top: 20%;
                right: 20%;
                animation-delay: 0.5s;
            }

            .particle:nth-child(3) {
                bottom: 20%;
                left: 20%;
                animation-delay: 1s;
            }

            .particle:nth-child(4) {
                bottom: 20%;
                right: 20%;
                animation-delay: 1.5s;
            }

            .particle:nth-child(5) {
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                animation-delay: 2s;
            }

            @keyframes pulse {

                0%,
                100% {
                    transform: scale(1);
                    opacity: 1;
                }

                50% {
                    transform: scale(1.1);
                    opacity: 0.8;
                }
            }

            @keyframes particle-float {

                0%,
                100% {
                    transform: translateY(0) scale(1);
                    opacity: 0.7;
                }

                50% {
                    transform: translateY(-20px) scale(1.2);
                    opacity: 1;
                }
            }

            /* Progress bar animation */
            .progress-bar-animated {
                background-image: linear-gradient(45deg,
                        rgba(255, 255, 255, .15) 25%,
                        transparent 25%,
                        transparent 50%,
                        rgba(255, 255, 255, .15) 50%,
                        rgba(255, 255, 255, .15) 75%,
                        transparent 75%,
                        transparent);
                background-size: 1rem 1rem;
                animation: progress-bar-stripes 1s linear infinite;
            }

            @keyframes progress-bar-stripes {
                0% {
                    background-position: 1rem 0;
                }

                100% {
                    background-position: 0 0;
                }
            }

            /* Grading specific styles */
            .grading-loading {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 15px;
                padding: 2rem;
                color: white;
            }

            .grading-loading .ai-brain i {
                color: white;
            }
        </style>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fas fa-robot text-primary"></i>
                            Chấm bài Tiếng Trung bằng AI
                        </h4>
                        <p class="text-muted">Sử dụng AI để sửa lỗi ngữ pháp, chấm điểm và phân tích bài nộp</p>
                    </div>
                    <div class="card-body">
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if ($isProcessing)
                            <div class="grading-loading text-center py-5">
                                <div class="ai-loading-animation">
                                    <div class="ai-brain">
                                        <i class="fas fa-brain fa-4x mb-4"></i>
                                    </div>
                                    <div class="ai-particles">
                                        <div class="particle"></div>
                                        <div class="particle"></div>
                                        <div class="particle"></div>
                                        <div class="particle"></div>
                                        <div class="particle"></div>
                                    </div>
                                    <h3 class="mb-3">AI đang chấm bài...</h3>
                                    <p class="mb-4">Đang phân tích ngữ pháp, chấm điểm và đưa ra gợi ý cải thiện</p>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated"
                                            style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        @elseif ($submission)
                            <div class="row">
                                <!-- Bài nộp của học sinh -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">Bài nộp của học sinh</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <strong>Nội dung:</strong>
                                                <div class="border rounded p-3 mt-2 bg-light">
                                                    {!! nl2br(e($submission->content)) !!}
                                                </div>
                                            </div>

                                            @if ($submission->hasAICorrection())
                                                <div class="mb-3">
                                                    <strong>Nội dung đã sửa bởi AI:</strong>
                                                    <div class="border rounded p-3 mt-2 bg-success bg-opacity-10">
                                                        {!! nl2br(e($submission->ai_corrected_content)) !!}
                                                    </div>
                                                </div>

                                                @if (!empty($submission->ai_errors_found))
                                                    <div class="mb-3">
                                                        <strong>Lỗi đã sửa:</strong>
                                                        <ul class="list-group mt-2">
                                                            @php
                                                                $errorsFound = $submission->ai_errors_found;
                                                                if (is_string($errorsFound)) {
                                                                    $errorsFound =
                                                                        json_decode($errorsFound, true) ?: [];
                                                                }
                                                            @endphp
                                                            @if (!empty($errorsFound))
                                                                <div class="mb-3">
                                                                    <strong>Lỗi đã sửa:</strong>
                                                                    <ul class="list-group mt-2">
                                                                        @foreach ($errorsFound as $error)
                                                                            <li class="list-group-item">
                                                                                <strong>{{ $error['original'] }}</strong>
                                                                                →
                                                                                <span
                                                                                    class="text-success">{{ $error['corrected'] }}</span>
                                                                                <br><small
                                                                                    class="text-muted">{{ $error['explanation'] }}</small>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            @endif
                                                @endif

                                                <div class="d-flex gap-2">
                                                    <button wire:click="correctGrammarWithAI"
                                                        class="btn btn-outline-primary btn-sm position-relative"
                                                        wire:loading.attr="disabled" wire:loading.class="disabled">
                                                        <div wire:loading.remove>
                                                            <i class="fas fa-magic"></i>
                                                            Sửa lỗi ngữ pháp
                                                        </div>
                                                        <div wire:loading class="loading-overlay">
                                                            <div class="spinner-border spinner-border-sm text-light me-2"
                                                                role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                            <span class="loading-text">Đang xử lý...</span>
                                                        </div>
                                                    </button>
                                                </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Kết quả AI -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title">Kết quả AI</h5>
                                        </div>
                                        <div class="card-body">
                                            @if ($submission->hasAIGrading())
                                                <div class="mb-3">
                                                    <strong>Điểm AI:</strong>
                                                    <span
                                                        class="badge bg-primary fs-6">{{ $submission->ai_score }}/10</span>
                                                </div>

                                                <div class="mb-3">
                                                    <strong>Nhận xét AI:</strong>
                                                    <div class="border rounded p-3 mt-2 bg-info bg-opacity-10">
                                                        {!! nl2br(e($submission->ai_feedback)) !!}
                                                    </div>
                                                </div>

                                                @if (!empty($submission->ai_criteria_scores))
                                                    <div class="mb-3">
                                                        <strong>Điểm chi tiết:</strong>
                                                        <div class="row">
                                                            @php
                                                                $criteriaScores = $submission->ai_criteria_scores;
                                                                // Xử lý trường hợp dữ liệu cũ có thể là string
                                                                if (is_string($criteriaScores)) {
                                                                    $criteriaScores =
                                                                        json_decode($criteriaScores, true) ?: [];
                                                                }
                                                            @endphp
                                                            @if (!empty($criteriaScores))
                                                                <div class="mb-3">
                                                                    <strong>Điểm chi tiết:</strong>
                                                                    <div class="row">
                                                                        @foreach ($criteriaScores as $criteria => $score)
                                                                            <div class="col-6">
                                                                                <small
                                                                                    class="text-muted">{{ ucfirst($criteria) }}:</small>
                                                                                <div class="progress"
                                                                                    style="height: 20px;">
                                                                                    <div class="progress-bar"
                                                                                        style="width: {{ ($score / 10) * 100 }}%">
                                                                                        {{ $score }}/10
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if (!empty($submission->ai_strengths))
                                                                <div class="mb-3">
                                                                    <strong>Điểm mạnh:</strong>
                                                                    <ul class="list-group list-group-flush mt-2">
                                                                        @php
                                                                            $strengths = $submission->ai_strengths;
                                                                            if (is_string($strengths)) {
                                                                                $strengths =
                                                                                    json_decode($strengths, true) ?: [];
                                                                            }
                                                                        @endphp
                                                                        @if (!empty($strengths))
                                                                            <div class="mb-3">
                                                                                <strong>Điểm mạnh:</strong>
                                                                                <ul
                                                                                    class="list-group list-group-flush mt-2">
                                                                                    @foreach ($strengths as $strength)
                                                                                        <li
                                                                                            class="list-group-item text-success">
                                                                                            <i class="fas fa-check"></i>
                                                                                            {{ $strength }}
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            </div>
                                                                        @endif

                                                                        @if (!empty($submission->ai_weaknesses))
                                                                            <div class="mb-3">
                                                                                <strong>Điểm yếu:</strong>
                                                                                <ul
                                                                                    class="list-group list-group-flush mt-2">
                                                                                    @php
                                                                                        $weaknesses =
                                                                                            $submission->ai_weaknesses;
                                                                                        if (is_string($weaknesses)) {
                                                                                            $weaknesses =
                                                                                                json_decode(
                                                                                                    $weaknesses,
                                                                                                    true,
                                                                                                ) ?:
                                                                                                [];
                                                                                        }
                                                                                    @endphp
                                                                                    @if (!empty($weaknesses))
                                                                                        <div class="mb-3">
                                                                                            <strong>Điểm yếu:</strong>
                                                                                            <ul
                                                                                                class="list-group list-group-flush mt-2">
                                                                                                @foreach ($weaknesses as $weakness)
                                                                                                    <li
                                                                                                        class="list-group-item text-warning">
                                                                                                        <i
                                                                                                            class="fas fa-exclamation-triangle"></i>
                                                                                                        {{ $weakness }}
                                                                                                    </li>
                                                                                                @endforeach
                                                                                            </ul>
                                                                                        </div>
                                                                                    @endif
                                                                                @else
                                                                                    <div class="text-center text-muted">
                                                                                        <i
                                                                                            class="fas fa-robot fa-3x mb-3"></i>
                                                                                        <p>Chưa có kết quả chấm từ AI
                                                                                        </p>
                                                                                        <button wire:click="gradeWithAI"
                                                                                            class="btn btn-primary position-relative"
                                                                                            wire:loading.attr="disabled"
                                                                                            wire:loading.class="disabled">
                                                                                            <div wire:loading.remove>
                                                                                                <i
                                                                                                    class="fas fa-magic"></i>
                                                                                                Chấm bài bằng AI
                                                                                            </div>
                                                                                            <div wire:loading
                                                                                                class="loading-overlay">
                                                                                                <div class="spinner-border spinner-border-sm text-light me-2"
                                                                                                    role="status">
                                                                                                    <span
                                                                                                        class="visually-hidden">Loading...</span>
                                                                                                </div>
                                                                                                <span
                                                                                                    class="loading-text">Đang
                                                                                                    chấm...</span>
                                                                                            </div>
                                                                                        </button>
                                                                                    </div>
                                                                        @endif

                                                                        @if ($showAIFeedback && $aiResult)
                                                                            <div class="mt-3">
                                                                                <button wire:click="applyAIScore"
                                                                                    class="btn btn-success">
                                                                                    <i class="fas fa-check"></i> Áp
                                                                                    dụng
                                                                                    điểm từ AI
                                                                                </button>
                                                                            </div>
                                                                        @endif
                                                                </div>
                                                        </div>
                                                    </div>
                                        </div>

                                        <!-- Phân tích AI -->
                                        @if ($submission->hasAIAnalysis())
                                            <div class="row mt-4">
                                                <div class="col-12">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h5 class="card-title">Phân tích chi tiết từ AI</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            @if (!empty($submission->ai_analysis))
                                                                <div class="row">
                                                                    @php
                                                                        $analysis = $submission->ai_analysis;
                                                                        if (is_string($analysis)) {
                                                                            $analysis =
                                                                                json_decode($analysis, true) ?: [];
                                                                        }
                                                                    @endphp
                                                                    @if (!empty($analysis))
                                                                        <div class="row">
                                                                            @foreach ($analysis as $aspect => $evaluation)
                                                                                <div class="col-md-6 mb-3">
                                                                                    <strong>{{ ucfirst(str_replace('_', ' ', $aspect)) }}:</strong>
                                                                                    <p class="text-muted">
                                                                                        {{ $evaluation }}</p>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif

                                                                    @if (!empty($submission->ai_improvement_suggestions))
                                                                        <div class="mb-3">
                                                                            <strong>Gợi ý cải thiện:</strong>
                                                                            <ul class="list-group mt-2">
                                                                                @php
                                                                                    $improvementSuggestions =
                                                                                        $submission->ai_improvement_suggestions;
                                                                                    if (
                                                                                        is_string(
                                                                                            $improvementSuggestions,
                                                                                        )
                                                                                    ) {
                                                                                        $improvementSuggestions =
                                                                                            json_decode(
                                                                                                $improvementSuggestions,
                                                                                                true,
                                                                                            ) ?:
                                                                                            [];
                                                                                    }
                                                                                @endphp
                                                                                @if (!empty($improvementSuggestions))
                                                                                    <div class="mb-3">
                                                                                        <strong>Gợi ý cải
                                                                                            thiện:</strong>
                                                                                        <ul class="list-group mt-2">
                                                                                            @foreach ($improvementSuggestions as $suggestion)
                                                                                                <li
                                                                                                    class="list-group-item">
                                                                                                    <div
                                                                                                        class="d-flex justify-content-between align-items-start">
                                                                                                        <div>
                                                                                                            <strong>{{ ucfirst($suggestion['category']) }}:</strong>
                                                                                                            {{ $suggestion['suggestion'] }}
                                                                                                        </div>
                                                                                                        <span
                                                                                                            class="badge bg-{{ $suggestion['priority'] === 'high' ? 'danger' : ($suggestion['priority'] === 'medium' ? 'warning' : 'info') }}">
                                                                                                            {{ ucfirst($suggestion['priority']) }}
                                                                                                        </span>
                                                                                                    </div>
                                                                                                </li>
                                                                                            @endforeach
                                                                                        </ul>
                                                                                    </div>
                                                                                @endif

                                                                                @if (!empty($submission->ai_learning_resources))
                                                                                    <div class="mb-3">
                                                                                        <strong>Tài liệu học
                                                                                            tập:</strong>
                                                                                        <div class="row mt-2">
                                                                                            @php
                                                                                                $learningResources =
                                                                                                    $submission->ai_learning_resources;
                                                                                                if (
                                                                                                    is_string(
                                                                                                        $learningResources,
                                                                                                    )
                                                                                                ) {
                                                                                                    $learningResources =
                                                                                                        json_decode(
                                                                                                            $learningResources,
                                                                                                            true,
                                                                                                        ) ?:
                                                                                                        [];
                                                                                                }
                                                                                            @endphp
                                                                                            @if (!empty($learningResources))
                                                                                                <div class="mb-3">
                                                                                                    <strong>Tài liệu học
                                                                                                        tập:</strong>
                                                                                                    <div
                                                                                                        class="row mt-2">
                                                                                                        @foreach ($learningResources as $resource)
                                                                                                            <div
                                                                                                                class="col-md-6 mb-2">
                                                                                                                <div
                                                                                                                    class="card">
                                                                                                                    <div
                                                                                                                        class="card-body">
                                                                                                                        <h6
                                                                                                                            class="card-title">
                                                                                                                            {{ $resource['title'] }}
                                                                                                                        </h6>
                                                                                                                        <p
                                                                                                                            class="card-text">
                                                                                                                            {{ $resource['description'] }}
                                                                                                                        </p>
                                                                                                                        <a href="{{ $resource['url'] }}"
                                                                                                                            target="_blank"
                                                                                                                            class="btn btn-sm btn-outline-primary">
                                                                                                                            <i
                                                                                                                                class="fas fa-external-link-alt"></i>
                                                                                                                            Xem
                                                                                                                            tài
                                                                                                                            liệu
                                                                                                                        </a>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        @endforeach
                                                                                                    </div>
                                                                                                </div>
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                        </div>
                                                                </div>
                                                            @else
                                                                <div class="row mt-4">
                                                                    <div class="col-12">
                                                                        <div class="card">
                                                                            <div class="card-body text-center">
                                                                                <button wire:click="analyzeWithAI"
                                                                                    class="btn btn-outline-info position-relative"
                                                                                    wire:loading.attr="disabled"
                                                                                    wire:loading.class="disabled">
                                                                                    <div wire:loading.remove>
                                                                                        <i
                                                                                            class="fas fa-chart-line"></i>
                                                                                        Phân tích chi tiết bằng AI
                                                                                    </div>
                                                                                    <div wire:loading
                                                                                        class="loading-overlay">
                                                                                        <div class="spinner-border spinner-border-sm text-light me-2"
                                                                                            role="status">
                                                                                            <span
                                                                                                class="visually-hidden">Loading...</span>
                                                                                        </div>
                                                                                        <span class="loading-text">Đang
                                                                                            phân tích...</span>
                                                                                    </div>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
</x-layouts.dash-admin>
