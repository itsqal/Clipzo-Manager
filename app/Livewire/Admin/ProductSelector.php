<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Product;

class ProductSelector extends Component
{
    public bool $mode = false;

    public array $selectedProducts = [];

    public $products = [];

    public function mount()
    {
        $this->products = Product::orderBy('created_at', 'asc')->get();
    }

    // Event catcher
    #[On('modeToggled')]
    public function updateMode(bool $mode)
    {
        $this->mode = $mode;
        if (!$mode) {
            $this->selectedProducts = [];
            $this->dispatchSummaryUpdate();
        }
    }

    public function selectProduct(int $id, string $name, float $price)
    {
        if (!$this->mode) {
            return;
        }
        
        $index = array_search($id, array_column($this->selectedProducts, 'id'));

        if ($index !== false) {
            unset($this->selectedProducts[$index]);
        } else {
            $this->selectedProducts[] = [
                'id' => $id,
                'name' => $name,
                'price' => $price,
                'type' => 'product',
                'quantity' => 1,
            ];
        }

        $this->dispatchSummaryUpdate();
    }
    
    protected function dispatchSummaryUpdate()
    {
        $this->dispatch('productSelectionUpdated', products: $this->selectedProducts);
    }

    public function render()
    {
        return view('livewire.admin.product-selector');
    }
}