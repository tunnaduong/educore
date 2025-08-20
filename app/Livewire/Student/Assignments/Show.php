<?php

namespace App\Livewire\Student\Assignments;

use App\Models\Assignment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public Assignment $assignment;

    public $assignmentId;

    public Collection $submissions;

    public function mount($assignmentId)
    {
        $this->assignmentId = $assignmentId;
        $this->loadAssignment();
    }

    public function loadAssignment()
    {
        $student = Auth::user()->student;

        if (! $student) {
            abort(403, 'Bạn không có quyền truy cập');
        }

        $this->assignment = Assignment::whereHas('classroom.students', function ($q) use ($student) {
            $q->where('users.id', $student->user_id);
        })
            ->with([
                'classroom.teachers',
                'submissions' => function ($q) use ($student) {
                    $q->where('student_id', $student->id);
                },
            ])
            ->findOrFail($this->assignmentId);

        // Lấy tất cả submissions của học viên cho assignment này (collection)
        $this->submissions = collect($this->assignment->submissions);
    }

    public function isOverdue()
    {
        return $this->assignment->deadline < now();
    }

    public function isCompleted()
    {
        return ! empty($this->submissions) && $this->submissions->count() > 0;
    }

    public function canSubmit()
    {
        return ! $this->isOverdue() && ! $this->isCompleted();
    }

    public function canRedo()
    {
        // Cho phép làm lại nếu chưa quá hạn
        return ! $this->isOverdue();
    }

    public function redoSubmission()
    {
        // Không xóa submission cũ, chỉ chuyển hướng sang trang nộp bài
        return $this->redirect(route('student.assignments.submit', $this->assignment->id));
    }

    public function getStatusBadge()
    {
        if ($this->isCompleted()) {
            return [
                'text' => 'Đã hoàn thành',
                'class' => 'bg-green-100 text-green-800',
            ];
        }

        if ($this->isOverdue()) {
            return [
                'text' => 'Quá hạn',
                'class' => 'bg-red-100 text-red-800',
            ];
        }

        return [
            'text' => 'Cần làm',
            'class' => 'bg-yellow-100 text-yellow-800',
        ];
    }

    public function getTimeRemaining()
    {
        if ($this->isOverdue()) {
            return 'Đã quá hạn '.$this->assignment->deadline->diffForHumans();
        }

        return 'Còn lại '.$this->assignment->deadline->diffForHumans(now(), ['parts' => 2]);
    }

    public function render()
    {
        return view('student.assignments.show');
    }
}
