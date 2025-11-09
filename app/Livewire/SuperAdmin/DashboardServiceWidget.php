<?php

namespace App\Livewire\Superadmin;

use App\Models\Service;
use Livewire\Component;

class DashboardServiceWidget extends Component
{
    public $services;
    public $offset = 0;
    public $limit = 10;

    public function mount()
    {
        $this->services = Service::all();
    }

    public function nextBatch()
    {
        $this->offset += $this->limit;

        if ($this->offset >= $this->services->count()) {
            $this->offset = 0;
        }
    }

    public function render()
    {
        return view('livewire.super-admin.dashboard-service-widget', [
            'visibleServices' => $this->services->slice($this->offset, $this->limit)
        ]);
    }
}
