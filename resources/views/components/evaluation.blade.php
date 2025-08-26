@php
    $student = Auth::user()->student;
    $needEvaluation = false;

    if ($student) {
        $currentRounds = \App\Models\EvaluationRound::current()->get();
        if ($currentRounds->count() > 0) {
            $evaluatedRounds = \App\Models\Evaluation::where('student_id', $student->id)
                ->whereIn('evaluation_round_id', $currentRounds->pluck('id'))
                ->whereNotNull('submitted_at')
                ->count();

            $needEvaluation = $evaluatedRounds < $currentRounds->count();
        }
    }
@endphp

@if ($student && $needEvaluation)
    <!-- Modal đánh giá bắt buộc - không thể đóng -->
    <div class="modal fade show d-block" id="requiredEvaluationModal" tabindex="-1"
        style="background-color: rgba(0,0,0,0.8); overflow-y: auto; z-index: 1050;">
        <div class="modal-dialog modal-dialog-scrollable modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle-fill mr-2"></i>
                        {{ __('general.evaluation_required') }}
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
                <h4 class="text-muted">{{ __('general.function_locked') }}</h4>
                <p class="text-muted">{{ __('general.complete_evaluation_first') }}
                </p>
            </div>
        </div>
    </div>
@endif
