<?php

namespace App\Livewire\Admin\Assignments;

use Livewire\Component;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\User;

class Show extends Component
{
    public $assignment;
    public $classroom;
    public $submissions;
    public $students;
    public $assignmentId;
    public $grading = [];

    public function mount($assignmentId)
    {
        $this->assignmentId = $assignmentId;
        $this->assignment = Assignment::with('classroom')->findOrFail($assignmentId);
        $this->classroom = $this->assignment->classroom;
        $this->submissions = AssignmentSubmission::where('assignment_id', $assignmentId)->with(['student.user'])->get();
        $this->students = $this->classroom ? $this->classroom->students : collect();
    }

    public function updatedGrading($value, $key)
    {
        // Không làm gì, chỉ để Livewire nhận biết thay đổi
    }

    public function saveGrade($submissionId)
    {
        $score = $this->grading[$submissionId]['score'] ?? null;
        $feedback = $this->grading[$submissionId]['feedback'] ?? null;

        // Validation cho điểm
        if ($score !== null && $score !== '') {
            // Kiểm tra xem có phải là số hợp lệ không
            if (!is_numeric($score) || !is_finite($score)) {
                session()->flash('error', 'Điểm phải là số hợp lệ!');
                return;
            }

            // Chuyển đổi thành float để so sánh chính xác
            $score = (float) $score;

            if ($score < 0) {
                session()->flash('error', 'Điểm không được nhỏ hơn 0!');
                return;
            }

            if ($score > 10) {
                session()->flash('error', 'Điểm không được vượt quá 10!');
                return;
            }

            // Kiểm tra số thập phân (chỉ cho phép tối đa 1 chữ số thập phân)
            if (strpos((string) $score, '.') !== false) {
                $decimalPlaces = strlen(substr(strrchr((string) $score, "."), 1));
                if ($decimalPlaces > 1) {
                    session()->flash('error', 'Điểm chỉ được có tối đa 1 chữ số thập phân!');
                    return;
                }
            }
        }

        // Nếu điểm là rỗng (empty string), set null
        if ($score === '' || $score === null) {
            $score = null;
        }

        $submission = AssignmentSubmission::find($submissionId);
        if ($submission) {
            $submission->score = $score;
            $submission->feedback = $feedback;
            $submission->save();
            session()->flash('success', 'Đã lưu điểm và nhận xét!');
        }
        // Làm mới submissions để cập nhật giao diện
        $this->submissions = AssignmentSubmission::where('assignment_id', $this->assignmentId)->with(['student'])->get();
    }

    public function render()
    {
        return view('admin.assignments.show');
    }
}
