<?php

namespace App\Livewire\Teacher\Assignments;

use Livewire\Component;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;

class Show extends Component
{
    public $assignment;
    public $classroom;
    public $submissions;
    public $students;
    public $assignmentId;
    public $grading = [];

    public function mount($assignment)
    {
        $this->assignmentId = $assignment;
        $this->assignment = Assignment::with('classroom')->findOrFail($assignment);
        $this->classroom = $this->assignment->classroom;
        $this->submissions = AssignmentSubmission::where('assignment_id', $assignment)
            ->whereNotNull('submitted_at')
            ->with(['student.user'])
            ->get();
        $this->students = $this->classroom ? $this->classroom->students : collect();
    }

    public function updatedGrading($value, $key)
    {
        // Không làm gì, chỉ để Livewire nhận biết thay đổi
    }

    /**
     * Kiểm tra trạng thái nộp bài của học viên
     */
    public function getSubmissionStatus($student)
    {
        // $student là User model từ classroom->students()
        // Cần lấy student_id từ Student model
        $studentId = $student->student ? $student->student->id : null;

        if (!$studentId) {
            return [
                'status' => 'not_submitted',
                'label' => 'Chưa nộp',
                'class' => 'bg-secondary',
                'submitted_types' => [],
                'required_types' => $this->assignment->types ?? [],
                'submitted_count' => 0,
                'required_count' => count($this->assignment->types ?? [])
            ];
        }

        // Lấy tất cả submissions của học viên cho assignment này (chỉ những bài đã thực sự nộp)
        $studentSubmissions = $this->submissions->where('student_id', $studentId)
            ->whereNotNull('submitted_at');

        // Nếu không có submission nào
        if ($studentSubmissions->isEmpty()) {
            return [
                'status' => 'not_submitted',
                'label' => 'Chưa nộp',
                'class' => 'bg-secondary',
                'submitted_types' => [],
                'required_types' => $this->assignment->types ?? [],
                'submitted_count' => 0,
                'required_count' => count($this->assignment->types ?? [])
            ];
        }

        // Lấy các loại bài đã nộp
        $submittedTypes = $studentSubmissions->pluck('submission_type')->toArray();
        $requiredTypes = $this->assignment->types ?? [];

        // Kiểm tra xem đã nộp đủ chưa
        $missingTypes = array_diff($requiredTypes, $submittedTypes);

        if (empty($missingTypes)) {
            // Đã nộp đủ
            return [
                'status' => 'completed',
                'label' => 'Đã nộp đủ',
                'class' => 'bg-success',
                'submitted_types' => $submittedTypes,
                'required_types' => $requiredTypes,
                'submitted_count' => count($submittedTypes),
                'required_count' => count($requiredTypes)
            ];
        } else {
            // Còn thiếu
            return [
                'status' => 'partial',
                'label' => 'Còn thiếu ' . count($missingTypes) . '/' . count($requiredTypes),
                'class' => 'bg-warning',
                'submitted_types' => $submittedTypes,
                'required_types' => $requiredTypes,
                'missing_types' => $missingTypes,
                'submitted_count' => count($submittedTypes),
                'required_count' => count($requiredTypes)
            ];
        }
    }

    /**
     * Lấy label cho loại bài tập
     */
    public function getTypeLabel($type)
    {
        return match ($type) {
            'text' => 'Điền từ',
            'essay' => 'Tự luận',
            'image' => 'Nộp ảnh',
            'audio' => 'Ghi âm',
            'video' => 'Quay video',
            default => $type
        };
    }

    /**
     * Lấy submission theo loại
     */
    public function getSubmissionByType($student, $type)
    {
        // $student là User model từ classroom->students()
        $studentId = $student->student ? $student->student->id : null;

        if (!$studentId) {
            return null;
        }

        return $this->submissions
            ->where('student_id', $studentId)
            ->where('submission_type', $type)
            ->first();
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

        // Refresh data để cập nhật giao diện
        $this->refreshData();
    }

    /**
     * Refresh all data to ensure UI is up to date
     */
    public function refreshData()
    {
        $this->submissions = AssignmentSubmission::where('assignment_id', $this->assignmentId)
            ->whereNotNull('submitted_at')
            ->with(['student.user'])
            ->get();

        // Force Livewire to re-render the component
        $this->render();
    }

    public function render()
    {
        return view('teacher.assignments.show');
    }
}
