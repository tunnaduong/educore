<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterRole = '';
    public $filterStatus = '';
    protected $queryString = ['search', 'filterRole', 'filterStatus'];

    // Reset pagination when search changes
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = User::query();
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('phone', 'like', "%{$this->search}%");
            });
        }
        if ($this->filterRole) {
            $query->where('role', $this->filterRole);
        }
        if ($this->filterStatus !== '') {
            $query->where('is_active', $this->filterStatus == 'active' ? 1 : 0);
        }
        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Delete a user by id.
     */
    public function delete(int $id): void
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            session()->flash('success', 'Xóa người dùng thành công!');
        }
    }
}
