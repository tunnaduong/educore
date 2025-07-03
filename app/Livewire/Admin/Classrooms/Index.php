<?php

namespace App\Livewire\Admin\Classrooms;

use App\Models\Classroom;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    protected $queryString = ['search'];

    protected $listeners = ['refresh' => '$refresh'];

    public function updatingSearch()
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
            ->with('teacher')
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('teacher', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->latest()
            ->paginate(10);

        return view('admin.classrooms.index', [
            'classrooms' => $classrooms
        ]);
    }
}
