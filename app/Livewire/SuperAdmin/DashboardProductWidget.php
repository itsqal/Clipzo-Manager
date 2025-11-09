<?php

namespace App\Livewire\Superadmin;

use App\Models\Product;
use Livewire\Component;

class DashboardProductWidget extends Component
{
    public $products;
    public $offset = 0;
    public $limit = 10;

    public function mount()
    {
        $this->products = Product::all();
    }

    public function nextBatch()
    {
        $this->offset += $this->limit;

        if ($this->offset >= $this->products->count()) {
            $this->offset = 0;
        }
    }

    public function render()
    {
        return view('livewire.super-admin.dashboard-product-widget', [
            'visibleProducts' => $this->products->slice($this->offset, $this->limit)
        ]);
    }
}
