<div>
    @if(!empty($conflicts))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-start">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3 mt-1"></i>
                <div class="flex-grow-1">
                    <h6 class="alert-heading mb-1">Phát hiện trùng lịch dạy!</h6>
                    <p class="mb-2">Một số giáo viên trong lớp này có lịch dạy trùng với các lớp khác.</p>
                    
                    @if($showConflicts)
                        <div class="mt-3" style="max-height: 300px; overflow-y: auto;">
                            @foreach($conflicts as $teacherId => $conflictData)
                                <div class="card border-warning mb-2">
                                    <div class="card-body py-2">
                                        <div class="d-flex align-items-start">
                                            <i class="bi bi-person-circle text-warning me-2 mt-1"></i>
                                            <div class="flex-grow-1">
                                                <strong>{{ $conflictData['teacher']->name }}</strong>
                                                <span class="badge bg-warning text-dark ms-2">Giáo viên</span>
                                                <div class="small text-muted mt-1">
                                                    @foreach($conflictData['conflicts'] as $conflict)
                                                        <div class="mb-1">{{ $conflict['message'] }}</div>
                                                        @if($conflict['overlapTime'])
                                                            <div class="text-danger small">
                                                                <i class="bi bi-exclamation-circle me-1"></i>
                                                                Thời gian trùng: {{ $conflict['overlapTime'] }}
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    <div class="mt-3">
                        <button type="button" class="btn btn-sm btn-outline-warning" wire:click="toggleConflicts">
                            @if($showConflicts)
                                <i class="bi bi-eye-slash me-1"></i>Ẩn chi tiết
                            @else
                                <i class="bi bi-eye me-1"></i>Xem chi tiết
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
