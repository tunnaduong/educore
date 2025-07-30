<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Logout extends Component
{
    public $showDropdown = false;

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function render()
    {
        return view('components.logout');
    }
}