<?php

namespace App\Livewire\Admin\Assignments;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AssignmentList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $classroomFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'classroomFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingClassroomFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'statusFilter', 'classroomFilter']);
        $this->resetPage();
    }

    public function deleteAssignment($assignmentId)
    {
        try {
            $assignment = Assignment::findOrFail($assignmentId);

            // Test: Log thông tin assignment
            Log::info('Attempting to delete assignment: ' . $assignment->title . ' (ID: ' . $assignmentId . ')');

            // Xóa assignment (submissions sẽ tự động xóa do cascade)
            $deleted = $assignment->delete();

            if ($deleted) {
                session()->flash('success', 'Bài tập "' . $assignment->title . '" đã được xóa thành công.');
            } else {
                session()->flash('error', 'Không thể xóa bài tập.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi xóa bài tập: ' . $e->getMessage());
            Log::error('Delete assignment error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $teacher = Auth::user();

        // Tạm thời lấy tất cả assignments để test
        $assignments = Assignment::with(['classroom', 'submissions'])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                if ($this->statusFilter === 'active') {
                    $query->where('deadline', '>', now());
                } elseif ($this->statusFilter === 'overdue') {
                    $query->where('deadline', '<', now());
                }
            })
            ->when($this->classroomFilter, function ($query) {
                $query->where('class_id', $this->classroomFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $classrooms = \App\Models\Classroom::orderBy('name')->get();

        return view('admin.assignments.assignment-list', [
            'assignments' => $assignments,
            'classrooms' => $classrooms,
        ]);
    }
}
