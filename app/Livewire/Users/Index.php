<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public $search = '';

    public function render()
    {
        $users = User::where('name', 'like', "%{$this->search}%")->paginate(10);
        return view('livewire.users.index', compact('users'));
    }
}
