<x-layouts.dash-teacher active="quizzes">
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
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-patch-question mr-2"></i>{{ $t('Quản lý bài kiểm tra', 'Manage quizzes', '管理测验') }}
                    </h4>
                    <p class="text-muted mb-0">{{ $t('Danh sách bài kiểm tra của các lớp bạn đang dạy', 'List of quizzes for your classes', '您所授课程的测验列表') }}</p>
                </div>
                <a href="{{ route('teacher.quizzes.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle mr-2"></i>{{ $t('Tạo bài kiểm tra mới', 'Create new quiz', '创建新测验') }}
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ $t('Tìm kiếm', 'Search', '搜索') }}</label>
                        <input type="text" class="form-control" wire:model.live="search"
                            placeholder="{{ $t('Tìm theo tên hoặc mô tả...', 'Search by title or description...', '按标题或描述搜索...') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ $t('Lớp học', 'Classroom', '班级') }}</label>
                        <select class="form-control" wire:model.live="filterClass">
                            <option value="">{{ $t('Tất cả lớp', 'All classes', '所有班级') }}</option>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ $t('Trạng thái', 'Status', '状态') }}</label>
                        <select class="form-control" wire:model.live="filterStatus">
                            <option value="">{{ $t('Tất cả', 'All', '全部') }}</option>
                            <option value="active">{{ $t('Còn hạn', 'Active', '有效') }}</option>
                            <option value="expired">{{ $t('Hết hạn', 'Expired', '过期') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" wire:click="$set('search', '')">
                            <i class="bi bi-arrow-clockwise mr-2"></i>{{ $t('Làm mới', 'Reset', '重置') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quiz List -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-list-ul mr-2"></i>{{ $t('Danh sách bài kiểm tra', 'Quiz List', '测验列表') }}
                </h6>
            </div>
            <div class="card-body">
                @if ($quizzes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ $t('Tiêu đề', 'Title', '标题') }}</th>
                                    <th>{{ $t('Lớp học', 'Classroom', '班级') }}</th>
                                    <th>{{ $t('Số câu hỏi', 'Question count', '题目数量') }}</th>
                                    <th>{{ $t('Thời gian làm bài', 'Time limit', '答题时长') }}</th>
                                    <th>{{ $t('Hạn nộp', 'Deadline', '截止时间') }}</th>
                                    <th>{{ $t('Trạng thái', 'Status', '状态') }}</th>
                                    <th>{{ $t('Ngày tạo', 'Created date', '创建日期') }}</th>
                                    <th>{{ $t('Thao tác', 'Actions', '操作') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quizzes as $quiz)
                                    <tr>
                                        <td>
                                            <div class="fw-medium">{{ $quiz->title }}</div>
                                            @if ($quiz->description)
                                                <small
                                                    class="text-muted">{{ Str::limit($quiz->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $quiz->classroom->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $quiz->getQuestionCount() }}</span>
                                        </td>
                                        <td>
                                            @if ($quiz->time_limit)
                                                <span class="badge bg-warning text-dark">{{ $quiz->time_limit }}
                                                    {{ $t('phút', 'minutes', '分钟') }}</span>
                                            @else
                                                <span class="text-muted">{{ $t('Không giới hạn', 'No limit', '不限') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($quiz->deadline)
                                                <div class="fw-medium">{{ $quiz->deadline->format('d/m/Y H:i') }}</div>
                                                <small class="text-muted">{{ $quiz->deadline->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">{{ $t('Không có hạn', 'No deadline', '无截止日期') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($quiz->isExpired())
                                                <span class="badge bg-danger">{{ $t('Hết hạn', 'Expired', '过期') }}</span>
                                            @else
                                                <span class="badge bg-success">{{ $t('Còn hạn', 'Active', '有效') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $quiz->created_at->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $quiz->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('teacher.quizzes.show', $quiz) }}"
                                                    class="btn btn-sm btn-outline-primary" title="{{ $t('Xem chi tiết', 'View details', '查看详情') }}">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('teacher.quizzes.edit', $quiz) }}"
                                                    class="btn btn-sm btn-outline-warning" title="{{ $t('Sửa', 'Edit', '编辑') }}">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ route('teacher.quizzes.show', $quiz) }}"
                                                    class="btn btn-sm btn-outline-info" title="{{ $t('Xem kết quả', 'View results', '查看结果') }}">
                                                    <i class="bi bi-graph-up"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    title="{{ $t('Xóa', 'Delete', '删除') }}" wire:click="deleteQuiz({{ $quiz->id }})"
                                                    wire:confirm="{{ $t('Bạn có chắc chắn muốn xóa bài kiểm tra', 'Are you sure you want to delete quiz', '您确定要删除测验') }} '{{ $quiz->title }}'? {{ $t('Hành động này không thể hoàn tác.', 'This action cannot be undone.', '此操作无法撤销。') }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $quizzes->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-journal-x fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">{{ $t('Không có bài kiểm tra nào', 'No quizzes yet', '暂无测验') }}</h5>
                        <p class="text-muted">{{ $t('Hãy tạo bài kiểm tra đầu tiên để bắt đầu.', 'Create your first quiz to get started.', '请创建第一个测验以开始。') }}</p>
                        <a href="{{ route('teacher.quizzes.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle mr-2"></i>{{ $t('Tạo bài kiểm tra', 'Create quiz', '创建测验') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Flash Message -->
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert">
                <i class="bi bi-check-circle mr-2"></i>{{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert">
                <i class="bi bi-exclamation-triangle mr-2"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>
</x-layouts.dash-teacher>
