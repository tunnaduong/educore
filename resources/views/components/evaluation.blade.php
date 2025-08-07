@php
$student = Auth::user()->student;
$hasEvaluation = $student ? $student->evaluations()->whereNotNull('submitted_at')->exists() : false;
@endphp

@if (!$hasEvaluation && $student)
<!-- Modal đánh giá bắt buộc - không thể đóng -->
<div class="modal fade show d-block" id="requiredEvaluationModal" tabindex="-1" style="background-color: rgba(0,0,0,0.8); overflow-y: auto;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Yêu cầu đánh giá chất lượng học tập
                </h5>
            </div>
            <div class="modal-body p-0" style="max-height: 80vh; overflow-y: auto;">
                <livewire:student.evaluation.index />
            </div>
        </div>
    </div>
</div>

<!-- Nội dung bị khóa -->
<div class="container-fluid py-5 text-center" style="opacity: 0.3; pointer-events: none;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <i class="bi bi-lock-fill fs-1 text-muted mb-3"></i>
            <h4 class="text-muted">Chức năng đã bị khóa</h4>
            <p class="text-muted">Bạn cần hoàn thành đánh giá chất lượng học tập trước khi có thể sử dụng hệ thống.</p>
        </div>
    </div>
</div>
@endif
