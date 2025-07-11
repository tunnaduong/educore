<?php

namespace App\Livewire\Admin\Grading;

use Livewire\Component;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Support\Facades\Auth;

class GradingList extends Component
{
    public $assignments = [];

    public function mount()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $this->assignments = Assignment::withCount('submissions')->orderByDesc('created_at')->get();
        } else if ($user->role === 'teacher') {
            $classIds = $user->teachingClassrooms->pluck('id');
            $this->assignments = Assignment::withCount('submissions')
                ->whereIn('class_id', $classIds)
                ->orderByDesc('created_at')
                ->get();
        } else {
            $this->assignments = collect();
        }
    }

    public function selectAssignment($assignmentId)
    {
        return $this->redirect(route('grading.grade-assignment', ['assignment' => $assignmentId]), true);
    }

    public function render()
    {
        return view('admin.grading.grading-list', [
            'assignments' => $this->assignments,
        ]);
    }
}
