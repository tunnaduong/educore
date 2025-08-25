<div>
    @include('components.language')
    @if (!empty($conflicts))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-start">
                <i class="bi bi-exclamation-triangle-fill fs-4 mr-3 mt-1"></i>
                <div class="flex-grow-1">
                    <h6 class="alert-heading mb-1">Phát hiện trùng lịch học!</h6>
                    <p class="mb-2">Một số học sinh trong lớp này có lịch học trùng với các lớp khác.</p>

                    @if ($showConflicts)
                        <div class="mt-3" style="max-height: 300px; overflow-y: auto;">
                            @foreach ($conflicts as $studentId => $conflictData)
                                <div class="card border-warning mb-2">
                                    <div class="card-body py-2">
                                        <div class="d-flex align-items-start">
                                            <i class="bi bi-person-circle text-danger mr-2 mt-1"></i>
                                            <div class="flex-grow-1">
                                                <strong>{{ $conflictData['student']->name }}</strong>
                                                <div class="small text-muted mt-1">
                                                    @foreach ($conflictData['conflicts'] as $conflict)
                                                        <div class="mb-1">{{ $conflict['message'] }}</div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <button type="button" class="btn btn-sm btn-outline-warning" wire:click="toggleConflicts">
                            <i class="bi bi-{{ $showConflicts ? 'chevron-up' : 'chevron-down' }} mr-1"></i>
                            {{ $showConflicts ? 'Ẩn chi tiết' : 'Xem chi tiết' }}
                        </button>
                        @if ($showConflicts && count($conflicts) > 3)
                            <small class="text-muted">
                                <i class="bi bi-info-circle mr-1"></i>
                                Có thể scroll để xem tất cả {{ count($conflicts) }} học sinh
                            </small>
                        @endif
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>
