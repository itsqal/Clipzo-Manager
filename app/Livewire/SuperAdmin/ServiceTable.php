<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\Service;
use Livewire\WithPagination;
use Livewire\Attributes\On; 

class ServiceTable extends Component
{
    use WithPagination;

    public $service_name;
    public $service_description;
    public $service_price;
    public $selectedService;

    protected $rules = [
        'service_name' => 'required|string|max:255',
        'service_price' => 'required|numeric|min:0',
        'service_description' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'service_name.required' => 'Nama layanan tidak boleh kosong.',
        'service_price.required' => 'Harga layanan tidak boleh kosong.',
        'service_price.numeric' => 'Harga layanan harus berupa angka.',
    ];

    #[On('service-updated')]
    public function refreshTable()
    {
        $this->resetPage();
    }

    public function viewDeleteService($serviceId)
    {
        $this->selectedService = Service::findOrFail($serviceId);
        $this->dispatch('open-modal', name: 'delete-service-modal');
    }

    public function viewEditService($serviceId)
    {
        $this->selectedService = Service::findOrFail($serviceId);

        $this->service_name = $this->selectedService->name;
        $this->service_price = $this->selectedService->price;
        $this->service_description = $this->selectedService->description;

        $this->dispatch('open-modal', name: 'edit-service-modal');
    }

    public function updateService()
    {
        $this->validate();

        if (!$this->selectedService) {
            session()->flash('message', 'Tidak ada layanan yang dipilih.');
            return;
        }

        $this->selectedService->update([
            'name' => $this->service_name,
            'price' => $this->service_price,
            'description' => $this->service_description,
        ]);

        $this->dispatch('flash-message', [
            'message' => 'Data layanan berhasil diperbarui.',
            'type' => 'update'
        ]);

        $this->reset(['selectedService', 'service_name', 'service_price', 'service_description']);
        $this->dispatch('close-modal', name: 'edit-service-modal');
        $this->dispatch('service-updated');
    }

    public function deleteService()
    {
        $service = Service::findOrFail($this->selectedService->id);
        $service->delete();

        $this->dispatch('flash-message', [
            'message' => 'Data layanan berhasil dihapus.',
            'type' => 'delete'
        ]);

        $this->reset('selectedService');
        $this->dispatch('service-updated');
        $this->dispatch('close-modal');
    }

    public function render()
    {
        $services = Service::latest()->paginate(5);

        return view('livewire.super-admin.service-table', [
            'services' => $services,
        ]);
    }
}