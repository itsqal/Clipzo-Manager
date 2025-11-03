<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\On;
use Livewire\Component;

class ToggleMode extends Component
{
    public $mode = false;

    public function updatedMode()
    {
        $this->dispatch('modeToggled', $this->mode);
    }

    #[On('transactionFinished')]
    public function resetAfterTransaction()
    {
        $this->mode = false;
        
        $this->dispatch('modeToggled', $this->mode);
    }

    public function render()
    {
        return view('livewire.admin.toggle-mode');
    }
}