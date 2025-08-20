<?php

namespace App\Livewire\Admin\Classrooms;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $filterTeacher = '';

    public $filterStatus = '';

    public $showTrashed = false;

    public $hideCompleted = false;

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
            $classroom = Classroom::with(['teachers'])->findOrFail($classroomId);

            // Kiểm tra xem lớp có đang hoạt động không
            if ($classroom->status === 'active') {
                session()->flash('error', 'Không thể xóa lớp học đang hoạt động. Vui lòng chuyển trạng thái sang không hoạt động trước.');

                return;
            }

            // Lớp ở trạng thái 'draft' có thể xóa hoàn toàn nếu không có dữ liệu
            if ($classroom->status === 'draft') {
                // Kiểm tra xem lớp có sinh viên không
                if ($classroom->students_count > 0) {
                    session()->flash('error', 'Không thể xóa lớp học có sinh viên. Lớp sẽ được ẩn khỏi danh sách.');
                    $classroom->delete(); // Soft delete
                    $this->dispatch('refresh');

                    return;
                }

                // Kiểm tra xem lớp có bài tập, bài học hoặc điểm danh không
                if ($classroom->assignments_count > 0 || $classroom->lessons_count > 0 || $classroom->attendances_count > 0) {
                    session()->flash('error', 'Không thể xóa lớp học có dữ liệu. Lớp sẽ được ẩn khỏi danh sách.');
                    $classroom->delete(); // Soft delete
                    $this->dispatch('refresh');

                    return;
                }

                // Nếu lớp draft không có dữ liệu gì, có thể xóa hoàn toàn
                $classroom->forceDelete();
                session()->flash('success', 'Xóa lớp học nháp thành công!');
                $this->dispatch('refresh');

                return;
            }

            // Kiểm tra xem lớp có sinh viên không
            if ($classroom->students_count > 0) {
                session()->flash('error', 'Không thể xóa lớp học có sinh viên. Lớp sẽ được ẩn khỏi danh sách.');
                $classroom->delete(); // Soft delete
                $this->dispatch('refresh');

                return;
            }

            // Kiểm tra xem lớp có bài tập, bài học hoặc điểm danh không
            if ($classroom->assignments_count > 0 || $classroom->lessons_count > 0 || $classroom->attendances_count > 0) {
                session()->flash('error', 'Không thể xóa lớp học có dữ liệu. Lớp sẽ được ẩn khỏi danh sách.');
                $classroom->delete(); // Soft delete
                $this->dispatch('refresh');

                return;
            }

            // Nếu lớp không có dữ liệu gì, có thể xóa hoàn toàn
            $classroom->forceDelete();
            session()->flash('success', 'Xóa lớp học thành công!');
            $this->dispatch('refresh');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            session()->flash('error', 'Không tìm thấy lớp học cần xóa.');
            Log::error('Delete Classroom - Not Found: '.$e->getMessage(), [
                'classroom_id' => $classroomId,
                'user_id' => Auth::id(),
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Không thể xóa lớp học này. Vui lòng thử lại sau.');
            Log::error('Delete Classroom Error: '.$e->getMessage(), [
                'classroom_id' => $classroomId,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function restore($classroomId)
    {
        try {
            $classroom = Classroom::withTrashed()->findOrFail($classroomId);
            if ($classroom->trashed()) {
                $classroom->restore();
                session()->flash('success', 'Khôi phục lớp học thành công!');
                $this->dispatch('refresh');
            } else {
                session()->flash('error', 'Lớp học này không bị ẩn.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Không thể khôi phục lớp học. Vui lòng thử lại sau.');
            Log::error('Restore Classroom Error: '.$e->getMessage(), [
                'classroom_id' => $classroomId,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function forceDelete($classroomId)
    {
        try {
            $classroom = Classroom::withTrashed()->findOrFail($classroomId);
            if ($classroom->trashed()) {
                $classroom->forceDelete();
                session()->flash('success', 'Xóa vĩnh viễn lớp học thành công!');
            } else {
                session()->flash('error', 'Lớp học này chưa bị ẩn, không thể xóa vĩnh viễn.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Không thể xóa vĩnh viễn lớp học. Vui lòng thử lại sau.');
            Log::error('Force Delete Classroom Error: '.$e->getMessage(), [
                'classroom_id' => $classroomId,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function closeModal($classroomId)
    {
        $this->dispatch('close-modal', id: $classroomId);
    }

    public function render()
    {
        $query = Classroom::query()->with('teachers');

        if ($this->showTrashed) {
            $query = $query->withTrashed();
        } else {
            $query = $query->whereNull('deleted_at');
        }

        $query = $query
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%'.$this->search.'%')
                        ->orWhereHas('teachers', function ($query) {
                            $query->where('name', 'like', '%'.$this->search.'%');
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
            });

        if ($this->hideCompleted) {
            $query = $query->where('status', '!=', 'completed');
        }

        $classrooms = $query->latest()->paginate(10);

        $teachers = User::where('role', 'teacher')
            ->orderBy('name')
            ->get();

        return view('admin.classrooms.index', [
            'classrooms' => $classrooms,
            'teachers' => $teachers,
        ]);
    }
}
