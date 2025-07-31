<?php

namespace App\Livewire\Admin\Classrooms;

use App\Models\Classroom;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterTeacher = '';
    public $filterStatus = '';

    protected $queryString = ['search', 'filterTeacher', 'filterStatus'];

    protected $listeners = ['refresh' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterTeacher()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function delete($classroomId)
    {
        try {
            $classroom = Classroom::findOrFail($classroomId);
            $classroom->delete();
            session()->flash('success', 'Xóa lớp học thành công!');
            $this->dispatch('hide-delete-modal', id: $classroomId);
        } catch (\Exception $e) {
            session()->flash('error', 'Không thể xóa lớp học này. Vui lòng thử lại sau.');
        }
    }

    public function render()
    {
        $classrooms = Classroom::query()
            ->withCount('students')
            ->with('teachers')
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('teachers', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->filterTeacher, function ($query) {
                $query->whereHas('teachers', function ($q) {
                    $q->where('users.id', $this->filterTeacher);
                });
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->latest()
            ->paginate(10);

        $teachers = User::where('role', 'teacher')
            ->orderBy('name')
            ->get();

        return view('admin.classrooms.index', [
            'classrooms' => $classrooms,
            'teachers' => $teachers
        ]);
    }
}
