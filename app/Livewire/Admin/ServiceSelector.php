<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Service;

class ServiceSelector extends Component
{
    public bool $mode = false;

    public array $selectedServices = [];

    public $services = [];

    public function mount()
    {
        $this->services = Service::orderBy('created_at', 'asc')->get();
    }

    // Event catcher
    #[On('modeToggled')]
    public function updateMode(bool $mode)
    {
        $this->mode = $mode;
        if (!$mode) {
            $this->selectedServices = [];
            $this->dispatchSummaryUpdate();
        }
    }

    public function selectService(int $id, string $name, float $price)
    {
        if (!$this->mode) {
            return;
        }
        
        $index = array_search($id, array_column($this->selectedServices, 'id'));

        if ($index !== false) {
            unset($this->selectedServices[$index]);
        } else {
            $this->selectedServices[] = [
                'id' => $id,
                'name' => $name,
                'price' => $price,
                'type' => 'service',
                'quantity' => 1,
            ];
        }

        $this->dispatchSummaryUpdate();
    }
    
    protected function dispatchSummaryUpdate()
    {
        $this->dispatch('serviceSelectionUpdated', services: $this->selectedServices);
    }

    public function render()
    {
        return view('livewire.admin.service-selector');
    }
}