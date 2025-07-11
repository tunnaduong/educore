<?php

namespace App\Http\Controllers;

use Livewire\Livewire;

class AdminController extends Controller
{
    public function assignments()
    {
        return Livewire::new(\App\Livewire\Admin\Assignments\Overview::class);
    }
}
