<?php

namespace App\Livewire\Admin;

use App\Models\Message;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Home extends Component
{
    public $unreadCount = 0;

    public function mount()
    {
        $this->unreadCount = Message::unread(auth()->id())->count();
    }

    public function render()
    {
        // if user is admin then render admin.home
        // if user is teacher then render teacher.home
        // if user is student then render student.home
        $user = Auth::user();
        if ($user->role == 'admin') {
            return view('admin.home');
        }
        if ($user->role == 'teacher') {
            return view('teacher.home');
        }
        if ($user->role == 'student') {
            return view('student.home');
        }
    }
}
